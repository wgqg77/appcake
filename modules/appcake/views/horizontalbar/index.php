<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\Horizontalbar */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '首页水平滚动栏';
$this->params['breadcrumbs'][] = $this->title;

$country = Yii::$app->params['country_list_cn'];
foreach($country as $k => $v){
    $country[$k] = $k .'_'.$v;
}


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
<style>
    .btn-rank{
        display: inle-block;
        border: 1px solid #ddd;
        cursor: pointer;
        border-radius: 5px;
        text-align: center;
        padding: 5px;
        margin: 0px;
        background: #429842;
        color: #fff;
    }
</style>
<div class="horizontalbar-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <p>
        <?= Html::a('添加(top/app/games)', ['create-ho'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('添加(混合)', ['create-ho2'], ['class' => 'btn btn-success']) ?>

        <?= Html::a('应用排序修改', ['update-app','category'=>'APP'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('游戏排序修改', ['update-app','category'=>'Games'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'country',
            [
                'attribute' => 'country',
                'value' => function($model){
                    return $model->country;
                },
                'filter' => $country
            ],
            //'category',
            [
                'attribute' => 'category',
                'value' => function($model){
                    return $model->category;
                },
                'filter' => array('Games'=>'Games','APP'=>'App','other'=>'混合')
            ],
            'app_id',
            'special_id',
             'img',
             'appstore',
             'rank',
             //'time:datetime',

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
                        $str = '';


                        $rankNum = $model->rank;
                        $rank = array(1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'',10=>'',11=>'',12=>'',13=>'',14=>'',15=>'');
                        $rank[$rankNum] = "selected = selected";
                        $str = "<select name=\"rank\" class=\"". $model->special_id."\">
                                    <option value=\"1\" ".$rank[1] . ">1</option>
                                    <option value=\"2\" ".$rank[2] . ">2</option>
                                    <option value=\"3\" ".$rank[3] . ">3</option>
                                    <option value=\"4\" ".$rank[4]. ">4</option>
                                    <option value=\"5\" ".$rank[5]. ">5</option>
                                    <option value=\"6\" ".$rank[6] . ">6</option>
                                    <option value=\"7\" ".$rank[7] . ">7</option>
                                    <option value=\"8\" ".$rank[8]. ">8</option>
                                    <option value=\"9\" ".$rank[9]. ">9</option>
                                    <option value=\"10\" ".$rank[10]. ">10</option>
                                    <option value=\"11\" ".$rank[11]. ">11</option>
                                    <option value=\"12\" ".$rank[12]. ">12</option>
                                    <option value=\"13\" ".$rank[13]. ">13</option>
                                    <option value=\"14\" ".$rank[14]. ">14</option>
                                    <option value=\"15\" ".$rank[15]. ">15</option>
                                </select>";
                        $str .= '<a href="javascript:;" class="btn-rank" data-url="'. $url .'" title="Update Rank" aria-label="rank" data-id="'.$model->special_id.'" data-pjax="0">Rank</a>';

                        return $str;
                    },
                ]
            ],
        ],
    ]); ?>
</div>
