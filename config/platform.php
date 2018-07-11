<?php

return [

    'binance' => [
        'key' => env('BINANCE_KEY', ''),
        'secret' => env('BINANCE_SECRET', '')
    ],

    'gate_io' => [
        'key' => env('GATE_KEY', ''),
        'secret' => env('GATE_SECRET', '')
    ],

     'fcoin' => [
          'MODE' => 'REAL', //SANDBOX OR REAL
          'key' => env('FCOIN_KEY', ''),
          'secret' => env('FCOIN_SECRET', ''),
          'API_URI' => 'https://api.fcoin.com/v2/',
          'SANDBOX' => 'https://api-sandbox.fcoin.com/v2/'
     ],

     'bitmex' => [
          'key' => env('BITMEX_KEY', ''),
          'secret' => env('BITMEX_SECRET', '')
     ],

];
