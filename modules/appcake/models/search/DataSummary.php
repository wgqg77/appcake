<?php

namespace app\modules\appcake\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\DataSummary as DataSummaryModel;

/**
 * DataSummary represents the model behind the search form about `app\modules\appcake\models\DataSummary`.
 */
class DataSummary extends DataSummaryModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id', 'camp_id', 'ad_source', 'click', 'download', 'install', 'cake_active', 'h_click', 'h_active', 'analog_click'], 'integer'],
            [['date', 'country','name','category'], 'safe'],
            [['payout_amount'], 'number'],
        ];
    }

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
        $query = DataSummaryModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $resquest = Yii::$app->request;
        $this->startTime = $resquest->get('startTime');
        $this->endTime =  $resquest->get('endTime');

        $this->startTime = empty($this->startTime) ? date("Y-m-d",strtotime('-1 day')) : $this->startTime;
        $this->endTime = empty($this->endTime) ? date("Y-m-d",time()) : $this->endTime;


        $this->isAd = isset($_GET['DataSummary']['isAd']) ? $_GET['DataSummary']['isAd'] : 0;




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
            'country' => $this->country,
            'ad_source' => $this->ad_source,
            'click' => $this->click,
            'download' => $this->download,
            'install' => $this->install,
            'cake_active' => $this->cake_active,
            'h_click' => $this->h_click,
            'h_active' => $this->h_active,
            'analog_click' => $this->analog_click,
            'payout_amount' => $this->payout_amount,
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
