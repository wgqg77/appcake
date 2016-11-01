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

$putOutUrl = Url::to(['/appcake/excel/out-put-excel']);
$loading = Url::to(['/appcake/excel/loading']);
//$this->registerCssFile("lib/My97DatePicker/WdatePicker.js");
$this->registerJs('


	$(function(){

		//导出
		$("#putout").click(function(){

			var startTime = $("#w0").val();
			var endTime = $("#w1").val();
			var db = $("#db").find("option:selected").val();
			var name = $("#name").val();
			var source = $("#source").find("option:selected").val();
			if(db != 6 || db != 7){
				source = "";
			}
			if(startTime == \'\' && endTime == \'\'){

				window.alertError("起止时间不能为空");
				return false;
			}

			//非广告相关导出限制导出最大1天数据
			if(endTime == \'\' ){
				if(db == 2 || db == 4 ){

				}else{
					window.alertError("截止时间不能为空");
					return false;
				}
			}

			//var newTab=window.open("请等待",\'about:blank\');
			var newTab=window.open (loadingUrl,\'newwindow\',\'height=200,width=400,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no\')
			var postData = "startTime="+startTime+"&endTime="+endTime+"&db="+db+"&name="+name+"&source="+source;
			ityzl_SHOW_LOAD_LAYER();
			ajaxPost_loading(putOutUrl,postData,function(data){
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

		//渠道选择
		$("#db").on(\'change\',function(){
			var db = $("#db").find("option:selected").val();
			if(db == 6 || db == 7){
				$("#adsource").show();
			}else{
				$("#adsource").hide();
			}
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
	var putOutUrl = "<?php echo $putOutUrl;?>";
	var loadingUrl = "<?php echo $loading;?>";
</script>

<article class="page-container">
	<form class="form form-horizontal" id="form-article-add">



		<div class="row cl">
			<label class="form-label col-xs-2 col-sm-2">起止时间：</label>
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

			<div class="formControls col-xs-4 col-sm-4">
				<?= \kartik\widgets\DatePicker::widget([
					'value' => isset($_GET['endTime'])?$_GET['endTime']:'',
					'name'=>'endTime',
					'class'=>'col-xs-4 col-sm-4',
					"pluginOptions" => array(
						"format" => "yyyy-m-d",
					)
				])?>
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2">保存文件名：</label>


			<div class="formControls col-xs-8 col-sm-8">
				<input id="name" type="text" class="input-text" value="" placeholder="" id="" name="">
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red"></span>导出内容：</label>
			<div class="formControls col-xs-8 col-sm-8"> <span class="select-box">
				<select name="db" class="select" id="db">
					<option value="1">汇总统计数据(广告)</option>
					<option value="2">汇总统计数据(非广告)   [1天/次]</option>
					<option value="5">最近7日汇总统计数据(广告)   [1天/次]</option>
					<option value="3">位置统计(广告)</option>
					<option value="4">位置统计(all)   [1天/次]</option>
					<option value="6">广告结算详情单</option>
					<option value="7">广告结算单</option>
				</select>
				</span> </div>
		</div>


		<div class="row cl" id="adsource" style="display: none;">
			<label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>广告主：</label>
			<div class="formControls col-xs-8 col-sm-8"> <span class="select-box">
				<select name="source" class="select" id="source">
					<?php
					$adSource = Yii::$app->params['ad_source'];
					foreach($adSource as $k => $v){
						$str =  '<option value="' . $k. '">' . $v .  '</option>';
						echo $str;
					}

					?>

				</select>
				</span> </div>
		</div>



		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button id="putout" class="btn btn-secondary radius" type="button">导出</button>
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2" style="color: #999;">
				注:[1天/次] : 每次限制导出一天的数据, 时间为开始时间.
			</div>
		</div>
	</form>
</article>

<div id="dump" class="dump">

</div>

<script>

</script>

