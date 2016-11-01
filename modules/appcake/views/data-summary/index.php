<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\DataSummary */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '数据汇总统计查询';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="data-summary-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <div class="form-inline">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <div class="summary" style="float: left; margin-left: 20px;height: 30px;line-height: 50px;color: #fff;">
        点击:<b><?php echo $total['click'];?></b>
        下载:<b><?php echo $total['download'];?></b>
        安装:<b><?php echo $total['install'];?></b>
        cake激活:<b><?php echo $total['cActive'];?></b>

        appSotre点击量:<b><?php echo $total['hClick'];?></b>
        appSotre激活量:<b><?php echo $total['hActive'];?></b>

        分配量:<b><?php echo $total['aClick'];?></b>

        总激活:<b><?php echo $total['TA'];?></b>

        收入:<b><?php echo $total['income'];?></b>
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

            //'id',
            'date',
            'app_id',
            'camp_id',
            'name',
            //'ad_source',
            [
                'attribute' => 'ad_source',
                'value' => function($model) {
                    return  isset(Yii::$app->params['ad_source'][$model->ad_source]) ? Yii::$app->params['ad_source'][$model->ad_source] : $model->ad_source;
                }
            ],
             //'country',
            [
                'attribute' => 'country',
                'value' => function($model) {
                    return  isset(Yii::$app->params['country_list_cn'][$model->country]) ? $model->country .'-' . Yii::$app->params['country_list_cn'][$model->country]   : $model->country;
                }
            ],
             'click',
             'download',
             'install',
             'cake_active',
             'CP',
             'h_click',
             'h_active',
             'AP',
             'analog_click',
             //'acquisition_flow',
             'TA',
             'TP',
            'payout_amount',
             'income',
             //'payout_currency',
            'category',
            'countries',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
