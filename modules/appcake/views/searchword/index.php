<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\Searchword */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '搜索词管理';
$this->params['breadcrumbs'][] = $this->title;

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
<div class="searchword-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Searchword', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'number',
            'addtime',
//            [
//                'attribute' => 'addtime',
//                'value' => function($model){
//                    $rank = substr($model->addtime,0,1);
//                    return $rank;
//                }
//            ],
            //'category',
            [
                'attribute' => 'category',
                'value' => function($model){
                    return $model->category;
                },
                'filter' => Yii::$app->params['banner_category'],
            ],
            // 'compatible',
            [
                'attribute' => 'compatible',
                'value' => function($model){
                    return $model->compatible;
                },
                'filter' => Yii::$app->params['banner_compatible'],
            ],

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
                        $rankNum = substr($model->addtime,0,1);
                        $rank = array(0=>'',1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'',8=>'',9=>'');
                        $rank[$rankNum] = "selected = selected";
                        $str = "<select name=\"rank\" class=\"". $model->id."\">
								    	<option value=\"9\" ".$rank[9] . ">置顶</option>
								    	<option value=\"8\" ".$rank[8] . ">2</option>
								    	<option value=\"7\" ".$rank[7] . ">3</option>
								    	<option value=\"6\" ".$rank[6]. ">4</option>
								    	<option value=\"5\" ".$rank[5]. ">5</option>
								    	<option value=\"4\" ".$rank[4] . ">6</option>
								    	<option value=\"3\" ".$rank[3] . ">7</option>
								    	<option value=\"2\" ".$rank[2]. ">8</option>
								    	<option value=\"1\" ".$rank[1]. ">9</option>
								    </select>";


                        return $str . '<a href="javascript:;" class="btn-rank" data-url="'. $url .'" title="Update Rank" aria-label="rank" data-id="'.$model->id.'" data-pjax="0">Rank</a>'; //Html::a('Update Rank', $url, $options);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
