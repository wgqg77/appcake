<?php

namespace app\modules\bt\controllers;

use yii\web\Controller;

/**
 * Default controller for the `bt` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
