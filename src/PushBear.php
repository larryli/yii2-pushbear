<?php

namespace larryli\yii\pushbear;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;

/**
 * PushBear Component
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
     * @var Client Http Client
     */
    private $_httpClient;

    /**
     * Init
     *
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty($this->sendKey)) {
            throw new InvalidConfigException('The PushBear::sendKey must be set.');
        }
    }

    /**
     * Sub message
     *
     * @param string $text
     * @param string $description
     * @return array
     * @throws InvalidConfigException
     * @throws Exception
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

        try {
            Yii::debug("Sub message data: " . var_export($data), __METHOD__);
            $response = $this->getHttpClient()->post('sub', $data)->send();
            Yii::debug("Sub message return: {$response->content}", __METHOD__);
            if (!$response->isOk) {
                if (isset($response->data['code']) && $response->data['message']) {
                    Yii::error("Sub message failed: #{$response->data['code']}, {$response->data['message']}", __METHOD__);
                    throw new Exception($response->data['message'], $response->data['code']);
                } else {
                    Yii::error("Sub message failed: {$response->content}", __METHOD__);
                    throw new Exception($response->content);
                }
            }
            // {code: 0, message: "", data: "1条消息已成功推送到发送队列", created: "2017-08-09 14:50:34"}
            return $response->data;
        } catch (\yii\httpclient\Exception $e) {
            Yii::error("sub message failed: #{$e->getCode()}, {$e->getMessage()}", __METHOD__);
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Sub message or fail
     *
     * @param $text
     * @param string $description
     * @return array|false
     * @throws InvalidConfigException
     */
    public function subOrFail($text, $description = '')
    {
        try {
            return $this->sub($text, $description);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get http client
     *
     * @return Client
     * @throws InvalidConfigException
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
