<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\admin\models\AuthRule;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AuthGroup */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$fatherModel = new AuthRule();
$father = $fatherModel->getTreeRules();
$ruels = array_column($father,'title','id');

$thisRules = explode(',',$model->rules);

$status = Yii::$app->params['groupStatus'];
$str = '';
foreach($thisRules as $k => $v){
    if(isset($ruels[$v])){
        $str .=  $ruels[$v] .' / ' ;
    }
}

?>
<div class="auth-group-view">



    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'label' => '状态',
                'value' => $status[$model->status]
            ],
            [
                'label' => '权限',
                'value' => $str
            ],
        ],
    ]) ?>

</div>