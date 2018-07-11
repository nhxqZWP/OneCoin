<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cryptocurrencies Trade Test</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #000000;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-center {
            position: relative;
            margin: 0 auto;
        }

        .content {
            text-align: center;
        }

        .title {
            /*font-size: 84px;*/
            font-size: 14px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="text-center">
    {{--<div class="top-center links">--}}
        {{--钱包余额: {{$xbt}} XBT&nbsp;&nbsp; --}}{{--USDT: {{$usdt}}--}}
    {{--</div>--}}

    <div class="content">
        <div class="top-center links">
            <br><br>
            钱包余额: {{$xbt}} XBT&nbsp;&nbsp; {{--USDT: {{$usdt}}--}}
        </div>
        <div class="" style="border:1px solid;">
            <table class="table table-striped">
                <tr>
                    <td></td>
                    <td>已奖励</td>
                    <td>待发</td>
                </tr>
                <tr>
                    <td>总交易额</td>
                    <td>{{$affi['prevTurnover']/pow(10,8)}} XBT</td>
                    <td>{{$affi['execTurnover'] / pow(10, 8)}} XBT</td>
                </tr>
                <tr>
                    <td>已支付佣金</td>
                    <td>{{$affi['prevComm']/pow(10,8)}} XBT</td>
                    <td>{{$affi['execComm'] / pow(10, 8)}} XBT</td>
                </tr>
                <tr>
                    <td>推荐等级(佣金百分比)</td>
                    <td></td>
                    <td>{{$affi['payoutPcnt']*100}} %</td>
                </tr>
                <tr>
                    <td>推荐人奖励</td>
                    <td>{{$affi['prevPayout']/pow(10,8)}} XBT</td>
                    <td>{{$affi['pendingPayout'] / pow(10, 8)}} XBT</td>
                </tr>
            </table>
        </div>
        <br>
        <div class="title m-b-md" style="overflow:scroll; border:1px solid;">
            <table class="table table-striped">
                <tr>
                    <td>时间</td>
                    <td>类型</td>
                    <td>金额</td>
                    <td>状态</td>
                    <td>钱包余额</td>
                </tr>
                @if (!empty($list))
                    @foreach($list as $item)
                        <tr>
                            <td>{{$item['transactTime']}}</td>
                            <td>{{$item['transactType']}}</td>
                            <td>{{$item['amount'] / pow(10, 8)}}{{$item['currency']}}</td>
                            <td>{{$item['transactStatus']}}</td>
                            <td>{{$item['walletBalance'] / pow(10, 8)}}{{$item['currency']}}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>

        <div class="links" style="">
            {{--<a href="https://www.binance.com/tradeDetail.html?symbol=BTC_USDT">Binance BTCUSDT</a>--}}
            钱包记录
        </div>
    </div>
</div>
</body>
</html>
