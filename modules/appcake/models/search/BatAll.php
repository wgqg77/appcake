<?php

namespace app\modules\appcake\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\BatAll as BatAllModel;

/**
 * BatAll represents the model behind the search form about `app\modules\appcake\models\BatAll`.
 */
class BatAll extends BatAllModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['camp_id', 'source', 'mobile_app_id', 'installs', 'dl_type', 'preload', 'start_time', 'end_time'], 'integer'],
            [['origin_camp_id', 'creatives', 'imp_url', 'click_url', 'click_callback_url', 'payout_currency', 'acquisition_flow', 'icon_gp', 'description', 'name', 'category', 'nocountries', 'countries'], 'safe'],
            [['payout_amount', 'rate', 'store_rating'], 'number'],
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
        $query = BatAllModel::find();

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
            'camp_id' => $this->camp_id,
            'source' => $this->source,
            'mobile_app_id' => $this->mobile_app_id,
            'payout_amount' => $this->payout_amount,
            'rate' => $this->rate,
            'store_rating' => $this->store_rating,
            'installs' => $this->installs,
            'dl_type' => $this->dl_type,
            'preload' => $this->preload,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);

        $query->andFilterWhere(['like', 'origin_camp_id', $this->origin_camp_id])
            ->andFilterWhere(['like', 'creatives', $this->creatives])
            ->andFilterWhere(['like', 'imp_url', $this->imp_url])
            ->andFilterWhere(['like', 'click_url', $this->click_url])
            ->andFilterWhere(['like', 'click_callback_url', $this->click_callback_url])
            ->andFilterWhere(['like', 'payout_currency', $this->payout_currency])
            ->andFilterWhere(['like', 'acquisition_flow', $this->acquisition_flow])
            ->andFilterWhere(['like', 'icon_gp', $this->icon_gp])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'nocountries', $this->nocountries])
            ->andFilterWhere(['like', 'countries', $this->countries]);

        return $dataProvider;
    }
}
