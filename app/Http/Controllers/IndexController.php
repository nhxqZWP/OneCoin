<?php

namespace App\Http\Controllers;

use App\Http\Services\Binance;

class IndexController extends Controller
{
    public function getIndex()
    {
        $key = config('platform.binance.key');
        $secret = config('platform.binance.secret');
        $api = new Binance($key, $secret);
        $data = $api->candlesticks("BTCUSDT", '1h');;
        dd($data);
    }
}