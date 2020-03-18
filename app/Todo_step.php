<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo_step extends Model
{
    protected $fillable = ['step', 'todo_id'];

    // Связь с таблицей Todo
    public function todo(){
        return $this->belongsTo('App\Todo');
    }
}
