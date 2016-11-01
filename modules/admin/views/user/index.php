<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\Admin */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">




    <p>
        <?= Html::a('添加新用户', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php

    $columns =[
        [
            'attribute'=>'uid',
        ],
        [
            'attribute'=>'user_name',
        ],
        [
            'attribute'=>'login_time',
            'value'=>function($model){
                return date("Y-m-d H:i:s",$model->login_time);
            },
        ],
        [
            'attribute'=>'create_time',
            'value'=>function($model){
                return date("Y-m-d H:i:s",$model->create_time);
            },
        ],
        [
            'attribute'=>'login_ip',
        ],
        [
            'attribute'=>'is_lock',
            'value' => function($model){
                if($model->is_lock == 0){
                    return '正常';
                }else{
                    return '已锁定';
                }
            },
        ],
        [
            'attribute'=>'login_count',
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ];

    echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel' => $searchModel,
    'columns'=>$columns,



    ]);

    ?>
</div>
