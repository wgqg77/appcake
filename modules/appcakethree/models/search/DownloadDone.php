<?php

namespace app\modules\appcakethree\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcakethree\models\DownloadDone as DownloadDoneModel;

/**
 * DownloadDone represents the model behind the search form about `app\modules\appcake\models\DownloadDone`.
 */
class DownloadDone extends DownloadDoneModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id', 'time', 'camp_id'], 'integer'],
            [['position', 'idfa', 'ip', 'device', 'country'], 'safe'],
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
        $query = DownloadDoneModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);
        $this->cake_channel = 2;
        $resquest = Yii::$app->request;
        $this->startTime = $resquest->get('startTime');
        $this->endTime =  $resquest->get('endTime');

        $this->startTime = empty($this->startTime) ? strtotime('-1 day') : strtotime($this->startTime);
        $this->endTime = empty($this->endTime) ? time() : strtotime($this->endTime);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'app_id' => $this->app_id,
            'time' => $this->time,
            'camp_id' => $this->camp_id,
            'cake_channel' => $this->cake_channel,
        ]);

        $query->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'idfa', $this->idfa])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'device', $this->device])
            ->andFilterWhere(['between', 'time', $this->startTime, $this->endTime])
            ->andFilterWhere(['like', 'country', $this->country]);

        if($this->isAd == 1){
            $query->andFilterWhere(['>', 'camp_id', 0]);
        }else if($this->isAd == 2){
            $query->andFilterWhere(['camp_id' =>  null ]);
        }

        $query->select('download_done.app_id,d.app_name,camp_id,country,category,position,idfa,count(*) as count')->groupBy('app_id')->orderBy("count desc");

        $query->leftJoin('app_data as d','d.app_id = download_done.app_id');
        return $dataProvider;
    }
}
