<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\Required */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Requireds';
$this->params['breadcrumbs'][] = $this->title;
$category = Yii::$app->params['banner_category'];
$compatible = Yii::$app->params['banner_compatible'];
$compatible[''] = '全部';
$category[''] = '全部';
ksort($category);
ksort($compatible);


$this->registerJs('

        $(\'.btn-rank\').click(function(){
            var baseUrl = $(this).attr(\'data-url\');
            var id = $(this).attr(\'data-id\');
            var rank = $(\'.\'+ id).find("option:selected").val();
            var url = baseUrl + "&rank=" + rank;
            //$(this).attr(\'href\',url);
            location.href=url;
        })

')


?>
<div class="required-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'app_id',
            'app_name',
            'description',

            //'category',
            [
                'attribute' => 'category',
                'value' => function($mode){
                    return $mode->category;
                },
                'filter' => $category,
                'filterType'=>GridView::FILTER_SELECT2,
            ],
            // 'compatible',
            [
                'attribute' => 'compatible',
                'value' => function($mode){
                    return $mode->compatible;
                },
                'filter' => $compatible,
                'filterType'=>GridView::FILTER_SELECT2,
            ],
            // 'frank',
            [
                'attribute' => 'frank',
                'value' => function($model){
                    if($model->frank == 0){
                        return '[非售卖]';
                    }else{
                        return '[售卖]'. $model->frank;
                    }
                },
                'filter' => array(0=>'非售卖',1=>'售卖')

            ],
            'rank',
             'size',
             'price',

            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'app\modules\appcake\components\ActionColumn',
                'template' => '{rank:view} {update:update} {delete:delete}',
                'buttons' => [
                    // 自定义按钮
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update Rank'),
                            'aria-label' => Yii::t('yii', 'Update Rank'),
                            'data-pjax' => '0',
                        ];
                        $rank = $model->frank;
                        $selected = "selected = selected";
                        $str = "<select name=\"rank\" class=\"". $model->app_id."\">";
                                $index = 0;
                                for($i= 30;$i>=0;$i--){
                                    $index ++ ;
                                    if($i == 0) $index = '取消';
                                    if($i == $rank){
                                        $str .= "<option value=\"{$i}\" ".$selected.">$index</option>";
                                    }else{
                                        $str .= "<option value=\"{$i}\">$index</option>";
                                    }
                                }
								 $str .=    "</select>";


                        return $str . '<a href="javascript:;" class="btn-rank" data-url="'. $url .'" title="Update Rank" aria-label="rank" data-id="'.$model->app_id.'" data-pjax="0"><span class="glyphicon glyphicon-check"></span></a>'; //Html::a('Update Rank', $url, $options);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
