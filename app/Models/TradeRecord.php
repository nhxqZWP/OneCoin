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

    public static function createSellOrder($btc, $pair, $init = 5000)
    {
        $preProfitRe = self::where('type', 1)->orderBy('id', 'desc')->first();
        if (is_null($preProfitRe)) {
            $preAmount = $init * pow(10, 8);
        } else {
            $preAmount = $preProfitRe->amount;
        }
        $api = app('Binance');
        $price = $api->prices()[$pair];
        $amount = floor($btc * $price);
        $data = [
            'price' => $price * pow(10, 8),
            'amount' => $amount * pow(10, 8),
            'type' => 1,
            'profit' => ($amount * pow(10, 8) - $preAmount) / $preAmount * pow(10, 8),
        ];
        self::insert($data);
        return $amount;
    }
}