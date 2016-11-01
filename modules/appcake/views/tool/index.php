<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\Special */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '查询导出';
$this->params['breadcrumbs'][] = ['label' => '调试工具', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$getTimeUrl = Url::to(['/appcake/tool/gettime']);
$selectUrl = Url::to(['/appcake/tool/select']);
$putOutUrl = Url::to(['/appcake/tool/put-out-excel']);

//$this->registerCssFile("lib/My97DatePicker/WdatePicker.js");
$this->registerJs('


	$(function(){

		//获取服务器时间
		$("#gettime").click(function(){
			var date = $("#w0").val();
			if(date == \'\'){
				window.alertError("请选择查询转换日期");
				return false;
			}
			var postData = "date="  + date;
			ityzl_SHOW_LOAD_LAYER();
			ajaxPost(getTimeUrl,postData,function(data){
				ityzl_SHOW_TIP_LAYER();
				obj=JSON.parse(data);
				$("#time").val(obj.data);
			})
		})

		//查询
		$("#search").click(function(){
			var sql = $("#sql-val").val();
			var db = $("#db").find("option:selected").val();
			var name = $("#name").val();
			if(sql == \'\'){
				window.alertError("sql不能为空");
				return false;
			}
			var postData = "sql="+sql+"&db="+db+"&type=0&name="+name;
			ityzl_SHOW_LOAD_LAYER();
			ajaxPost_loading(selectUrl,postData,function(data){
				ityzl_SHOW_TIP_LAYER();
				$("#dump").html("<pre>" + data + "</pre>")
			})
		})

		//导出
		$("#putout").click(function(){
			var newTab=window.open(\'about:blank\');
			var sql = $("#sql-val").val();
			var db = $("#db").find("option:selected").val();
			var name = $("#name").val();
			if(sql == \'\'){
				window.alertError("sql不能为空");
				return false;
			}
			var postData = "sql="+sql+"&db="+db+"&type=1&name="+name;
			ityzl_SHOW_LOAD_LAYER();
			ajaxPost_loading(selectUrl,postData,function(data){
				ityzl_SHOW_TIP_LAYER();
				obj=JSON.parse(data);
				if(obj.code == 10000){
					window.alertSuccess("导出完成");
					//window.open(obj.url);
					newTab.location.href=obj.url;
				}else{
					window.alertError("导出失败");
				}
			})
		})

	})


		function ityzl_SHOW_LOAD_LAYER(){
            return layer.msg(\'处理中...\', {icon: 16,shade: [0.5, \'#f5f5f5\'],scrollbar: false,offset: \'0px\', time:100000}) ;
        }
        function ityzl_CLOSE_LOAD_LAYER(index){
            layer.close(index);
        }
        function ityzl_SHOW_TIP_LAYER(){
            layer.msg(\'恭喜您，处理完成！\',{time: 1000,offset: \'10px\'});
        }

')


?>
<script>
	var getTimeUrl = "<?php echo $getTimeUrl;?>";
	var putOutUrl = "<?php echo $putOutUrl;?>";
	var selectUrl = "<?php echo $selectUrl;?>";
</script>

<article class="page-container">
	<form class="form form-horizontal" id="form-article-add">



		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">时间转换：</label>
			<div class="formControls col-xs-4 col-sm-4">
				<?= \kartik\widgets\DatePicker::widget([
				'value' => isset($_GET['startTime'])?$_GET['startTime']:'',
				'name'=>'startTime',
				'class'=>'col-xs-4 col-sm-4',
				"pluginOptions" => array(
				"format" => "yyyy-m-d",
				)
				])?>
			</div>

				<button id="gettime"  class="btn btn-primary radius" type="button">转换</button>

			<div class="formControls col-xs-4 col-sm-4">
			<input id="time" type="text" class="input-text" value="" placeholder="" id="" name="">
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">file name：</label>


			<div class="formControls col-xs-8 col-sm-8">
				<input id="name" type="text" class="input-text" value="" placeholder="" id="" name="">
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>db：</label>
			<div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select name="db" class="select" id="db">
					<option value="app_system">app_system</option>
					<option value="iphonecake">iphonecake</option>
					<option value="ad_system2">ad_systems2</option>
					<option value="apptree_weike">apptree_weike</option>
				</select>
				</span> </div>
		</div>


		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">sql：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea name="sql" id="sql-val" cols="" rows="" class="textarea"  placeholder="sql" datatype="*10-100" dragonfly="true" nullmsg="sql不能为空！" ></textarea>

			</div>
		</div>




		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button id="search" class="btn btn-primary radius" type="button">查询</button>
				<button id="putout" class="btn btn-secondary radius" type="button">导出</button>
				<button class="btn btn-default radius" type="reset">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>

	</form>
</article>

<div id="dump" class="dump">

</div>


