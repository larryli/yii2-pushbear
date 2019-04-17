<?php

namespace larryli\yii\pushbear;

use yii\helpers\StringHelper;
use yii\di\Instance;

/**
 * PushBear Target
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
        $message = reset($this->messages);
        $text = StringHelper::truncate($message, 80);
        $messages = array_map([$this, 'formatMessage'], $this->messages);
        $body = wordwrap(implode("\n\n", $messages), 70);
        $this->pushBear->subOrFail($text, $body);
    }
}
