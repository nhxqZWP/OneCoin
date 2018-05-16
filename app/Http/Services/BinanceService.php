<?php

namespace App\Http\Services;

class BinanceService
{
    public static function getMACD($pair = 'BTCUSDT', $period = '1h', $short=12,$long=26,$m=9)
    {
        //Periods: 1m,3m,5m,15m,30m,1h,2h,4h,6h,8h,12h,1d,3d,1w,1M

    }

    public static function getEMA($param)
    {

    }

    public static function getCandleSticks($pair = 'BTCUSDT', $period = '1h')
    {
        $key = config('platform.binance.key');
        $secret = config('platform.binance.secret');
        $api = new Binance($key, $secret);
        $data = $api->candlesticks($pair, $period);;
        if (is_array($data)) return $data;
        return null;
    }
}