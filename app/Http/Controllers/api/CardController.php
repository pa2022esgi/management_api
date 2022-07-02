<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    protected $custom_validator = [
        'title.required' => 'Un nom est requis',
        'title.max' => 'Le nom ne doit pas dépasser 50 caractères',
        'status_id.required' => 'Un statut est requis',
        'due_date.required' => 'Une date est requise',
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
    public function store(Project $project, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'description' => 'string|nullable',
            'labels' => 'array|nullable',
            'status_id' => 'required|integer',
            'user_id' => 'nullable|integer',
            'due_date' => 'date|required',
        ], $this->custom_validator);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $card = Card::create(array_merge(
            $validator->validated(),
            ['project_id' => $project->id]
        ));
        
        if ($request->has('labels')) {
            $card->labels()->attach($request->labels);
        }

        return response()->json([
            'message' => 'Task successfully created',
            'task' => $card
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, Card $card)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            'description' => 'string|nullable',
            'labels' => 'array|nullable',
            'status_id' => 'required|integer',
            'user_id' => 'nullable|integer',
            'due_date' => 'date|required',
        ], $this->custom_validator);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $card->update($validator->validated());
        
        if ($request->has('labels')) {
            $card->labels()->sync($request->labels);
        }

        return response()->json([
            'message' => 'Task successfully updated',
            'task' => $card
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Card $card)
    {
        $card->delete();

        return response()->json([
            'message' => 'Task successfully deleted'
        ], 200);
    }

    public function getStatuses()
    {
        return Status::all();
    }
}
