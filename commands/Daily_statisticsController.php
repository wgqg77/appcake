<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\modules\appcake\models\DailyStatistics;
use app\modules\appcake\models\HookUser;
use app\modules\appcake\models\UserDailyAppstore;
use app\modules\appcake\models\UserHookNew;
use app\modules\appcake\models\Userinfo;
use app\modules\appcake\models\UserinfoV4;
use yii\console\Controller;
use app\modules\appcake\models\DownloadWeek;
use app\modules\appcake\models\DownloadDone;
use app\modules\appcake\models\DownloadInstall;
use app\modules\appcake\models\CakeAdIdfa;
use app\modules\appcake\models\IdfaAppidV4;
use app\modules\appcake\models\AnalogClickRecord;
use app\modules\appcake\models\BatAll;
use app\modules\appcake\models\AppData;
use app\modules\appcake\models\DataSummary;
use app\components\Common;
use Yii;
class Daily_statisticsController extends Controller
{
    /**
     * daily_statistics
     */
    public function actionMerge($strartTime=null,$endTime=null)
    {
        if($strartTime == null){
            $strartTime = isset($_SERVER["argv"][2]) ? $_SERVER["argv"][2] : date('Y-m-d',strtotime('-1 day'));
            $endTime = isset($_SERVER["argv"][3]) ? $_SERVER["argv"][3] : date('Y-m-d',time());
        }
        //$strartTime = '2015-09-02';
        $logStr = '汇总每日数据';
        $mergeModel = new DailyStatistics();
        $isInserted = $mergeModel->IsInserted($strartTime);
        if($isInserted){
            $logStr = date("Y-m-d H:i:s",time()) ."执行开始时间: $strartTime 认定已经执行过,停止任务.";
            echo $logStr;
            Yii::info($logStr . $endTime ,'merge');die;
        }

        //appcake新增
        $cakeNewUserModel = new Userinfo();
        $cakeNewUser = $cakeNewUserModel->getNewUserTotal($strartTime,$endTime);
        $cakeNewUser = $cakeNewUser['count'];

        //apcake活跃 user_info_v4
        $userInfoV4Model =  new UserinfoV4();
        $cakeActiveUser = $userInfoV4Model->getActiveuser($strartTime);
        $cakeActiveUser = (int)$cakeActiveUser['count'];

        //appcake激活
        $dataSummaryModel = new DataSummary();
        $data = $dataSummaryModel->getSumByDate($strartTime);
        $cakeActive =  (int)$data['cakeActive'];

        //appcake下载
        $cakeDownload = (int)$data['cakeDownload'];


        //appcake单个活跃下载
        if($cakeDownload){
            $cakeActivation = sprintf('%.2f',$cakeDownload / $cakeActiveUser) ;
        }else{
            $cakeActivation = 0;
        }


        //hook 新增   暂无数据
        $hooNewUserModel = new UserHookNew();
        $hooNewUser = $hooNewUserModel->getNewUser($strartTime,$endTime);


        //hook 活跃
        $hookActiveUserModel = new UserDailyAppstore();
        $hookActiveUser = $hookActiveUserModel->getActiveuser($strartTime);


        //hook 下载
        $hookDownload =  (int)$data['hookDownload'];

        //hook 广告去重
        $idfaModel = new IdfaAppidV4();
        $adCount = $idfaModel->getHookAdNum($strartTime,$endTime);
        $adCount = $adCount['count'];

        //hook 激活
        $hookActive =  (int)$data['hookActive'];

        //总下载
        $totalDownload = $cakeDownload + $hookDownload;
        //总激活
        $totalActive = $cakeActive + $hookActive;
        //总收入
        $totalIncome =  (int)$data['totalIncome'];
        //a 单价
        if($totalIncome){
            $aPrice = sprintf('%.2f',$totalIncome / $totalActive);
        }else{
            $aPrice = 0;
        }

        $sql = "INSERT INTO `daily_statistics` (`id`, `date`, `cake_new_user`, `cake_active_user`, `cake_activation`, `cake_download`,`cake_active_down`, `hook_new_user`, `hook_active_user`, `hook_download`, `hook_ad_no_repeat`, `hook_activation`, `all_download`, `all_activation`, `all_income`, `a_price`) VALUES";
        $sql .= "(0, '{$strartTime}', {$cakeNewUser}, {$cakeActiveUser}, {$cakeActive}, {$cakeDownload},{$cakeActivation}, {$hooNewUser},{$hookActiveUser}, {$hookDownload}, {$adCount}, {$hookActive}, {$totalDownload}, {$totalActive}, {$totalIncome}, {$aPrice});";

        Common::dbExecute($sql);

        echo $logStr. "end \n\r";
        Yii::info($logStr . $endTime ,'merge');
    }



    //1 广告 0 非广告 导入合并31天数据
    public function actionIndex()
    {
        $isAd = isset($_SERVER["argv"][2]) ? 1 : 0;
        for($i = 1 ;$i <= 31; $i ++)
        {
            echo "当前任务第{$i}条\n\r";
            $strTime = date('Y-m-d',strtotime('-'.$i.' day'));
            $endTime = date('Y-m-d',strtotime('-'.$i + 1 .' day'));
            if($isAd){
                $this->actionAdData($strTime,$endTime);
            }else{
                $this->actionNotAdData($strTime,$endTime);
            }

        }
    }

}
