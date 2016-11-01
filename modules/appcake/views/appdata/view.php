<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AppData */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'App Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-data-view">

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
            'app_id',
            'app_name',
            'version',
            'category',
            'vendor',
            'release_date',
            'add_date',
            'size',
            'icon',
            'screenshot',
            'screenshot2',
            'screenshot3',
            'screenshot4',
            'screenshot5',
            'ipadscreen1',
            'ipadscreen2',
            'ipadscreen3',
            'ipadscreen4',
            'ipadscreen5',
            'support_watch',
            'watch_icon',
            'watch_screen1',
            'watch_screen2',
            'watch_screen3',
            'watch_screen4',
            'watch_screen5',
            'requirements',
            'whatsnew:ntext',
            'description:ntext',
            'download',
            'week_download',
            'price',
            'compatible',
            'need_backup',
            'youtube_vid',
            'v_poster',
            'v_approved',
            'bundle_id',
            'stars',
            'genres',
            'min_os_version',
            'ipa',
            's3_key',
            'bt_url:url',
            'app_store_version',
        ],
    ]) ?>

</div>