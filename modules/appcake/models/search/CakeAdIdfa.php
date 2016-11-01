<?php

namespace app\modules\appcake\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\CakeAdIdfa as CakeAdIdfaModel;

/**
 * CakeAdIdfa represents the model behind the search form about `app\modules\appcake\models\CakeAdIdfa`.
 */
class CakeAdIdfa extends CakeAdIdfaModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'camp_id', 'type', 'number', 'time', 'app_id', 'channel'], 'integer'],
            [['idfa', 'date', 'country_code'], 'safe'],
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
        $query = CakeAdIdfaModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        $this->load($params);

        $resquest = Yii::$app->request;
        $this->startTime = $resquest->get('startTime');
        $this->endTime =  $resquest->get('endTime');
        $this->aid =  isset($_GET['CakeAdIdfa']['aid']) && !empty($_GET['CakeAdIdfa']['aid']) ?
                      $_GET['CakeAdIdfa']['aid'] :
                      0 ;


        if($this->aid == 1){
            $this->channel = 0;
        }else if($this->aid == 2){
            $this->channel = 80;
        }else if($this->aid == 3){
            $this->channel = 81;
        }else if($this->aid == 0){
            $this->channel = '';
        }else{
            $this->channel = '';
        }

        $this->startTime = empty($this->startTime) ? date("Y-m-d",strtotime('-1 day')) : $this->startTime;
        $this->endTime = empty($this->endTime) ? date("Y-m-d",time()) : $this->endTime;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'camp_id' => $this->camp_id,
            'type' => $this->type,
            'date' => $this->date,
            'number' => $this->number,
            'time' => $this->time,
            'app_id' => $this->app_id,
            'channel' => $this->channel,
        ]);



        $query->andFilterWhere(['like', 'idfa', $this->idfa])
            ->andFilterWhere(['between', 'time', strtotime($this->startTime), strtotime($this->endTime)]);

        if($this->country_code  == 1){
            $query->andFilterWhere(["country_code"=>'is null']);
        }else{
            $query->andFilterWhere(['country_code' => $this->country_code]);
        }

        $query->groupBy("date,camp_id,idfa");
        return $dataProvider;
    }
}
