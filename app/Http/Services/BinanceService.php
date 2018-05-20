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
        return array_reverse($kStockList);
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
        $timestamp = $macds[0]['timestamp'];
        $timeMark = Redis::get('binance:timestamp'.$pair);
        if (!is_null($timeMark) && $timeMark == $timestamp) return null;
        $newMacd = $macds[1]['macd'];
        $preMacd = $macds[2]['macd'];

        // 钱包余额
        $btc = Redis::get('binance:btc');
        $usdt = Redis::get('binance:usdt');
        if (is_null($usdt)) $usdt = 5000;
        $keyStatus = 'binance:'.$pair.$period;
        $status = Redis::get($keyStatus);
        if (is_null($status)) $status = 0;

        // 买点 MACD 先<0后>0且值>5
        if ($preMacd < 0 && $newMacd > 0) {
            if ($newMacd > 5) {
                if (is_null($status) || $status == 0) {
                    $buyBtc = TradeRecord::createBuyOrder($usdt, $pair);
                    Redis::set('binance:btc', $buyBtc / pow(10, 8));
                    Redis::set('binance:usdt', 0);
                    Redis::set($keyStatus, 1); //下买单并成交
                }
            } else {
                Redis::set($keyStatus, 2); // 标记金叉但不足5
            }
        }
        if ($status == 2 && $newMacd > 5) {
            $amount = TradeRecord::createBuyOrder($usdt, $pair);
            Redis::set('binance:btc', $amount / pow(10, 8));
            Redis::set('binance:usdt', 0);
            Redis::set($keyStatus, 1); //下买单并成交
        }

        // 卖点 MACD 第二次下降 或 先>0后<0
        if ($status == 1) {
            $do = 0;
            if ($preMacd > $newMacd) {
                if ($status == 3) {
                    $sellUsdt = TradeRecord::createSellOrder($btc, $pair, 5000);
                    Redis::set('binance:btc', 0);
                    Redis::set('binance:usdt', $sellUsdt);
                    Redis::set($keyStatus, 4); //下卖单并成交
                    $do = 1;
                } else {
                    Redis::set($keyStatus, 3);
                }
            }
            if ($preMacd > 0 && $newMacd < 0 && $do == 0) {
                $sellUsdt = TradeRecord::createSellOrder($btc, $pair, 5000);
                Redis::set('binance:btc', 0);
                Redis::set('binance:usdt', $sellUsdt);
                Redis::set($keyStatus, 4); //下卖单并成交
            }
        }

        Redis::set('binance:timestamp'.$pair, $timestamp);
    }
}