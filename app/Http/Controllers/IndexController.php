<?php

namespace App\Http\Controllers;

use App\Http\Services\Binance;
use App\Http\Services\BinanceService;
use App\Http\Services\WantService;
use App\Models\TradeRecord;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    public function getIndex()
    {
         //bitmex test
         list($s, $amount) = WantService::getBalanceAll('bitmex');
         $xbt = $amount['amount'] / pow(10, 8);
         list($s, $walletHistory) = WantService::getWalletHistory('bitmex');
         list($s, $affi) = WantService::getAffiliateStatus('bitmex');
         dd($affi);
         return view('bitmex', ['xbt'=> $xbt, 'list' => $walletHistory]);
//         dd($walletHistory);

         //test
         $obj = new StrategyController();
         $obj->highFrequency();
         dd(1);
         $data = WantService::markPriceChange();
//         var_dump($data);
         dd($data);

        // test
//        BinanceService::tradeBtc2();
////        dd(1);
//        $pair = 'BTCUSDT'; $period = '30m';
//        $macds = BinanceService::getMACD($pair, $period);
//        dd($macds);

        $btc = Redis::get('2_binance:btc');
        if (is_null($btc)) $btc = 0;
        $usdt = Redis::get('2_binance:usdt');
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