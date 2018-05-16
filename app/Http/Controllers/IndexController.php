<?php

namespace App\Http\Controllers;

use App\Http\Services\Binance;
use App\Http\Services\BinanceService;

class IndexController extends Controller
{
    public function getIndex()
    {
        $data = BinanceService::getMACD();
        dd($data);

        $key = config('platform.binance.key');
        $secret = config('platform.binance.secret');
        $api = new Binance($key, $secret);
        $data = $api->candlesticks("BTCUSDT", '1h');
        $data = array_values($data);
        dd($data);
    }
}