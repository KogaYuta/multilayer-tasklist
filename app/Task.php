<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id', 'parent_id', 'project_flag', 'content', 'selected'
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
