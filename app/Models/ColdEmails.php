<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColdEmails extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $table='cold_emails';
    public $timestamps = false;
    protected $fillable = [
        'email_id','email'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}