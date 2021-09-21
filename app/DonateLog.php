<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DonateLog extends Model
{
    public $timestamps = false;
    //
    protected $fillable = [
        'from_date', 'to_date', 'id_from', 'id_to', 'total_propose', 'count_donate', 'total_donate', 'reward', 'remain_donate',
        'draw_date', 'drawed_id', 'drawed_username', 'paid', 'txid', 'fee', 'fromwallet', 'towallet',
        'drawed_id2', 'drawed_username2', 'paid2', 'txid2', 'fee2', 'fromwallet2', 'towallet2',
        'drawed_id3', 'drawed_username3', 'paid3', 'txid3', 'fee3', 'fromwallet3', 'towallet3',
        'atl_value', 'ath_value', 'atl_propose', 'ath_propose', 'atl_donate', 'ath_donate'
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
