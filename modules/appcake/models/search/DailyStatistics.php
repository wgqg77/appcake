<?php

namespace app\modules\appcake\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\DailyStatistics as DailyStatisticsModel;

/**
 * DailyStatistics represents the model behind the search form about `app\modules\appcake\models\DailyStatistics`.
 */
class DailyStatistics extends DailyStatisticsModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cake_new_user', 'cake_active_user', 'cake_download', 'hook_new_user', 'hook_active_user', 'hook_download', 'hook_ad_no_repeat', 'hook_activation', 'all_download', 'all_activation'], 'integer'],
            [['date'], 'safe'],
            [['cake_activation', 'cake_active_down', 'all_income', 'a_price'], 'number'],
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
        $query = DailyStatisticsModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

//        $resquest = Yii::$app->request;
//        $this->startTime = $resquest->get('startTime');
//        $this->endTime =  $resquest->get('endTime');
        $this->startTime = isset($_GET['startTime']) && !empty($_GET['startTime']) ? $_GET['startTime'] : date("Y-m-d",strtotime('-1 day'));
        $this->endTime = isset($_GET['endTime']) && !empty($_GET['endTime']) ? $_GET['endTime'] : date("Y-m-d",time()) ;

//        $this->startTime = empty($this->startTime) ? date("Y-m-d",strtotime('-1 day')) : $this->startTime;
//        $this->endTime = empty($this->endTime) ? date("Y-m-d",time()) : $this->endTime;


        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'date' => $this->date,
//            'cake_new_user' => $this->cake_new_user,
//            'cake_active_user' => $this->cake_active_user,
//            'cake_activation' => $this->cake_activation,
//            'cake_download' => $this->cake_download,
//            'cake_active_down' => $this->cake_active_down,
//            'hook_new_user' => $this->hook_new_user,
//            'hook_active_user' => $this->hook_active_user,
//            'hook_download' => $this->hook_download,
//            'hook_ad_no_repeat' => $this->hook_ad_no_repeat,
//            'hook_activation' => $this->hook_activation,
//            'all_download' => $this->all_download,
//            'all_activation' => $this->all_activation,
//            'all_income' => $this->all_income,
//            'a_price' => $this->a_price,
//        ]);
        $query->andFilterWhere(['between', 'date', $this->startTime, $this->endTime]);

        return $dataProvider;
    }
}
