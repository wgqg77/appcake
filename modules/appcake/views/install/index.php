<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\DownloadInstall */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '安装top';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="download-install-index">



    <div class="form-inline">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <?= GridView::widget([
        //导出开始
        'pjax'=>true, // pjax is set to always true for this demo
        // set your toolbar
        'toolbar'=> [

            '{export}',
            '{toggleData}',
        ],
        'export'=>[
            'fontAwesome'=>true
        ],
        'toggleDataOptions'=>[
            'maxCount'=>100000
        ],
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
        ],
        'persistResize'=>false,
        //导出结束
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'app_id',
            'camp_id',
            'app_name',
            //'position',
            //'idfa',
            //'time:datetime',
            //'ip',
            //'device',
            //'country',

            'count',
            //'category'
            [
                'attribute' => 'country',
                'value' => function($model) {
                    return  isset(Yii::$app->params['country_list_cn'][$model->country]) ? $model->country .'-' . Yii::$app->params['country_list_cn'][$model->country]   : $model->country;
                }
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
