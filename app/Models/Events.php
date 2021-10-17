<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $fillable = [
        'username','is_admin','property_id','event_type','source_page','datetime'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}