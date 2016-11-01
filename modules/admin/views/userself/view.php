<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\admin\models\AuthGroup;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Admin */

$this->title = '个人信息';
$this->params['breadcrumbs'][] = ['label' => '个人中心', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$group = AuthGroup::find()->select(array('title'))->where("id = {$model->group}")->asArray()->one();
?>
<div class="admin-view">



    <p>
        <?= Html::a('Update', ['update', 'id' => $model->uid], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'uid',
            'user_name',
            //'passwd',
            //'sex',
            //'email:email',
            //'phone',
            'create_time:datetime',
            'create_ip',
            'login_time:datetime',
            'login_ip',
            //'safe_question',
            //'safe_answer',
            //'face',
            //'scores',
            [
                'label' => 'group',
                'value' => $group['title']
            ],
            //'coin',
            'is_lock',
            //'is_admin',
            //'nick_name',
            //'collect',
            'login_count',
        ],
    ]) ?>

</div>
