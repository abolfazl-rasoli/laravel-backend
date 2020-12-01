<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <style>

        .container{
            display: flex;
            justify-content: center;
            align-content: center;
            height: 100%;
        }

        .card{
            width: 70%;
            background: #eaeaea;
            padding: 10px;
            direction: rtl;
        }
        .content{
            margin: 10px 0 5px 0;
            font-size: 12px;
            line-height: 20px;
        }

        .token {
            text-align: center;
            width: 100%;
            margin: 20px 0;
        }

        .token-box{
            box-shadow: 0 0 2px 2px #718096;
            background: #eaeaea;
            color: #6b6b6b;
            padding: 5px;
            font-family: monospace;
        }

    </style>
    <div class="container">
        <div class="card">
            <div>
                <h4>{{ trans('messages.hello') }}</h4>
            </div>
            <div class="content">
                {{ trans('messages.activeCode') }} . {{ trans('messages.hasTroubleClick') }}
            </div>
            <div class="token">
                <span class="token-box">
                    {{$token}}
                </span>
            </div>
            <div>
                {{ env('APP_NAME') }}
            </div>
        </div>
    </div>
</body>
</html>
