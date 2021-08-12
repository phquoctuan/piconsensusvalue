<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DonateLog extends Model
{
    public $timestamps = false;
    //
    protected $fillable = [
        'from_date', 'to_date', 'id_from', 'id_to', 'total_propose', 'count_donate', 'total_donate', 'reward', 'remain_donate',
        'draw_date', 'drawed_id', 'drawed_username', 'paid', 'txid', 'fromwallet', 'towallet'
    ];

    // protected $dates = [
    //     'seen_at',
    // ];
    // protected $casts = [
    //     'is_admin' => 'boolean',
    // ];
    protected $casts = [
        'from_date' => 'datetime',
        'to_date' => 'datetime',
        'draw_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d',
    ];
}
