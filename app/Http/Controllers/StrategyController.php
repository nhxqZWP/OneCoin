<?php

namespace App\Http\Controllers;

use App\Http\Services\WantService;
use App\Services\LockService;
use Illuminate\Support\Facades\Redis;

class StrategyController extends Controller
{
     // 余额百分百交易
     public function highFrequency($platform = 'fcoin', $pair = 'btcusdt', $addPricePercent = 0.002)  // 0.1手续费时
     {
          $mark = WantService::getMarkPriceChange($platform, $pair, 'M1');
          if ($mark <= -3) {
               ini_set('memory_limit', '500M'); //内存限制
               set_time_limit(0);
//               $api = app('Binance');
//               $ticker = implode('', explode('_', $pair));  // pair - ETH_USDT  ticker - EHTUSDT

               // 加锁
               $lockKey = $platform.$pair;
               $isLock = LockService::lock($lockKey);
               if (!$isLock) {
                    return ['result' => true, 'message' => 'trigger lock '.$lockKey];
               }

               $sellNumber = Redis::get($platform.':sell:number_'.$pair);
//               $sellStatus = $api->orderStatus($ticker, $sellNumber);
               list($s, $sellStatus) = WantService::getOrderTransaction($platform, $sellNumber);
               if (!is_null($sellNumber) && $sellStatus['side'] == 'SELL' && ($sellStatus['status'] == 'NEW' || $sellStatus['status'] == 'PARTIALLY_FILLED')) {
                    // 有未完成卖单
                    LockService::unlock('binance:lock:shot_2');
                    return ['result' => true, 'message' => 'have unfinished sell order'];
               } else {
                    $buyNumber = Redis::get('binance:buy:number_'.$pair.'2');
                    $buyStatus = $api->orderStatus($ticker, $buyNumber);
                    $buyDeal = Redis::get('binance:buy:mark_'.$pair.'2');
                    if (!is_null($buyNumber) && $buyStatus['side'] == 'BUY' && ($buyStatus['status'] == 'NEW' || $buyStatus['status'] == 'PARTIALLY_FILLED')) {
                         // 无卖单 有未完成的买单
                         //判断是否到了最长买单时间
                         $runTimeLimit = Redis::get(ConsoleService::BINANCE_RUN_TIME_LIMIT_KEY.'2');
                         if (is_null($runTimeLimit) && $buyDeal == 1) {
                              if ($buyStatus['status'] == 'PARTIALLY_FILLED') {
                                   LockService::unlock('binance:lock:shot_2');
                                   return ['result' => true, 'message' => 'have partially filled buy order'];
                              }
                              $api->cancel($ticker, $buyNumber);
                              Redis::set('binance:buy:mark_'.$pair.'2', 2);
                              LockService::unlock('binance:lock:shot_2');
                              return ['result' => true, 'message' => 'auto cancel buy order'];
                         }
                         LockService::unlock('binance:lock:shot_2');
                         return ['result' => true, 'message' => 'have unfinished buy order'];
                    }
                    $quantity = Redis::get('binance:buy:quantity_'.$pair.'2'); //买卖单数量
                    if (is_null($quantity)) $quantity = 0.04;  // 买卖1个eth

                    // 无卖单或卖单完成 且 无买单或买单完成或买单取消 则下买单
                    $noSell = is_null($sellNumber) || isset($sellStatus['status']) && $sellStatus['status'] == 'FILLED';
                    $noBuy = is_null($buyNumber) || (isset($buyStatus['status'])&&($buyStatus['status'] == 'FILLED' || $buyStatus['status'] == 'CANCELED'));
                    if ($noSell && $noBuy && (is_null($buyDeal) || $buyDeal == 2)) {
                         $depth = $api->depth($ticker);
                         $depthBids = array_keys($depth['bids']);
                         $buyDepthNumber = Redis::get('binance:buy:offset_'.$pair.'2'); //买单偏移数
                         if (is_null($buyDepthNumber)) $buyDepthNumber = 3;
                         $price = $depthBids[$buyDepthNumber];
                         $res = $api->buy($ticker, $quantity, $price);
                         if (!isset($res['status'])) return ['result' => false, 'message' => '2:'.json_encode($res).'qua:'.$quantity.'pri:'.$price];
                         if ($res['status'] == 'NEW' || $res['status'] == 'PARTIALLY_FILLED' || $res['status'] == 'FILLED') {
                              Redis::set('binance:buy:number_'.$pair.'2', $res['orderId']);
                              Redis::set('binance:buy:price_'.$pair.'2', $res['price']);
                              Redis::set('binance:buy:mark_'.$pair.'2', 1); //标记买单创建
                              // 设定此次挂单时间
                              $timeLimit = Redis::get(ConsoleService::BINANCE_RUN_TIME_LIMIT_VALUE);
                              if (is_null($timeLimit)) $timeLimit = 30;
                              Redis::setex(ConsoleService::BINANCE_RUN_TIME_LIMIT_KEY.'2', $timeLimit, '1');
                              LockService::unlock('binance:lock:shot_2');
                              return ['result' => true, 'message' => 'create buy order success '.json_encode($res)];
                         } else {
                              LockService::unlock('binance:lock:shot_2');
                              return ['result' => false, 'message' => 'create buy order fail '.json_encode($res)];
                         }
                    } else {
                         // 有完成的买单 则下卖单
                         if (!is_null($buyNumber) && $buyStatus['side'] == 'BUY' && $buyStatus['status'] == 'FILLED') {
                              $sellDepthNumber = Redis::get('binance:sell:offset_'.$pair); //卖单偏移值
                              if (is_null($sellDepthNumber)) $sellDepthNumber = 0.2;
                              $sellPrice = Redis::get('binance:buy:price_'.$pair.'2') * (1+0.001) + $sellDepthNumber;
                              $sellPrice = number_format($sellPrice, 2, '.', '');
                              $res = $api->sell($ticker, $quantity, $sellPrice);
                              if (isset($res['msg'])) {
                                   LockService::unlock('binance:lock:shot_2');
                                   return ['result' => false, 'message' => $res['msg']];
                              }
                              if ($res['status'] == 'NEW' || $res['status'] == 'PARTIALLY_FILLED' || $res['status'] == 'FILLED') {
                                   Redis::set('binance:sell:number_'.$pair.'2', $res['orderId']);
                                   Redis::set('binance:buy:mark_'.$pair.'2', 2); //标记对应买单处理了
                                   LockService::unlock('binance:lock:shot_2');
                                   return ['result' => true, 'message' => 'create sell order success '.json_encode($res)];
                              } else {
                                   LockService::unlock('binance:lock:shot_2');
                                   return ['result' => false, 'message' => 'create sell order fail '.json_encode($res)];
                              }
                         }
                    }
               }
               LockService::unlock('binance:lock:shot_2');
               return ['result' => false, 'message' => 'have no action'];
          }
     }
}