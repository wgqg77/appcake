<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "app_data".
 *
 * @property integer $id
 * @property integer $app_id
 * @property string $app_name
 * @property string $version
 * @property string $category
 * @property string $vendor
 * @property integer $release_date
 * @property integer $add_date
 * @property string $size
 * @property string $icon
 * @property string $screenshot
 * @property string $screenshot2
 * @property string $screenshot3
 * @property string $screenshot4
 * @property string $screenshot5
 * @property string $ipadscreen1
 * @property string $ipadscreen2
 * @property string $ipadscreen3
 * @property string $ipadscreen4
 * @property string $ipadscreen5
 * @property integer $support_watch
 * @property string $watch_icon
 * @property string $watch_screen1
 * @property string $watch_screen2
 * @property string $watch_screen3
 * @property string $watch_screen4
 * @property string $watch_screen5
 * @property string $requirements
 * @property string $whatsnew
 * @property string $description
 * @property integer $download
 * @property integer $week_download
 * @property string $price
 * @property string $compatible
 * @property integer $need_backup
 * @property string $youtube_vid
 * @property string $v_poster
 * @property integer $v_approved
 * @property string $bundle_id
 * @property integer $stars
 * @property string $genres
 * @property integer $min_os_version
 * @property integer $ipa
 * @property string $s3_key
 * @property string $bt_url
 * @property string $app_store_version
 */
class AppData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_data';
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
            [['app_id', 'app_name', 'version', 'category', 'vendor', 'release_date', 'add_date', 'size', 'icon', 'screenshot', 'description', 'download', 'week_download'], 'required'],
            [['app_id', 'release_date', 'add_date', 'support_watch', 'download', 'week_download', 'need_backup', 'v_approved', 'stars', 'min_os_version', 'ipa'], 'integer'],
            [['whatsnew', 'description'], 'string'],
            [['app_name', 'vendor'], 'string', 'max' => 128],
            [['version', 'price', 'app_store_version'], 'string', 'max' => 30],
            [['category', 'size'], 'string', 'max' => 50],
            [['icon', 'screenshot', 'screenshot2', 'screenshot3', 'screenshot4', 'screenshot5', 'ipadscreen1', 'ipadscreen2', 'ipadscreen3', 'ipadscreen4', 'ipadscreen5', 'watch_icon', 'watch_screen1', 'watch_screen2', 'watch_screen3', 'watch_screen4', 'watch_screen5', 'bt_url'], 'string', 'max' => 255],
            [['requirements'], 'string', 'max' => 256],
            [['compatible'], 'string', 'max' => 10],
            [['youtube_vid'], 'string', 'max' => 15],
            [['v_poster'], 'string', 'max' => 25],
            [['bundle_id', 'genres'], 'string', 'max' => 100],
            [['s3_key'], 'string', 'max' => 56],
            [['app_id'], 'unique'],
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
            'app_name' => 'App Name',
            'version' => '版本',
            'category' => '栏目',
            'vendor' => '开发商',
            'release_date' => '发布日期',
            'add_date' => '添加日期',
            'size' => '文件大小',
            'icon' => 'Icon',
            'screenshot' => '截图1',
            'screenshot2' => '截图2',
            'screenshot3' => '截图3',
            'screenshot4' => '截图4',
            'screenshot5' => '截图5',
            'ipadscreen1' => 'Ipad截图1',
            'ipadscreen2' => 'Ipad截图2',
            'ipadscreen3' => 'Ipad截图3',
            'ipadscreen4' => 'Ipad截图4',
            'ipadscreen5' => 'Ipad截图5',
            'support_watch' => 'Support Watch',
            'watch_icon' => 'Watch Icon',
            'watch_screen1' => 'Watch截图1',
            'watch_screen2' => 'Watch截图2',
            'watch_screen3' => 'Watch截图3',
            'watch_screen4' => 'Watch截图4',
            'watch_screen5' => 'Watch截图5',
            'requirements' => '硬件需求',
            'whatsnew' => '更新介绍',
            'description' => '应用简介',
            'download' => '下载数',
            'week_download' => '周下载',
            'price' => '价格',
            'compatible' => '兼容机型',
            'need_backup' => 'Need Backup',
            'youtube_vid' => 'Youtube Vid',
            'v_poster' => 'V Poster',
            'v_approved' => 'V Approved',
            'bundle_id' => 'Bundle ID',
            'stars' => 'Stars',
            'genres' => 'Genres',
            'min_os_version' => 'Min Os Version',
            'ipa' => '是否Ipa包',
            's3_key' => 'S3 Key',
            'bt_url' => 'Bt Url',
            'app_store_version' => 'App Store版本',
        ];
    }


    public function getAppByAppIds($appId)
    {
        $field = ["app_id","app_name","category"];
        if(count($appId) < 100)
        {
            $res = $this->find()->where(['app_id'=>$appId])->select($field)->all();
        }
        else
        {
            $res = $this->getAdByMoreCampIds($appId,$field);
        }
        return $res;
    }

    public function getAdByMoreCampIds($appId,$field){
        $times = ceil(count($appId) / 100);
        $res = array();
        $dataArr = array();

        for($i = 0 ; $i <= $times ; $i++ ){
            $start = $i*100;
            $tempArr =  array_slice($appId,$start,100);
            $res = $this->find()->where(['app_id'=>$tempArr])->select($field)->all();
            $res = $res ? $res :array();
            $dataArr = array_merge($dataArr ,$res);

        }
        return $dataArr;
    }

    public function getAppNotInId($countryArr,$appId,$limit =205)
    {
        if(!empty($countryArr)){
            $data = array();
            foreach($countryArr as $k => $v){
                if(isset($appId[$v]) && count($appId[$v]) > 0 ){
                    $temAppid = trim(implode(',',$appId[$v]));

                    $app_id = 'and app_id not in (' .$temAppid . ')';
                }else{
                    $app_id = '';
                }

                $games = $this->find()->where("category = 'Games' $app_id")->select('app_id,category')->groupBy("app_id")->asArray()->limit($limit)->all();
                $games = $games == true ? $games : array();
                $app = $this->find()->where("category != 'Games' $app_id")->select('app_id,category')->groupBy("app_id")->asArray()->limit($limit)->all();
                $app = $app == true ? $app : array();
                $data[$v] = array_merge($app,$games);
            }
            return $data;
        }else{
            return array();
        }
    }

    public function getAppInfoInAppId($appIdArr,$fields){
        $data =  $this->find()->where(['app_id'=>$appIdArr])->select($fields)->asArray()->all();
        if($data){
            return $data;
        }else{
            return array();
        }
    }
}
