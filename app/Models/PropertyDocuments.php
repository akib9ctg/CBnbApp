<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyDocuments extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $table='property_documents';
    public $timestamps = false;
    protected $fillable = [
        'document_url','property_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}