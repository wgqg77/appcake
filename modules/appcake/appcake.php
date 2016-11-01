<?php

namespace app\modules\appcake;

/**
 * appcake module definition class
 */
class appcake extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\appcake\controllers';

    public $layout = '@app/modules/admin/views/layouts/main';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
