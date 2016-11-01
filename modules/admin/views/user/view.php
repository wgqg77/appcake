<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\admin\models\AuthGroup;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Admin */

$this->title = $model->uid;
$this->params['breadcrumbs'][] = ['label' => 'Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$group = AuthGroup::find()->select(array('title'))->where("id = {$model->group}")->asArray()->one();

?>
<div class="admin-view">



    <p>
        <?= Html::a('Update', ['update', 'id' => $model->uid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->uid], [
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
            'uid',
            'user_name',
//            'email:email',
//            'phone',
            'create_time:datetime',
            'create_ip',
            'login_time:datetime',
            'login_ip',
            [                      // the owner name of the model
             'label' => 'group',
             'value' => $group['title']
            ],
            'is_lock',
            'login_count',
        ],
    ]) ?>

</div>
