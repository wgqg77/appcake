<?php
use yii\helpers\Url;
use app\modules\admin\models\AuthGroup;
use app\modules\admin\models\AuthRule;
$authGroupModel = new AuthGroup();
$authRuleModel = new AuthRule();
$menu = $authRuleModel->getMenuByPosition(3);
$authGropuModle = new AuthGroup();
$userRules = $authGropuModle->getUserRues();
$userRules = array_column($userRules,'name');
$noAuth = Yii::$app->params['notCheck'];
$userRulesId = array_merge($userRules,$noAuth);

?>

<aside class="Hui-aside">
    <input runat="server" id="divScrollValue" type="hidden" value="" />
    <div class="menu_dropdown bk_2">

        <?php foreach($menu as $k => $v){ if(!in_array($v['name'],$userRulesId)){ continue;} ?>

            <dl id="menu-product">
                <dt><i class="Hui-iconfont">&#xe620;</i> <?php echo $v['title']; ?><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>

                <?php  if(isset($v['sub']) && count($v['sub']) > 0 ){ ?>
                    <dd>
                        <ul>

                  <?php foreach($v['sub'] as $kk => $vv){ if(!in_array($vv['name'],$userRulesId)){ continue;} ?>

                      <li><a _href="<?php echo Url::toRoute("{$vv['name']}",true); ?>" data-title="<?php echo $vv['title']; ?>" href="javascript:void(0)"><?php echo $vv['title']; ?></a></li>

                  <?php }?>

                        </ul>
                    </dd>
                <?php } ?>

            </dl>
        <?php } ?>

    </div>
</aside>