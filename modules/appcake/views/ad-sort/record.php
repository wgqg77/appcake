<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\AdSortOne */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '排序操作记录';
$this->params['breadcrumbs'][] = ['label' => '广告排序管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;




?>
<?= Html::a('清空当前排序记录', ['/appcake/ad-sort/del-sort-record'], ['class' => 'btn btn-success']) ?>
<div class="page-container">

    <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">

        </span> <span class="r">共有数据：<strong><?php echo count($record);?></strong> 条</span> </div>
    <table class="table table-border table-bordered table-bg">
        <thead>
        <tr class="text-c">
            <th width="40">顺序</th>
            <th width="150">国家</th>
            <th width="90">位置</th>
            <th width="150">广告id</th>
            <th width="100">渠道</th>
            <th>app_id</th>
            <th width="130">当前排序</th>
            <th width="100">下次排序</th>

            <th width="100">更新时间</th>
            <th width="100">更新方式</th>
            <th width="100">操作时间</th>
<!--            <th width="100">操作</th>-->
        </tr>
        </thead>
        <tbody>
        <?php  foreach($record as $k => $v){?>
        <tr class="text-c">
            <td><?php echo count($record) - $k; ?></td>
            <td><?php
                if(isset(Yii::$app->params['country_list_cn'][$v['country']])){
                    echo $v['country'] .'_'.Yii::$app->params['country_list_cn'][$v['country']];
                }else{
                    echo $v['country'] ;
                }
                ?></td>
            <td>
                <?php
                if(isset(Yii::$app->params['ad_sort_position_name'][$v['position']])){
                    echo Yii::$app->params['ad_sort_position_name'][$v['position']];
                }else{
                    echo $v['position'] ;
                }
                ?>
            </td>
            <td><?php echo $v['camp_id'] ?></td>
            <td><?php
                if(isset(Yii::$app->params['ad_source'][$v['source']])){
                    echo Yii::$app->params['ad_source'][$v['source']];
                }else{
                    echo $v['source'] ;
                }
                ?></td>
            <td><?php echo $v['app_id'] ?></td>
            <td><?php echo $v['current_sort'] ?></td>
            <td><?php echo $v['next_sort'] ?></td>

            <td><?php
                if(isset(Yii::$app->params['ad_sort_update_method'][$v['update_method']])){
                    echo Yii::$app->params['ad_sort_update_method'][$v['update_method']];
                }else{
                    echo $v['update_method'] ;
                }
                if($v['update_method'] == 2){
                    echo ':' . $v['start_time']  .' 至 '. $v['end_time'];
                }
                ?></td>
            <td><?php
                if(isset(Yii::$app->params['ad_sort_method'][$v['sort_method']])){
                    echo Yii::$app->params['ad_sort_method'][$v['sort_method']];
                }else{
                    echo $v['sort_method'] ;
                }
                ?></td>
            <td><?php echo $v['create_time'] ?></td>
<!--            <td class="td-manage"><a style="text-decoration:none" onClick="admin_stop(this,'10001')" href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a> <a title="编辑" href="javascript:;" onclick="admin_edit('管理员编辑','admin-add.html','1','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>-->
        </tr>
       <?php }?>
        </tbody>
    </table>
</div>
<script type="text/javascript" src="lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript" src="lib/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="static/h-ui/js/H-ui.admin.js"></script>
<script type="text/javascript">
    /*
     参数解释：
     title	标题
     url		请求的url
     id		需要操作的数据id
     w		弹出层宽度（缺省调默认值）
     h		弹出层高度（缺省调默认值）
     */
    /*管理员-增加*/
    function admin_add(title,url,w,h){
        layer_show(title,url,w,h);
    }
    /*管理员-删除*/
    function admin_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            //此处请求后台程序，下方是成功后的前台处理……

            $(obj).parents("tr").remove();
            layer.msg('已删除!',{icon:1,time:1000});
        });
    }
    /*管理员-编辑*/
    function admin_edit(title,url,id,w,h){
        layer_show(title,url,w,h);
    }
    /*管理员-停用*/
    function admin_stop(obj,id){
        layer.confirm('确认要停用吗？',function(index){
            //此处请求后台程序，下方是成功后的前台处理……

            $(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_start(this,id)" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
            $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已禁用</span>');
            $(obj).remove();
            layer.msg('已停用!',{icon: 5,time:1000});
        });
    }

    /*管理员-启用*/
    function admin_start(obj,id){
        layer.confirm('确认要启用吗？',function(index){
            //此处请求后台程序，下方是成功后的前台处理……


            $(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_stop(this,id)" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
            $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
            $(obj).remove();
            layer.msg('已启用!', {icon: 6,time:1000});
        });
    }
</script>
</body>
</html>