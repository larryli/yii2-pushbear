# yii2-pushbear

PushBear Yii2 组件，使用 yii2-httpclient 包装 API。并提供 PushBearTarget 日志处理。

## 创建消息通道

访问 https://pushbear.ftqq.com/admin/ 使用微信扫码登录后，创建消息通道，获取指定通道的 SendKey。

## 安装

通过 [composer](http://getcomposer.org/download/) 安装。

执行命令：

    php composer.phar require --prefer-dist larryli/yii2-pushbear

或者增加：

    "larryli/yii2-pushbear": "dev-master"

## Yii 组件配置

```php
'components' => [
    'pushBear' => [
        'class' => \larryli\yii\pushbear\PushBear::class,
        'sendKey' => 'your-SendKey',
    ],
],
```

## 使用

```php
Yii::$app->pushBear->sub('标题', "# 内容标题\n\n- 列表 1\n- 列表 2\n\n[链接](https://github.com/larryli/yii2-pushbear)");
```

## 日志处理

```php
'components' => [
    'log' => [
         'targets' => [
            [
                'class' => \larryli\yii\pushbear\PushBearTarget::class,
                'pushBear' => 'pushBear',
                'levels' => ['error', 'warning'],
            ],
       ],
   ],
],
```

## Yii2 HttpClient 配置

使用 [cURL](http://php.net/manual/en/book.curl.php) 库发送 HTTP 消息，此传输需要安装 PHP 'curl' 扩展。

```php
'components' => [
    'pushBear' => [
        'class' => \larryli\yii\pushbear\PushBear::class,
        'sendKey' => 'your-SendKey',
        'transport' => \yii\httpclient\CurlTransport::class,
    ],
],
```

保存 Http 请求日志到指定日志文件。

```php
'components' => [
    'log' => [
        'targets' => [
            [
                'class' => \yii\log\FileTarget::class,
                'logFile' => '@runtime/logs/http-request.log',
                'categories' => ['yii\httpclient\*'],
            ],
        ],
    ],
],
```

使用调试面板查看 Http 请求数据。

```php
'bootstrap' => ['debug'],
'modules' => [
    'debug' => [
        'class' => \yii\debug\Module::class,
        'panels' => [
            'httpclient' => [
                'class' => \yii\httpclient\debug\HttpClientPanel::class,
            ],
        ],
    ],
],
```
