<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TradeRecord extends Model
{
    protected $table = 'trade_record';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function createBuyOrder($usdt)
    {

    }
}