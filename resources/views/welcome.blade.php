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
                color: #636b6f;
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

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
                border: 1px solid red;
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
        <div class="flex-center position-ref full-height">
                <div class="top-right links">
                    BTC: {{$btc}} USDT: {{$usdt}}
                </div>

            <div class="content">
                <div class="title m-b-md">
                    <table class="table table-striped">
                        <tr>
                            <td>ID</td>
                            <td>Price</td>
                            <td>Amount</td>
                            <td>Type</td>
                            <td>Profit</td>
                            <td>Time</td>
                        </tr>
                        @if (!empty($list))
                            @foreach($list as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->price / pow(10, 8)}}</td>
                                    <td>{{$item->amount / pow(10, 8)}}</td>
                                    <td>{{$item->type == 0 ? 'buy' : 'sell'}}</td>
                                    <td>{{$item->profit / pow(10,6)}}%</td>
                                    <td>{{$item->created_at}}</td>
                                </tr>
                                @endforeach
                            @endif
                    </table>
                </div>

                <div class="links" style="width: 800px">
                    <a href="https://www.binance.com/tradeDetail.html?symbol=BTC_USDT">Binance BTCUSDT</a>
                </div>
            </div>
        </div>
    </body>
</html>
