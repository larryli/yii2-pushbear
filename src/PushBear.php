<?php

namespace larryli\yii\pushbear;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;

/**
 * PushBear 组件
 */
class PushBear extends Component
{
    /**
     * @var string Base Url
     */
    public $baseUrl = 'https://pushbear.ftqq.com/';
    /**
     * @var string Send Key
     */
    public $sendKey;
    /**
     * @var string Http Client Transport
     * @see https://github.com/yiisoft/yii2-httpclient/blob/master/docs/guide/usage-transports.md
     */
    public $transport;
    /**
     * @var Client
     */
    private $_httpClient;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->sendkey)) {
            throw new InvalidConfigException('The PushBear::sendKey must be set.');
        }
    }

    /**
     * 发送系统消息
     *
     * @param string $text
     * @param string $description
     * @return array
     * @throws \Exception
     */
    public function sub($text, $description = '')
    {
        $data = [
            'sendkey' => $this->sendKey,
            'text' => $text,
        ];
        if (!empty($description)) {
            $data['desp'] = $description;
        }

        $response = $this->getHttpClient()->post('sub', $data)->send();
        if (!$response->isOk) {
            throw new Exception('Unable to sub message.');
        }
        // {code: 0, message: "", data: "1条消息已成功推送到发送队列", created: "2017-08-09 14:50:34"}
        return $response->data;
    }

    /**
     * @return Client
     */
    protected function getHttpClient()
    {
        if (!is_object($this->_httpClient)) {
            $config = [
                'class' => Client::class,
                'baseUrl' => $this->baseUrl,
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON,
                ],
            ];
            if (!empty($this->transport)) {
                $config['transport'] = $this->transport;
            }
            /** @var Client _httpClient */
            $this->_httpClient = Yii::createObject($config);
        }
        return $this->_httpClient;
    }
}
