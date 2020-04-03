<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['title', 'task', 'last_name', 'active',
							'due_date', 'files'];

	// Связь с таблицей Models
    public function steps(){
        return $this->hasMany('App\Todo_step', 'todo_id');
    }						
}
