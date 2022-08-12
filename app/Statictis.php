<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statictis extends Model
{
    protected $table = 'statictis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'from',
        'to' ,
        'total'
    ];
}
