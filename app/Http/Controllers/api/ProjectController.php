<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    protected $custom_validator = [
        'name.required' => 'Un nom est requis',
        'name.max' => 'Le nom ne doit pas dépasser 50 caractères',
        '*.name.required' => 'Un nom de label est requis',
        '*.color.required' => 'Une couleur de label est requise',
        'token.required' => 'Un code est requis'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'description' => 'string|nullable',
            'labels' => 'array|nullable',
        ], $this->custom_validator);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $project = Project::create(array_merge(
            $validator->validated(),
            ['created_by' => auth()->user()->id]
        ));

        $project->token = $this->generateToken();
        $project->save();

        $project->users()->attach(auth()->user()->id);

        if ($request->has('labels')) {
            $project->labels()->delete();

            $validator = Validator::make($request->all()['labels'], [
                '*.name' => 'required|string',
                '*.color' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            
            $project->labels()->createMany($validator->validated());
        }

        return response()->json([
            'message' => 'Project successfully created',
            'project' => $project
        ], 201);
    }

    public function generateToken() 
    {
        $token = "";
        do {
            $token = Str::random(7);
        } while (project::where('token', $token)->exists());

        return strtoupper($token);
    }

    public function join(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ], $this->custom_validator);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $project = Project::where('token', $validator->validated()['token'])->first();

        if ($project) {
            if ($project->users->contains(auth()->user()->id)) {
                return response()->json([
                    'error' => 'Vous êtes déjà dans ce projet'
                ], 400);
            } else {
                $project->users()->attach(auth()->user()->id);
                return response()->json([
                    'message' => 'You joined the project',
                    'project' => $project
                ], 201);
            }
        } else {
            return response()->json([
                'error' => 'Projet introuvable'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return $project;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
