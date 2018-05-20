<?php

namespace App\Http\Controllers;

use App\Http\Services\Binance;
use App\Http\Services\BinanceService;

class IndexController extends Controller
{
    public function getIndex()
    {
//        $key = config('platform.binance.key');
//        $secret = config('platform.binance.secret');
//        $api = new Binance($key, $secret);
//        $api = app('Binance');
//        $price = $api->prices()['BTCUSDT'];
//        var_dump($price);
//        $depth = $api->depth('BTCUSDT');
//        var_dump($depth);

        $data = BinanceService::getMACD($pair = 'BTCUSDT', $period = '30m');
        dd($data);
        var_dump(array_reverse($data));
//
//        $data = $api->candlesticks("BTCUSDT", '1h');
//        $data = array_values($data);
//        dd($data);
    }
}