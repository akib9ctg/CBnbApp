<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertiesTags extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $table='properties_tags';
    public $timestamps = false;
    protected $fillable = [
        'tag_id','property_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}