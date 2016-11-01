<?php

namespace app\modules\appcake\models\search;

use Yii;
use yii\base\Model;
use app\components\data\ActiveDataProvider;
use app\modules\appcake\models\DataSummaryPosition as DataSummaryPositionModel;

/**
 * DataSummary represents the model behind the search form about `app\modules\appcake\models\DataSummary`.
 */
class DataSummaryPosition extends DataSummaryPositionModel
{

    public $clickTotal = 0;
    public $downloadTotal = 0;
    public $installTotal = 0;
    public $total = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id', 'camp_id', 'ad_source', 'click', 'download', 'install'], 'integer'],
            [['date', 'country','name','category'], 'safe'],
        ];
    }

    public $showTotalNum = true;
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DataSummaryPositionModel::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        //提供true设置是否count(*)获取总数 或者false使用默认设置值
        //$dataProvider->setPageCount(true);

        $this->load($params);

        //$resquest = Yii::$app->request;
        //$this->startTime = $resquest->get('startTime');
        //$this->endTime =  $resquest->get('endTime');
        $this->startTime = isset($_GET['startTime']) ? $_GET['startTime'] : date("Y-m-d",strtotime('-1 day'));
        $this->endTime = isset($_GET['endTime']) ? $_GET['endTime'] : date("Y-m-d",time());
        $this->isAd = isset($_GET['DataSummaryPosition']['isAd']) ? $_GET['DataSummaryPosition']['isAd'] : 0;




        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['between', 'date', $this->startTime, $this->endTime]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'app_id' => $this->app_id,
            'camp_id' => $this->camp_id,
            'country'=> $this->country,
            'ad_source' => $this->ad_source,
            'click' => $this->click,
            'download' => $this->download,
            'install' => $this->install,
            'name' => $this->name,
        ]);

        
        if($this->isAd == 1){
            $query->andFilterWhere(['>', 'camp_id', 0]);
        }else if($this->isAd == 2){
            $query->andFilterWhere(['=', 'camp_id', 0]);
        }


        return $dataProvider;
    }
}
