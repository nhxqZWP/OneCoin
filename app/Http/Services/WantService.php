<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Redis;

class WantService
{
     // 查询服务器时间
     public static function getServerTime($platform = 'binance')
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getServerTime();
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    };
                    break;
          }
          return $data;
     }

     // 查询可用交易对
     public static function getSymbols($platform = 'binance')
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getSymbols();
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    };
                    break;
               case 'bitmex':
                    $data = app('Bitmex')->getTicker();
                    break;
          }
          return $data;
     }

     // 查询可用币种
     public static function getCurrencies($platform = 'binance')
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getCurrencies();
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    };
                    break;
          }
          return $data;
     }

     // 获取全部账户资产
     public static function getBalanceAll($platform = 'binance')
     {
//          "currency" => "etc"
//          "category" => "main"
//          "available" => "0.000000000000000000"
//          "frozen" => "0.000000000000000000"
//          "balance" => "0.000000000000000000"

          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getBalance();
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    };
                    break;
               case 'bitmex':
                    $data = app('Bitmex')->getWallet();
//                    $data = app('Bitmex')->getAffiliateStatus();
                    break;
          }
          return $data;
     }

     // 获取指定币种资产
     public static function getBalanceOne($platform = 'binance', $currency = 'btc')
     {
          $res = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getBalance();
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         foreach ($data['data'] as $v) {
                              if ($v['currency'] == $currency) {
                                   $res = $v;
                              }
                         }
                         //res       "currency" => "etc"
                         //          "category" => "main"
                         //          "available" => "0.000000000000000000"
                         //          "frozen" => "0.000000000000000000"
                         //          "balance" => "0.000000000000000000"
                         $data = [0, $res];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    }
                    break;
          }
          return $data;
     }

     // 创建订单
     public static function createOrders($platform = 'binance', $symbol = 'btcusdt', $side = 'buy', $type = 'limit', $price = '5000', $amount = '0.001')
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::createOrders(['symbol' => $symbol, 'side' => $side, 'type' => $type, 'price' => $price, 'amount' => $amount]);
                    /**
                     *   Create order
                     *   POST https://api.fcoin.com/v2/orders
                     *
                     *   @param Array $order,
                     *    'symbol' order symbol  (required)
                     *    'side' 'buy' or 'sell'  (required)
                     *    'type' 'limit' or 'market' (required)
                     *    'price' price of the order (required)
                     *    'amount' amount of the order (required)
                     *
                     *   @return json result
                     *    {
                     *    "status":0,
                     *    "data":"9d17a03b852e48c0b3920c7412867623"
                     *    }
                     *
                     *   @example createOrders(['symbol' => 'btcustd', 'side' => 'buy', 'type' => 'limit', 'price' => '300', 'amount' => '0.01'])
                     */
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    }
                    break;
          }
          return $data;
     }

     // 获取订单列表
     public static function getOrderslist($platform = 'binance', $symbol = 'btcusdt', $states = 'submitted, partial_filled, partial_canceled, filled, canceled')
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getOrderslist(['symbol' => $symbol, 'states' => $states]);
                    /**
                     *   Get order list
                     *   GET https://api.fcoin.com/v2/orders
                     *
                     *   @param Array $criteria,
                     *    'symbol' order symbol  (required)
                     *    'states' order states  (required), options: submitted, partial_filled, partial_canceled, filled, canceled,
                     *                    can pick more than one, e.g. 'filled,canceled'
                     *    'before' the orders before page#
                     *    'after' the orders after page#
                     *    'limit' order per page, default is 20
                     *
                     *   @return json result
                     *    {
                     *        "status":0,
                     *        "data":[
                     *           {
                     *               "id":"string",
                     *               "symbol":"string",
                     *               "type":"limit",
                     *               "side":"buy",
                     *               "price":"string",
                     *               "amount":"string",
                     *               "state":"submitted",
                     *               "executed_value":"string",
                     *               "fill_fees":"string",
                     *               "filled_amount":"string",
                     *               "created_at":0,
                     *               "source":"web"
                     *           }
                     *       ]
                     *   }
                     *
                     *   @example    getOrderslist(['symbol' => 'etcusdt', 'states' => 'filled'])
                     */
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    }
                    break;
          }
          return $data;
     }

     // 获取指定订单
     public static function getOrder($platform = 'binance', $orderId = '')
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getOrder($orderId);
                    /**
                     *   Get orders
                     *   GET https://api.fcoin.com/v2/orders
                     *
                     *   @param string
                     *    specific order_id
                     *
                     *   @return json result
                     *    {
                     *       "status":0,
                     *       "data":{
                     *            "id":"9d17a03b852e48c0b3920c7412867623",
                     *           "symbol":"string",
                     *            "type":"limit",
                     *            "side":"buy",
                     *           "price":"string",
                     *            "amount":"string",
                     *            "state":"submitted",
                     *            "executed_value":"string",
                     *           "fill_fees":"string",
                     *           "filled_amount":"string",
                     *           "created_at":0,
                     *           "source":"web"
                     *       }
                     *    }
                     *
                     *   @example    getOrder(['order_id' => 'w3EJQPkRs-UhP0STjUlnm-atrGafdW3qtdVkNWohs3g='])
                     */
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    }
                    break;
          }
          return $data;
     }

     // 取消指定订单
     public static function cancelOrder($platform = 'binance', $orderId = '')
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::cancelOrder($orderId);
                    /**
                     *   Cancel order
                     *   POST https://api.fcoin.com/v2/orders/{order_id}/submit-cancel
                     *
                     *   @param Array $criteria,
                     *    'order_id' specific order_id
                     *
                     *   @return json result
                     *    {
                     *    "status": 0,
                     *    "msg": "string",
                     *    "data": true
                     *    }
                     *
                     *   @example createOrders(['order_id' => 'VPWyi9POZsJ-7G6GQt8dvDCvMI00HBg0H5-ms0JBTkg='])
                     */
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    }
                    break;
          }
          return $data;
     }

     // 获取指定订单状态
     public static function getOrderTransaction($platform = 'binance', $orderId = '')
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getOrderTransaction($orderId);
                    /**
                     *   Get order transaction
                     *   GET https://api.fcoin.com/v2/orders/{order_id}/match-results
                     *
                     *   @param string $orderId
                     *
                     *   @return json result
                     *    {
                     *       "status": 0,
                     *       "data": [
                     *        {
                     *           "price": "string",
                     *            "fill_fees": "string",
                     *            "filled_amount": "string",
                     *            "side": "buy",
                     *            "type": "limit",
                     *            "created_at": 0
                     *        }
                     *        ]
                     *    }
                     *
                     *   @example    getOrderTransaction(['order_id' => 'w3EJQPkRs-UhP0STjUlnm-atrGafdW3qtdVkNWohs3g='])
                     */
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    }
                    break;
          }
          return $data;
     }

     // 获取指定币种最新成交价
     public static function getLastPrice($platform = 'binance', $symbol = 'btcusdt')
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getTickData($symbol);
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']['ticker'][0]];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    }
                    break;
          }
          return $data;
     }

     // 获取深度明细
     public static function getMarketDepthStatus($platform = 'binance', $symbol = 'btcusdt', $level = 'full')
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getMarketDepthStatus(['symbol' => $symbol, 'level' => $level]);
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    }
                    break;
          }
          return $data;
     }

     // 获取最新的成交明细
     public static function getMarketTransaction($platform = 'binance', $symbol = 'btcusdt', $beforeId = '', $limit = 20)
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getMarketTransaction($symbol, $beforeId = '', $limit = 20);
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    }
                    break;
          }
          return $data;
     }

     // 获取K线   resolution: M1 M3 M5 M15 M30 H1 H4 H6 D1 W1 MN
     public static function getCandles($platform = 'binance', $symbol = 'btcusdt', $resolution = 'M5', $beforeId = '', $limit = 20)
     {
          $data = array();
          switch ($platform) {
               case 'binance' : ;
                    break;
               case 'fcoin' :
                    $data = app('Fcoin')::getCandles($symbol, $resolution, $beforeId = '', $limit = 20);
                    $data = json_decode($data, true);
                    if ($data['status'] == 0) {
                         $data = [0, $data['data']];
                    } else {
                         $data = [$data['status'], $data['msg']];
                    }
                    break;
          }
          return $data;
     }


     /* ------------------------ 实际应用 ----------------------------*/

     // 标记价格连续涨跌多少次
     public static function setMarkPriceChange($platform = 'fcoin', $symbol = 'btcusdt', $level = 'M1')
     {
          list($status, $candles) = self::getCandles($platform, $symbol, $level);
          $mark = 0;
          unset($candles[0]);
          if ($candles[1] == $candles[2]) {
               $mark = 0;
          } elseif ($candles[1] > $candles[2]) {  // 涨
               foreach ($candles as $k => $candle) {
                    $k2 = $k + 1;
                    if (isset($candles[$k2])) {
                         if ($candles[$k] > $candles[$k2]) {
                              $mark++;
                         } else {
                              break;
                         }
                    }
               }
          } else {
               foreach ($candles as $k => $candle) {
                    $k2 = $k + 1;
                    if (isset($candles[$k2])) {
                         if ($candles[$k] < $candles[$k2]) {
                              $mark--;
                         } else {
                              break;
                         }
                    }
               }
          }
          $key = self::markPriceChangeKey($platform = 'fcoin', $symbol = 'btcusdt', $level = 'M1');
          Redis::set($key, $mark);
     }

     // 标记价格连续涨跌多少次的key 0不变 -1连续跌1次 1连续涨1次 2连续涨2次 依次类推
     public static function markPriceChangeKey($platform = 'fcoin', $symbol = 'btcusdt', $level = 'M1')
     {
          return 'mark_price_change_'.$platform.'_'.$symbol.'_'.$level;
     }

     public static function getMarkPriceChange($platform = 'fcoin', $symbol = 'btcusdt', $level = 'M1')
     {
         $value = Redis::get(self::markPriceChangeKey($platform, $symbol, $level));
         if (is_null($value)) {
              $res = 0;
         } else {
              $res = $value;
         }
         return $res;
     }
}