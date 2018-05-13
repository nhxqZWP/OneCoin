<?php

namespace App\Http\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Exception;

class Binance
{
    protected $base = "https://api.binance.com/api/", $wapi = "https://api.binance.com/wapi/", $api_key, $api_secret;
    protected $depthCache = [];
    protected $depthQueue = [];
    protected $chartQueue = [];
    protected $charts = [];
    protected $info = ["timeOffset"=>0];
    public $balances = [];
    public $btc_value = 0.00; // value of available assets
    public $btc_total = 0.00; // value of available + onOrder assets

    public function __construct($api_key = '', $api_secret = '', $options = ["useServerTime"=>false]) {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        if ( isset($options['useServerTime']) && $options['useServerTime'] ) {
            $this->useServerTime();
        }
    }

    public function useServerTime() {
        $serverTime = $this->apiRequest("v1/time")['serverTime'];
        $this->info['timeOffset'] = $serverTime - (microtime(true)*1000);
    }

    private function apiRequest($url, $method = "GET") {
        if ( empty($this->api_key) ) die("apiRequest error: API Key not set!");
//        try {
            $headers = ['User-Agent' => 'Mozilla/4.0 (compatible; PHP Binance API)', 'X-MBX-APIKEY' => $this->api_key];
            $sendData = [
                'headers' => $headers,
            ];
            $client = new Client();
            $request = new Request($method, $this->base.$url, $sendData);
            $response = $client->send($request, ['timeout' => 5]);
            $data = $response->getBody();
            dd($data);
//        } catch ( Exception $e ) {
//            return ["error"=>$e->getMessage()];
//        }
//        return json_decode($data, true);
    }

    public function time() {
        return $this->apiRequest("v1/time");
    }

}