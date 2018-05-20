<?php

namespace App\Http\Controllers;

use App\Http\Services\Binance;
use App\Http\Services\BinanceService;

class IndexController extends Controller
{
    public function getIndex()
    {
        $data = BinanceService::getMACD($pair = 'BTCUSDT', $period = '30m');
        dd(array_reverse($data));

        $key = config('platform.binance.key');
        $secret = config('platform.binance.secret');
        $api = new Binance($key, $secret);
        $price = $api->prices();
        var_dump($price);
        $data = $api->candlesticks("BTCUSDT", '1h');
        $data = array_values($data);
        dd($data);
    }
}