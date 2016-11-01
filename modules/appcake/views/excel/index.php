<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\GridView;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\DownloadWeek */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文件管理';
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = dirname(Url::base());
?>


<div class="wrap">


    <div class="container">
        <div class="bt-app-version-index">

            <h1>Bt App Versions</h1>


            <div id="w0" class="grid-view"><div class="summary">共<b><?php echo count($file);?></b>条数据.</div>
                <table class="table table-striped table-bordered"><thead>
                    <tr><th>#</th>
                        <th>名称</th>
                        <th>大小</th>
                        <th>时间</th>
                        <th class="action-column">&nbsp;</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach($file as $k => $v){ ?>
                    <tr data-key="30">
                        <td><?php echo $k+1; ?></td>
                        <td><a href="<?php echo $baseUrl.'/Excel/'.$v['name']; ?>"><?php echo $v['name']; ?></a></td>
                        <td><?php echo $v['size']; ?></td>
                        <td><?php echo $v['time']; ?></td>

                        <td>
                            </a><a href="<?php echo Url::to(['/appcake/excel/download','filename'=>"{$v['name']}"]) ;?>"><span class="glyphicon glyphicon-download-alt"></span></a>
                            <a href="<?php echo Url::to(['/appcake/excel/del','filename'=>"{$v['name']}"]) ;?>"><span class="glyphicon glyphicon-trash"></span></a>
                        </td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div></div>
    </div>
</div>