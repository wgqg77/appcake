<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\DownloadWeek */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '点击top';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="download-week-index">



    <div class="form-inline">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>





    



    <div class="clear" style="clear:both;"></div>
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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'app_id',
            'app_name',
            //'position',
            //'idfa',
            //'time:datetime',
             //'ip',
             //'device',
             //'country',
             'camp_id',
             'count',
            [
                'attribute' => 'country',
                'value' => function($model) {
                    return  isset(Yii::$app->params['country_list_cn'][$model->country]) ? $model->country .'-' . Yii::$app->params['country_list_cn'][$model->country]   : $model->country;
                },

            ],
             'category'


            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
