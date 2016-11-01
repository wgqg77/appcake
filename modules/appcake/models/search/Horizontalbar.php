<?php

namespace app\modules\appcake\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\Horizontalbar as HorizontalbarModel;

/**
 * Horizontalbar represents the model behind the search form about `app\modules\appcake\models\Horizontalbar`.
 */
class Horizontalbar extends HorizontalbarModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id', 'special_id', 'appstore', 'rank', 'time'], 'integer'],
            [['country', 'category', 'img'], 'safe'],
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
        $query = HorizontalbarModel::find();

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

        if(!isset($_GET['Horizontalbar']['category'])){
            $this->category = 'other';
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'app_id' => $this->app_id,
            'special_id' => $this->special_id,
            'appstore' => $this->appstore,
            'rank' => $this->rank,
            'time' => $this->time,
        ]);

        $query->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'img', $this->img]);

        return $dataProvider;
    }
}
