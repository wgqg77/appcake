<?php

namespace app\modules\appcake\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\Required as RequiredModel;

/**
 * Required represents the model behind the search form about `app\modules\appcake\models\Required`.
 */
class Required extends RequiredModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'rank', 'frank'], 'integer'],
            [['app_name', 'description', 'category', 'compatible', 'size', 'price'], 'safe'],
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
        $query = RequiredModel::find();

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
            'app_id' => $this->app_id,
            'rank' => $this->rank,
            'frank' => $this->frank,
        ]);

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'compatible', $this->compatible])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'price', $this->price]);

        return $dataProvider;
    }
}
