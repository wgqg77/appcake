<?php

namespace app\modules\appcakethree;

/**
 * appcakethree module definition class
 */
class appcakethree extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\appcakethree\controllers';

    public $defaultRoute = 'index';

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
