<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\BatAll */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bat Alls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bat-all-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'camp_id' => $model->camp_id, 'source' => $model->source], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'camp_id' => $model->camp_id, 'source' => $model->source], [
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
            'camp_id',
            'origin_camp_id',
            'source',
            'creatives',
            'imp_url:url',
            'click_url:url',
            'click_callback_url:url',
            'mobile_app_id',
            'payout_amount',
            'payout_currency',
            'acquisition_flow',
            'icon_gp',
            'description',
            'name',
            'rate',
            'store_rating',
            'installs',
            'category',
            'dl_type',
            'preload',
            'start_time:datetime',
            'end_time:datetime',
            'nocountries',
            'countries',
        ],
    ]) ?>

</div>