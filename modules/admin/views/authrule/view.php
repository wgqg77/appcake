<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\admin\models\AuthRule;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AuthRule */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$status = array(
    0 => '显示',
    1 => '隐藏'
);
$father = AuthRule::find()->select(array('id','name','title','pid'))->where("id={$model->pid}")->asArray()->one();

?>
<div class="auth-rule-view">


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
            'name',
            'title',
            [
                'label' => '状态',
                'value' => $status[$model->status]
            ],
            [
                'label' => '父级',
                'value' => $father['title'] ."|". $father['name']
            ],
            'sort',
            'create_time:datetime',
        ],
    ]) ?>

</div>