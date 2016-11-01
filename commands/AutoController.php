<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\modules\appcake\models\CakeAdIdfa;
use app\modules\appcake\models\DownloadWeek;
use yii\console\Controller;
use app\modules\appcake\models\ActiveData;
use app\modules\appcake\models\UserinfoV2;
use app\modules\appcake\models\Userinfo;
use app\modules\appcake\models\IdfaAppidV4;
use yii;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AutoController extends Controller
{
    /**
     * 查询发送邮件
     * 1. Appcake用户新增，活跃
     * 2. appcake总点击，美国总点击，推荐位总点击
     * 3. 广告总点击，总激活，总收入
     */
    public function actionYesterdayData()
    {
        $startTime = strtotime(date("Y-m-d",strtotime("-1 day")));
        $endTime = strtotime(date('Y-m-d',time()));
        $yesterday = date("Y-m-d",strtotime("-1 day"));

        //新增
//        $newUser = Userinfo::find()->where("time >= {$startTime} and time < {$endTime}")->select("count(*) as total")->asArray()->one();
//        $newUser = $newUser['total'];
        //日活
//        $dayActive = UserinfoV2::find()->where("date  = '{$yesterday}' ")->select("count(*) as total")->asArray()->one();
//        $dayActive = $dayActive['total'];
        //appcake总点击
        $cakeClickTotal = DownloadWeek::find()->where("time >= {$startTime} and time < {$endTime}")->select("count(*) as totalClick")->asArray()->one();
        $cakeClickTotal = $cakeClickTotal['totalClick'];
        //美国总点击
        $usClickTotal = DownloadWeek::find()->where("time >= {$startTime} and time < {$endTime} and country = 'US' ")->select("count(*) as totalClick")->asArray()->one();
        $usClickTotal = $usClickTotal['totalClick'];
        //推荐位置总点击
        $hotPositionClickTotal = DownloadWeek::find()->where("time >= {$startTime} and time < {$endTime} and position like '0_%' ")->select("count(*) as totalClick")->asArray()->one();
        $hotPositionClickTotal = $hotPositionClickTotal['totalClick'];

        //广告总点击 cake
        $adClickTotal = DownloadWeek::find()->where("time >= {$startTime} and time < {$endTime} and camp_id > 0 and cake_channel = 0 ")->select("count(*) as totalClick")->asArray()->one();
        $adClickTotal = $adClickTotal['totalClick'];
        //3k
        $k3ClickTotal = DownloadWeek::find()->where("time >= {$startTime} and time < {$endTime} and camp_id > 0 and cake_channel = 2 ")->select("count(*) as totalClick")->asArray()->one();
        $k3ClickTotal = $k3ClickTotal['totalClick'];

        //广告总点击 hook
        $hookAdClick = IdfaAppidV4::find()->where("date  = '{$yesterday}' and camp_id > 0 ")->select(["count(distinct camp_id,idfa) as total"])->asArray()->one();
        $hookAdClick = $hookAdClick['total'];

        $totalClick = $adClickTotal + $hookAdClick + $k3ClickTotal;

        //hook
        $hookActive = CakeAdIdfa::find()->where("time >= {$startTime} and time < {$endTime} and channel = 80 ")->select("count(*) as total")->asArray()->one();
        $hookActive = $hookActive['total'];

        //cake激活
        $cakeActive = CakeAdIdfa::find()->where("time >= {$startTime} and time < {$endTime} and channel = 0 ")->select("count(*) as total")->asArray()->one();
        $cakeActive = $cakeActive['total'];

        //bt激活
        $btActive = CakeAdIdfa::find()->where("time >= {$startTime} and time < {$endTime} and channel = 81 ")->select("count(*) as total")->asArray()->one();
        $btActive = $btActive['total'];

        //3k激活
        $k3Active = CakeAdIdfa::find()->where("time >= {$startTime} and time < {$endTime} and channel = 82 ")->select("count(*) as total")->asArray()->one();
        $k3Active = $k3Active['total'];

        $totalActive = $hookActive + $cakeActive + $k3Active + $btActive;

        //总收入
        $income = ActiveData::find()->where("date  = '{$yesterday}' ")->select("sum(total_price) as total")->asArray()->one();
        $income = $income['total'];

        $logstr = "{$yesterday}cake数据:\n";
//        $logstr .= "新增用户:  {$newUser}\n";
//        $logstr .= "日活用户:  {$dayActive}\n";
        $logstr .= "cake总点击:  {$cakeClickTotal}\n";
        $logstr .= "美国总点击:  {$usClickTotal}\n";
        $logstr .= "推荐位置总点击:  {$hotPositionClickTotal}\n";
        $logstr .= "广告点击:  {$adClickTotal}(cake) + {$hookAdClick}(hook) + {$k3ClickTotal}(3k) = {$totalClick}  \n";
        $logstr .= "激活:   {$cakeActive}(cake) + {$hookActive}(hook) + {$k3Active}(3k) + {$btActive}(bt) = {$totalActive}  \n";
        $logstr .= "总收入:  {$income}\n";

        echo $logstr;

    }

    /**
     * 汇总数据定时清理数据
     * 两个月运行一次
     * 1.清理大于60天数据  / 数据汇总表  / 汇总位置表 / 周汇总表
     *
     */
    public function actionDeleteData()
    {
        $delDay = date('Y-m-d',strtotime("-60 day"));
        $sql = "delete from data_summary where date <= '{$delDay}';
                delete from data_summary_position where date <= '{$delDay}';
                delete from data_summary_week where date <= '{$delDay}';";
        $this->InsertBysql($sql);
        echo date("Y-m-d H:i:s",time()) . " [{$delDay}]以前汇总数据清理完成\n\r";
        Yii::info(date("Y-m-d H:i:s",time()) . "清理60天以前汇总数据完成\n\r" ,'auto');
    }

    /**
     * 排序历史记录表
     * 每天汇总前一天 并清理数据
     * 一周执行一次
     */
    public function actionDelSortHistory()
    {
        //data_summary_position表有每天数据位置汇总 删除不再汇总
        $delDay = date('Y-m-d',strtotime("-7 day"));
        $sql = "delete from ad_sort_history where date <= '{$delDay}' ;";
        $this->InsertBysql($sql);
        echo date("Y-m-d H:i:s",time()) . " [{$delDay}]以前数据清理完成\n\r";
        Yii::info(date("Y-m-d H:i:s",time()) . "清理7天以前排序历史记录数据完成\n\r" ,'auto');
    }

    public function InsertBysql($sql)
    {
        try{
            $connection = \Yii::$app->db;
            return $connection->createCommand($sql)->execute();
        }catch(\Exception $e){
            $trace = $e->getTraceAsString();
            Yii::info(date("Y-m-d H:i:s",time()) . $trace ,'auto');
        }
    }
}
