<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\bt\models\BtAppVersion */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bt App Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$updaStatus = Yii::$app->params['mustUpdate'];
$appFileType = Yii::$app->params['appFileType'];
?>
<div class="bt-app-version-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'version',
            'download_url:url',
            'download_url2:url',
            'descript:ntext',
            'update_descript:ntext',
            'md5',
            [
                'label' => '强制更新',
                'value' => $updaStatus[$model->update_status]
            ],
            'create_time:datetime',
            'update_time:datetime',
            [
                'label' => '包类型',
                'value' => $appFileType[$model->file_type]
            ],
        ],
    ]) ?>

</div>
