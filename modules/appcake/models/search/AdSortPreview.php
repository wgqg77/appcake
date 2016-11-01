<?php

namespace app\modules\appcake\models\search;

use app\modules\appcake\models\AdSortHistory;

use app\modules\appcake\models\AppData;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\AdSortPreview as AdSortPreviewModel;

/**
 * AdSortOne represents the model behind the search form about `app\modules\appcake\models\AdSortOne`.
 */
class AdSortPreview extends AdSortPreviewModel
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
        $query = AdSortPreviewModel::find();


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





        // grid filtering conditions
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
        if( !isset($_GET['sort']) ){
            $query->orderBy('current_sort asc');
        }

        return $dataProvider;
    }


}
