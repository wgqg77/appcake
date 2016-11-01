<?php

namespace app\modules\bt;

/**
 * bt module definition class
 */
class bt extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\bt\controllers';

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
