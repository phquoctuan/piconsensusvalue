<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PiValueLog extends Model
{
    public $timestamps = false;
    //the Last Current Pi Value store every 10 minutes
    //this value is reference to calculate current value if we lose cahce.

    protected $fillable = [
        'current_value', 'total_propose', 'sum_donate', 'propose_time', 'propose_id',
        'atl_value', 'ath_value', 'atl_propose', 'ath_propose', 'atl_donate', 'ath_donate'
    ];
}
