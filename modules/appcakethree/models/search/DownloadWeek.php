<?php

namespace app\modules\appcakethree\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcakethree\models\DownloadWeek as DownloadWeekModel;

/**
 * DownloadWeek represents the model behind the search form about `app\modules\appcake\models\DownloadWeek`.
 */
class DownloadWeek extends DownloadWeekModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id','camp_id'], 'integer'],
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
        $query = DownloadWeekModel::find();

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

        $this->isAd = isset($_GET['DownloadWeek']['isAd']) ? $_GET['DownloadWeek']['isAd'] : 0;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'app_id' => $this->app_id,
            'camp_id' => $this->camp_id,
            'cake_channel' => $this->cake_channel,
        ]);

        $query->andFilterWhere(['like', 'position', $this->position])
//            ->andFilterWhere(['like', 'idfa', $this->idfa])
//            ->andFilterWhere(['like', 'ip', $this->ip])
//            ->andFilterWhere(['like', 'device', $this->device])
            ->andFilterWhere(['like', 'app_id', $this->app_id])
            ->andFilterWhere(['like', 'camp_id', $this->camp_id])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['between', 'time', $this->startTime, $this->endTime]);

        if($this->isAd == 1){
            $query->andFilterWhere(['>', 'camp_id', 0]);
        }else if($this->isAd == 2){
            $query->andFilterWhere(['camp_id' =>  null ]);
        }

        $query->select('download_week.app_id,d.app_name,camp_id,country,category,position,idfa,count(*) as count')->groupBy('app_id')->orderBy("count desc");

        $query->leftJoin('app_data as d','d.app_id = download_week.app_id');

        return $dataProvider;
    }
}
