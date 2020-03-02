<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = false;

    protected $fillable = ['first_name', 'middle_name', 'last_name', 'position',
							'email', 'work_phone', 'mobile_phone'];
}
