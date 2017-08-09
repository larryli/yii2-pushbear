# yii2-pushbear

PushBear Yii2 组件，使用 yii2-httpclient 包装 API。并提供 PushBearTarget 日志处理。

## 创建消息通道

访问 https://pushbear.ftqq.com/admin/ 使用微信扫码登录后，创建消息通道，获取指定通道的 SendKey。

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
