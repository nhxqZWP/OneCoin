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
//        $price = 7318.00;
        $buy = floor($usdt / $price * pow(10, 8));
        // 下买单 todo
        $data = [
            'price' => $price * pow(10, 8),
            'use' => $usdt * pow(10, 8),
            'amount' => $buy,
            'type' => 0,
            'profit' => 0,
        ];
        self::insert($data);
        return $buy / pow(10,8);
    }

    public static function createSellOrder($btc, $pair)
    {
        $buyRecord = self::where('type', 0)->orderBy('id', 'desc')->first();
        $useUsdt = $buyRecord->use;
        $api = app('Binance');
        $price = $api->prices()[$pair];
//        $price = 7320.00;
        $sell = floor($btc * $price * pow(10, 8) * (1-0.002)) ;
        // 下卖单 todo
        $data = [
            'price' => $price * pow(10, 8),
            'use' => $btc * pow(10, 8),
            'amount' => $sell,
            'type' => 1,
            'profit' => ($sell - $useUsdt) / $useUsdt * pow(10, 8),
        ];
        self::insert($data);
        return $sell / pow(10, 8);
    }
}