<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailInputEvents extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $table='email_input_events';
    public $timestamps = false;
    protected $fillable = [
        'email_input_id','username'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}