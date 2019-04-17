<?php


namespace larryli\yii\pushbear;

/**
 * Class PushBearException
 */
class Exception extends \yii\base\Exception
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'PushBear Exception';
    }
}
