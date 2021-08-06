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
        'uid',
        'username',
        'paymentid' ,
        'propose',
        'current',
        'donate',
        'note',
        'ipaddress',
        'txid',
        'fromwallet',
        'towallet',
        'txlink',
        'completed'
    ];
}
