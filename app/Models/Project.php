<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ['id'];

    public function labels()
    {
        return $this->hasMany(Label::class);
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, UserProject::class, 'project_id', 'user_id');
    }
}
