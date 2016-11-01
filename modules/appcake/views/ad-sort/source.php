<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\AdSortOne */
/* @var $dataProvider yii\data\ActiveDataProvider */




$this->title = '编辑渠道';
$this->params['breadcrumbs'][] = ['label' => '广告排序管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    $('#save-source').click(function(){
       var sourceObj = $('input[name=\"source\"]:checked');
       var source = sourceObj.val();
       var camp_id = sourceObj.attr('data-camp_id');
       var app_id = sourceObj.attr('data-app_id');
       var country = sourceObj.attr('data-country');
       var position = sourceObj.attr('data-position');
       var id = sourceObj.attr('data-id');
       if(source == undefined || camp_id == undefined || app_id == undefined || country == undefined || position == undefined || id == undefined ){
            window.alertError('参数不能为空');
            return false;
       }

       var data = 'country='+country +'&position='+position+'&camp_id='+camp_id+'&app_id='+app_id+'&source='+source + '&id=' + id ;
       var url = window.location.href;

        ajaxPost(url,data,function(data){
            data = JSON.parse(data);
            if(data.code == 10000){
                window.alertSuccess('修改成功');
            }else if(data.code == 10002){
                window.alertError('编辑失败,内容未发生改变');
            }else if(data.code == 10003){
                window.alertError('参数错误');
            }

        });
    });
")


?>
<div class="page-container">

    <div class="cl pd-5 bg-1 bk-gray mt-20">


        <span class="r">共有数据：<strong><?php echo count($ad);?></strong> 条</span>
        <div type="button" class="btn btn-success"  name="" id="save-source"><i class="Hui-iconfont">&#xe615;</i> 保存</div> </div>
    <table class="table table-border table-bordered table-bg">
        <thead>

        <tr class="text-c">

            <th width="40">ID</th>
            <th width="150">名称</th>
            <th width="150">camp_id</th>
            <th width="100">国家</th>
            <th width="100">位置</th>
<!--            <th width="150">camp_id</th>-->
            <th width="90">app_id</th>
            <th width="150">渠道</th>
            <th>单价</th>
            <th width="130">支付方式</th>
            <th width="100">单位</th>
            <th width="100">分类</th>
            <th width="100">可投放国家</th>
            <th width="100">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  foreach($ad as $k => $v){?>
        <tr class="text-c">
            <td><?php echo $k; ?></td>
            <td><?php echo $v['name']; ?></td>
            <td><?php echo $v['camp_id']; ?></td>
            <td><?php echo $model->country; ?></td>
            <td>
                <?php
                if(isset(Yii::$app->params['ad_sort_position_name'][$model->position])){
                    echo Yii::$app->params['ad_sort_position_name'][$model->position];
                }else{
                    echo $model->position;
                }

                ?>
            </td>
<!--            <td>--><?php //echo $v['origin_camp_id']; ?><!--</td>-->
            <td><?php echo $v['app_id']; ?></td>

            <td><?php
                if(isset(Yii::$app->params['ad_source'][$v['source']])){
                    echo Yii::$app->params['ad_source'][$v['source']];
                }else{
                    echo $v['source'] ;
                }
                ?></td>
            <td><?php echo $v['payout_amount'] ?></td>
            <td><?php echo $v['payout_currency'] ?></td>
            <td><?php echo $v['acquisition_flow'] ?></td>
            <td><?php echo $v['category'] ?></td>
            <td><?php echo $v['countries'] ?></td>
            <td>
                <input type="radio" value="<?php echo $v['source'];?>" name="source" class="source"
                    data-camp_id = '<?php echo $v['camp_id'];?>'
                    data-app_id = '<?php echo $v['app_id']; ?>'
                    data-country = '<?php echo $model->country; ?>'
                    data-position = '<?php echo $model->position; ?>'
                    data-id = '<?php echo $model->id; ?>'

                       <?php if($v['camp_id'] == $model->camp_id){echo 'checked';} ?>
                >
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>



