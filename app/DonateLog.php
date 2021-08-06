<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DonateLog extends Model
{
    public $timestamps = false;
    //
    protected $fillable = [
        'from_date', 'to_date', 'id_from', 'id_to','all_donate', 'total_donate',
        'draw_date', 'drawed_id', 'drawed_username', 'paid', 'txid',
    ];
}
