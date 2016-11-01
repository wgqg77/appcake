<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admin\models\AuthGroup;
use app\modules\admin\models\AuthRule;
$authGroupModel = new AuthGroup();
$authRuleModel = new AuthRule();

$menu = $authRuleModel->getMenuByPosition();
$authGropuModle = new AuthGroup();
$userRules = $authGropuModle->getUserRues();
$userRules = array_column($userRules,'name');
$noAuth = Yii::$app->params['notCheck'];
$userRulesId = array_merge($userRules,$noAuth);

?>
<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="<?php echo Url::toRoute('/admin/index/index',true); ?>">
            应用管理后台
        </a>  <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>

            <nav class="nav navbar-nav">
                <ul class="cl">

                   <!-- 头部二级导航 需要用户有权限才显示-->
                    <?php foreach($menu as $k => $v){
                        if(!in_array($v['name'],$userRulesId)){ continue;}
                        ?>
                    <li class="dropDown dropDown_hover"><a href="<?php echo Url::toRoute("{$v['name']}") ;?>" class="dropDown_A"><i class="Hui-iconfont">&#xe620;</i> <?php echo $v['title']; ?> <i class="Hui-iconfont">&#xe6d5;</i></a>
                       <?php if(isset($v['sub']) && count($v['sub']) > 0){ ?>
                       <ul class="dropDown-menu menu radius box-shadow">
                          <?php foreach($v['sub'] as $kk => $vv){  ?>
                              <?php if(in_array($vv['name'],$userRulesId)){?>
                                  <li><a href="<?php echo Url::toRoute("{$vv['name']}",true); ?>" data-title="<?php echo $vv['title']; ?>" ><?php echo $vv['title']; ?></a></li>
                              <?php }else{
                                  continue;
                              } ?>
                            <?php }?>
                       </ul>
                       <?php }?>
                    </li>
                    <?php } ?>
                </ul>
            </nav>

            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl">
                    <li><?php echo Yii::$app->session['user_name']. '  ['.  Yii::$app->session['group_name'] .']'; ?></li>
                    <li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A"> <i class="Hui-iconfont">&#xe6d5;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
<!--                            <li><a href="--><?php //echo Url::toRoute('/admin/userself/index',true); ?><!--">个人信息</a></li>-->
<!--                            <li><a href="#">切换账户</a></li>-->
                            <li><a href="<?php echo Url::toRoute('/admin/login/loginout',true); ?>">退出</a></li>
                        </ul>
                    </li>
<!--                    <li id="Hui-msg"> <a href="#" title="消息"><span class="badge badge-danger">1</span><i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i></a> </li>-->
                    <li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
                            <li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
                            <li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
                            <li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
                            <li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
                            <li><a href="javascript:;" data-val="orange" title="绿色">橙色</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>