<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Properties extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected  $primaryKey = 'property_id';
    protected $fillable = [
        'status','property_id','updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}