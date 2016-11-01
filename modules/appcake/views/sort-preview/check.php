<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\assets\HuiAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\AdSortOne */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '排序预览';
$this->params['breadcrumbs'][] = $this->title;
HuiAsset::register($this);

?>

<div class="page-container">
	<form class="form form-horizontal" id="form-article-add">
		<div id="tab-system" class="HuiTab">
			<div class="tabBar cl"><span>首页</span><span>应用</span><span>游戏</span></div>
			<div class="tabCon" style="display: block;">
				<div class="page-container">
					<div class="text-c"> <span class="select-box inline">
		<select name="" class="select">
			<option value="0">全部分类</option>
			<option value="1">分类一</option>
			<option value="2">分类二</option>
		</select>
		</span> 日期范围：
						<input onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}'})" id="logmin" class="input-text Wdate" style="width:120px;" type="text">
						-
						<input onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d'})" id="logmax" class="input-text Wdate" style="width:120px;" type="text">
						<input name="" id="" placeholder=" 资讯名称" style="width:250px" class="input-text" type="text">
						<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont"></i> 搜资讯</button>
					</div>
					<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="datadel()" class="btn btn-danger radius"><i class="Hui-iconfont"></i> 批量删除</a> <a class="btn btn-primary radius" data-title="添加资讯" _href="article-add.html" onclick="Hui_admin_tab(this)" href="javascript:;"><i class="Hui-iconfont"></i> 添加资讯</a></span> <span class="r">共有数据：<strong>54</strong> 条</span> </div>
					<div class="mt-20">
						<div class="dataTables_wrapper no-footer" id="DataTables_Table_0_wrapper"><div id="DataTables_Table_0_length" class="dataTables_length"><label>显示 <select class="select" aria-controls="DataTables_Table_0" name="DataTables_Table_0_length"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> 条</label></div><div class="dataTables_filter" id="DataTables_Table_0_filter"><label>从当前数据中检索:<input aria-controls="DataTables_Table_0" class="input-text " type="search"></label></div><table aria-describedby="DataTables_Table_0_info" role="grid" id="DataTables_Table_0" class="table table-border table-bordered table-bg table-hover table-sort dataTable no-footer">
								<thead>
								<tr role="row" class="text-c"><th aria-label="" style="width: 25px;" colspan="1" rowspan="1" class="sorting_disabled" width="25"><input name="" value="" type="checkbox"></th><th aria-label="ID: 升序排列" aria-sort="descending" style="width: 80px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting_desc" width="80">ID</th><th aria-label="标题: 升序排列" style="width: 606px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting">标题</th><th aria-label="分类: 升序排列" style="width: 80px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting" width="80">分类</th><th aria-label="来源: 升序排列" style="width: 80px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting" width="80">来源</th><th aria-label="更新时间: 升序排列" style="width: 120px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting" width="120">更新时间</th><th aria-label="浏览次数: 升序排列" style="width: 75px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting" width="75">浏览次数</th><th aria-label="发布状态: 升序排列" style="width: 60px;" colspan="1" rowspan="1" aria-controls="DataTables_Table_0" tabindex="0" class="sorting" width="60">发布状态</th><th aria-label="操作" style="width: 120px;" colspan="1" rowspan="1" class="sorting_disabled" width="120">操作</th></tr>
								</thead>
								<tbody>


								<tr role="row" class="text-c odd">
									<td><input value="" name="" type="checkbox"></td>
									<td class="sorting_1">10002</td>
									<td class="text-l"><u style="cursor:pointer" class="text-primary" onclick="article_edit('查看','article-zhang.html','10002')" title="查看">资讯标题</u></td>
									<td>行业动态</td>
									<td>H-ui</td>
									<td>2014-6-11 11:11:42</td>
									<td>21212</td>
									<td class="td-status"><span class="label label-success radius">草稿</span></td>
									<td class="f-14 td-manage"><a style="text-decoration:none" onclick="article_shenhe(this,'10001')" href="javascript:;" title="审核">审核</a> <a style="text-decoration:none" class="ml-5" onclick="article_edit('资讯编辑','article-add.html','10001')" href="javascript:;" title="编辑"><i class="Hui-iconfont"></i></a> <a style="text-decoration:none" class="ml-5" onclick="article_del(this,'10001')" href="javascript:;" title="删除"><i class="Hui-iconfont"></i></a></td>
								</tr><tr role="row" class="text-c even">
									<td><input value="" name="" type="checkbox"></td>
									<td class="sorting_1">10001</td>
									<td class="text-l"><u style="cursor:pointer" class="text-primary" onclick="article_edit('查看','article-zhang.html','10001')" title="查看">资讯标题</u></td>
									<td>行业动态</td>
									<td>H-ui</td>
									<td>2014-6-11 11:11:42</td>
									<td>21212</td>
									<td class="td-status"><span class="label label-success radius">已发布</span></td>
									<td class="f-14 td-manage"><a style="text-decoration:none" onclick="article_stop(this,'10001')" href="javascript:;" title="下架"><i class="Hui-iconfont"></i></a> <a style="text-decoration:none" class="ml-5" onclick="article_edit('资讯编辑','article-add.html','10001')" href="javascript:;" title="编辑"><i class="Hui-iconfont"></i></a> <a style="text-decoration:none" class="ml-5" onclick="article_del(this,'10001')" href="javascript:;" title="删除"><i class="Hui-iconfont"></i></a></td>
								</tr></tbody>
							</table><div aria-live="polite" role="status" id="DataTables_Table_0_info" class="dataTables_info">显示 1 到 2 ，共 2 条</div><div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers"><a id="DataTables_Table_0_previous" tabindex="0" data-dt-idx="0" aria-controls="DataTables_Table_0" class="paginate_button previous disabled">上一页</a><span><a tabindex="0" data-dt-idx="1" aria-controls="DataTables_Table_0" class="paginate_button current">1</a></span><a id="DataTables_Table_0_next" tabindex="0" data-dt-idx="2" aria-controls="DataTables_Table_0" class="paginate_button next disabled">下一页</a></div></div>
					</div>
				</div>
			</div>
			<div class="tabCon" style="display: block;">
			</div>



		</div>
		<div class="row cl" style="display: none;">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
				<button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
				<button onClick="layer_close();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
			</div>
		</div>
	</form>
</div>



