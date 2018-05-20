<?php

namespace App\Http\Controllers;

use App\Http\Services\Binance;
use App\Http\Services\BinanceService;
use App\Models\TradeRecord;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    public function getIndex()
    {
        $btc = Redis::get('binance:btc');
        if (is_null($btc)) $btc = 0;
        $usdt = Redis::get('binance:usdt');
        if (is_null($usdt)) $usdt = 5000;
        $data = TradeRecord::get();
        return view('welcome', ['list' => $data, 'btc' => $btc, 'usdt' => $usdt]);
//        $key = config('platform.binance.key');
//        $secret = config('platform.binance.secret');
//        $api = new Binance($key, $secret);
//        $api = app('Binance');
//        $price = $api->prices()['BTCUSDT'];
//        var_dump($price);
//        $depth = $api->depth('BTCUSDT');
//        var_dump($depth);

//        $data = BinanceService::getMACD($pair = 'BTCUSDT', $period = '30m');
//        dd($data);
//
//        $data = $api->candlesticks("BTCUSDT", '1h');
//        $data = array_values($data);
//        dd($data);
    }
}