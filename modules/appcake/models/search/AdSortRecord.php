<?php

namespace app\modules\appcake\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\AdSortRecord as AdSortRecordModel;

/**
 * AdSortRecord represents the model behind the search form about `app\modules\appcake\models\AdSortRecord`.
 */
class AdSortRecord extends AdSortRecordModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort_id', 'position', 'current_sort', 'next_sort', 'sort_method', 'update_method'], 'integer'],
            [['country', 'is_updated', 'create_time', 'start_time'], 'safe'],
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
        $query = AdSortRecordModel::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'position' => $this->position,
            'current_sort' => $this->current_sort,
            'next_sort' => $this->next_sort,
            'sort_method' => $this->sort_method,
            'update_method' => $this->update_method,
            'create_time' => $this->create_time,
            'start_time' => $this->start_time,
        ]);

        $query->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'is_updated', $this->is_updated]);

        return $dataProvider;
    }
}
