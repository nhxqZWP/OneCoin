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
        foreach ($kList as $k => $v) {
            if ($k == 0) {
                $kStockList[$k]['ema12'] = $v['close'];
                $kStockList[$k]['ema26'] = $v['close'];
                $kStockList[$k]['dif'] = 0;
                $kStockList[$k]['dea'] = 0;
                $kStockList[$k]['macd'] = 0;
            } else {
                $kStockList[$k]['ema12'] = (2.0 * $v['close'] + ($short-1) * $kStockList[$k-1]['ema12']) / ($short+1);
                $kStockList[$k]['ema26'] = (2.0 * $v['close'] + ($long-1) * $kStockList[$k-1]['ema26']) / ($long+1);
                $kStockList[$k]['dif'] = $kStockList[$k]['ema12'] - $kStockList[$k]['ema26'];
                $kStockList[$k]['dea'] = (2.0 * $kStockList[$k]['dif'] + ($m-1)*$kStockList[$k-1]['dea']) / ($m+1);
//                $kStockList[$k]['macd'] = 2.0 * ($kStockList[$k]['dif'] - $kStockList[$k]['dea']);
                $kStockList[$k]['macd'] = $kStockList[$k]['dif'] - $kStockList[$k]['dea'];
            }
        }
        return $kStockList;
    }

    public static function getCandleSticks($pair = 'BTCUSDT', $period = '1h')
    {
        $key = config('platform.binance.key');
        $secret = config('platform.binance.secret');
        $api = new Binance($key, $secret);
        $data = $api->candlesticks($pair, $period);;
        if (is_array($data)) return array_values($data);
        return null;
    }

    public static function tradeBtc()
    {
        $macds = self::getMACD($pair = 'BTCUSDT', $period = '30m');
        $newMacd = $macds[1]['macd'];
        $preMacd = $macds[2]['macd'];
        $btc = Redis::get('binance:btc');
        $usdt = Redis::get('binance:usdt');
        $keyStatus = 'binance:'.$pair.$period;
        // 买点 MACD 先<0后>0且值>5
        if ($preMacd < 0 && $newMacd > 0) {
            if ($newMacd > 5) {
                Redis::set($keyStatus, 1); //下买单
                TradeRecord::createBuyOrder($usdt);
            }
        }

        // 卖点 MACD 第二次下降 或 先>0后<0
    }
}