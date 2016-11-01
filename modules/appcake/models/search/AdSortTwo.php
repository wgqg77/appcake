<?php

namespace app\modules\appcake\models\search;

use app\modules\appcake\models\AdSortHistory;
use app\modules\appcake\models\AppData;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\AdSortTwo as AdSortTwoModel;


class AdSortTwo extends AdSortTwoModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'camp_id', 'app_id', 'position', 'source', 'current_sort', 'is_ad', 'next_sort'], 'integer'],
            [['country','last_sort','app_name'], 'safe'],
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
        $query = AdSortTwoModel::find()->alias('a');
//        $History = AdSortHistory::find()->select("count(*)")->asArray()->one();
//        if($History['count(*)'] > 0){
//            $query->joinWith(['adSortHistory h']);
//        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        if($this->country == false) $this->country = 'US';
        if($this->position == false) $this->position = 0;


        //初始化 无历史记录数据 不关联表
//        if($History['count(*)'] > 0){
//
//            // grid filtering conditions
//            $query->andFilterWhere([
//                'id' => $this->id,
//                'a.camp_id' => $this->camp_id,
//                'a.app_id' => $this->app_id,
//                'a.position' => $this->position,
//                'a.country' => strtoupper($this->country),
//                'a.source' => $this->source,
//                'a.current_sort' => $this->current_sort,
//                'a.is_ad' => $this->is_ad,
//                'next_sort' => $this->next_sort,
//                //'h.creat_time' => strtotime('2016-06-14 19:00:00')
//            ]);
//
//            //$query->andFilterWhere(['like', 'h.country', strtoupper($this->country)]);
//            //$query->andFilterWhere(['>', 'h.create_time', '2016-06-14 19:00:00']);
//
//            if(isset($_GET['AdSortOne']['app_name']) && !empty($_GET['AdSortOne']['app_name'])){
//                $this->app_name = $_GET['AdSortOne']['app_name'];
//                $app_id = AppData::find()->where(['like', 'app_name', $_GET['AdSortOne']['app_name']])->select('app_id')->asArray()->all();
//                $app_id = array_column($app_id,'app_id');
//                $query->andFilterWhere(['h.app_id' => $app_id]);
//            }
//
//        }else{
            $query->andFilterWhere([
                'id' => $this->id,
                'camp_id' => $this->camp_id,
                'app_id' => $this->app_id,
                'position' => $this->position,
                'country' => strtoupper($this->country),
                'source' => $this->source,
                'current_sort' => $this->current_sort,
                'is_ad' => $this->is_ad,
                'next_sort' => $this->next_sort,
                //'creat_time' => strtotime('2016-06-14 19:00:00')
            ]);

            //$query->andFilterWhere(['like', 'h.country', strtoupper($this->country)]);
            //$query->andFilterWhere(['>', 'h.create_time', '2016-06-14 19:00:00']);

            if(isset($_GET['AdSortOne']['app_name']) && !empty($_GET['AdSortOne']['app_name'])){
                $this->app_name = $_GET['AdSortOne']['app_name'];
                $app_id = AppData::find()->where(['like', 'app_name', $_GET['AdSortOne']['app_name']])->select('app_id')->asArray()->all();
                $app_id = array_column($app_id,'app_id');
                $query->andFilterWhere(['app_id' => $app_id]);
            }
//        }
        if( !isset($_GET['sort']) ){
            $query->orderBy('current_sort asc');
        }
        return $dataProvider;
    }

    public function setclick($value){
        $this->click = $value;
    }


    public function getclick(){
        if(isset($this->click)){
            return $this->click;
        }
    }

}
