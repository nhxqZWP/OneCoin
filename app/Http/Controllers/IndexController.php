<?php

namespace App\Http\Controllers;

use App\Http\Services\Binance;
use App\Http\Services\BinanceService;

class IndexController extends Controller
{
    public function getIndex()
    {
        $key = config('platform.binance.key');
        $secret = config('platform.binance.secret');
        $api = new Binance($key, $secret);
        $price = $api->prices();
        var_dump($price);
        $data = $api->candlesticks("BTCUSDT", '30m');
        $data = array_values($data);
        dd($data);
    }
}