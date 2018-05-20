<?php

namespace App\Http\Services;

use App\Models\TradeRecord;
use Illuminate\Support\Facades\Redis;

class BinanceService
{
    public static function getMACD($pair = 'BTCUSDT', $period = '1h', $short=12,$long=26,$m=9)
    {
        //Periods: 1m,3m,5m,15m,30m,1h,2h,4h,6h,8h,12h,1d,3d,1w,1M
        $kList = self::getCandleSticks($pair, $period);
        $kStockList = [];
        $i = 0;
        foreach ($kList as $k => $v) {
            if ($i == 0) {
                $kStockList[$i]['ema12'] = $v['close'];
                $kStockList[$i]['ema26'] = $v['close'];
                $kStockList[$i]['dif'] = 0;
                $kStockList[$i]['dea'] = 0;
                $kStockList[$i]['macd'] = 0;
                $kStockList[$i]['timestamp'] = $k;
                $i++;
            } else {
                $kStockList[$i]['ema12'] = (2.0 * $v['close'] + ($short-1) * $kStockList[$i-1]['ema12']) / ($short+1);
                $kStockList[$i]['ema26'] = (2.0 * $v['close'] + ($long-1) * $kStockList[$i-1]['ema26']) / ($long+1);
                $kStockList[$i]['dif'] = $kStockList[$i]['ema12'] - $kStockList[$i]['ema26'];
                $kStockList[$i]['dea'] = (2.0 * $kStockList[$i]['dif'] + ($m-1)*$kStockList[$i-1]['dea']) / ($m+1);
//                $kStockList[$k]['macd'] = 2.0 * ($kStockList[$k]['dif'] - $kStockList[$k]['dea']);
                $kStockList[$i]['macd'] = $kStockList[$i]['dif'] - $kStockList[$i]['dea'];
                $kStockList[$i]['timestamp'] = $k;
                $i++;
            }
        }
        return $kStockList;
    }

    public static function getCandleSticks($pair = 'BTCUSDT', $period = '1h')
    {
//        $key = config('platform.binance.key');
//        $secret = config('platform.binance.secret');
//        $api = new Binance($key, $secret);
        $api = app('Binance');
        $data = $api->candlesticks($pair, $period);;
//        if (is_array($data)) return array_values($data);
        if (is_array($data)) return $data;
        return null;
    }

    public static function tradeBtc()
    {
        $macds = self::getMACD($pair = 'BTCUSDT', $period = '30m');
        $newMacd = $macds[1]['macd'];
        $preMacd = $macds[2]['macd'];

        // 钱包余额
        $btc = Redis::get('binance:btc');
        $usdt = Redis::get('binance:usdt');
        if (is_null($usdt)) $usdt = 5000;
        $keyStatus = 'binance:'.$pair.$period;

        // 买点 MACD 先<0后>0且值>5
        if ($preMacd < 0 && $newMacd > 0) {
            if ($newMacd > 5) {
                $status = Redis::get($keyStatus);
                if (is_null($status) || $status == 0) {
                    Redis::set($keyStatus, 1); //下买单
                    $amount = TradeRecord::createBuyOrder($usdt, $pair);
                    Redis::set('binance:btc', $amount / pow(10, 8));
                }
            }
            Redis::set($keyStatus, 2); // 标记
        }

        // 卖点 MACD 第二次下降 或 先>0后<0
    }
}