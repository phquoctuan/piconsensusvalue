<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    //Proposal data of all poonineer

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'propose', 'current', 'donate', 'public_key', 'note',
    ];
}
