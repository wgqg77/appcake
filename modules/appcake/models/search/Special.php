<?php

namespace app\modules\appcake\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\Special as SpecialModel;

/**
 * Special represents the model behind the search form about `app\modules\appcake\models\Special`.
 */
class Special extends SpecialModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'addtime'], 'integer'],
            [['name', 'img', 'description', 'arr_appid', 'category', 'compatible'], 'safe'],
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
        $query = SpecialModel::find();

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
            'sid' => $this->sid,
            'addtime' => $this->addtime,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'img', $this->img])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'arr_appid', $this->arr_appid])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'compatible', $this->compatible]);

        return $dataProvider;
    }
}
