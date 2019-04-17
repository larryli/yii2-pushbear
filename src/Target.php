<?php

namespace larryli\yii\pushbear;

use yii\helpers\StringHelper;
use yii\di\Instance;

/**
 * PushBearTarget 日志处理
 */
class Target extends \yii\log\Target
{
    /**
     * @var string | PushBear
     */
    public $pushBear = 'pushBear';

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->pushBear = Instance::ensure($this->pushBear, PushBear::class);
    }

    /**
     * Sends log messages
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function export()
    {
        list($text, $level, $category, $timestamp) = reset($this->messages);
        $text = StringHelper::truncate($text, 80);
        $messages = array_map([$this, 'formatMessage'], $this->messages);
        $body = wordwrap(implode("\n\n", $messages), 70);
        $this->pushBear->subOrFail($text, $body);
    }
}
