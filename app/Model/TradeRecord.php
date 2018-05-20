<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TradeRecord extends Model
{
    protected $table = 'trade_record';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function createBuyOrder($usdt, $pair)
    {
        $api = app('Binance');
        $price = $api->prices()[$pair];
        $amount = floor($usdt / $price * pow(10, 8));
        $data = [
            'price' => $price * pow(10, 8),
            'amount' => $amount,
            'type' => 0,
            'profit' => 0,
        ];
        self::insert($data);
        return $amount;
    }
}