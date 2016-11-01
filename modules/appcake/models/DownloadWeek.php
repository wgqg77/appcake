<?php

namespace app\modules\appcake\models;
use app\components\Common;
use Yii;

/**
 * This is the model class for table "download_week".
 *
 * @property integer $id
 * @property integer $app_id
 * @property string $position
 * @property string $idfa
 * @property integer $time
 * @property string $ip
 * @property string $device
 * @property string $country
 * @property integer $camp_id
 */
class DownloadWeek extends \yii\db\ActiveRecord
{

    public $startTime = '';

    public $endTime = '';

    public $app_name = '';

    public $count = 0;

    public $category = '';

    public $isAd = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'download_week';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('cake');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'time', 'camp_id'], 'integer'],
            [['position'], 'string', 'max' => 10],
            [['idfa'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 16],
            [['device'], 'string', 'max' => 20],
            [['country'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'position' => 'Position',
            'idfa' => 'Idfa',
            'time' => 'Time',
            'ip' => 'Ip',
            'device' => 'Device',
            'country' => 'Country',
            'camp_id' => 'Camp ID',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'app_name' => '名称',
        ];
    }

    public function getData($startTime,$endTime)
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $data = $this->find()
            ->where("time >= {$startTime} and time < {$endTime} and camp_id > 0")
            ->groupBy("camp_id,country")
            ->select(["CONCAT(camp_id, '|', country) AS pkey","app_id","camp_id","count( * ) AS count"])
            ->asArray()
            ->all();
        $data = $data == true ? $data : array();
        return $data;
    }

    public function getNotAdData($startTime,$endTime)
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $data = $this->find()
            ->where("time >= {$startTime} and time < {$endTime} and camp_id is null")
            ->groupBy("app_id,country")
            ->select(["CONCAT(app_id, '|', country) AS pkey","app_id","count( * ) AS count"])
            ->asArray()
            ->all();
        $data = $data == true ? $data : array();
        return $data;
    }

    public function getDataWithPosition($startTime,$endTime)
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $data = $this->find()
            ->where("time >= {$startTime} and time < {$endTime} and camp_id > 0 and position is not null and country is not null")
            ->groupBy("camp_id,country,position")
            ->select(["CONCAT(camp_id, '|', country, '|',position) AS pkey","app_id","camp_id","count( * ) AS count"])
            ->asArray()
            ->all();
        $data = $data == true ? $data : array();
        return $data;
    }

//    public function getDataNotAdWithPosition($startTime,$endTime,$limit)
//    {
//        $startTime = strtotime($startTime);
//        $endTime = strtotime($endTime);
//        $data = $this->find()
//            ->where("time >= {$startTime} and time < {$endTime} and camp_id is null and position is not null and country is not null")
//            ->groupBy("app_id,country,position")
//            ->select(["CONCAT(app_id, '|', country, '|',position) AS pkey","app_id","camp_id","count( * ) AS count"])
//            ->limit(1,2)
//            ->asArray()
//            ->all();
//        $data = $data == true ? $data : array();
//        return $data;
//    }

    public function getDataNotAdWithPosition($startTime,$endTime,$i,$limit,$position= true)
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $start = $i * $limit;
        if($position){
            $sql = "select CONCAT(app_id, '|', country, '|',position) AS pkey,app_id,camp_id,count( * ) AS count
                from download_week
                where time >= {$startTime}
                    and time < {$endTime}
                    and camp_id is null
                    and position is not null
                    and country is not null
                group by app_id,country,position
                limit $start,$limit;";
        }else{
            $sql = "select CONCAT(app_id, '|', country) AS pkey,app_id,camp_id,count( * ) AS count
                from download_week
                where time >= {$startTime}
                    and time < {$endTime}
                    and camp_id is null
                group by app_id,country
                limit $start,$limit;";
        }

        $data = Common::cakeQuery($sql);
        $data = $data == true ? $data : array();
        return $data;
    }

    public function getCountNumNotAd($startTime,$endTime,$position= true)
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        if($position){
            $countSql = "select count(*) as count from
                ( select app_id from download_week where time >= {$startTime}
                and time < {$endTime} and camp_id is null
                and position is not null
                and country is not null group by app_id,country,position) as downcount;";
        }else{
            $countSql = "select count(*) as count from
                ( select app_id from download_week where time >= {$startTime}
                and time < {$endTime} and camp_id is null
                group by app_id,country) as downcount;";
        }
        $count = Common::cakeQuery($countSql);
        $count = !empty($count) ? $count[0]['count'] : 0;
        return $count;
    }

}
