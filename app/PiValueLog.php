<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PiValueLog extends Model
{
    public $timestamps = false;
    //the Last Current Pi Value store every 10 minutes
    //this value is reference to calculate current value if we lose cahce.

    protected $fillable = [
        'current_value', 'total_propose', 'propose_time', 'propose_id',
    ];
}
