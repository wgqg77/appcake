<?php

namespace app\modules\appcake\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\AppData as AppDataModel;

/**
 * AppData represents the model behind the search form about `app\modules\appcake\models\AppData`.
 */
class AppData extends AppDataModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'app_id', 'release_date', 'add_date', 'support_watch', 'download', 'week_download', 'need_backup', 'v_approved', 'stars', 'min_os_version', 'ipa'], 'integer'],
            [['app_name', 'version', 'category', 'vendor', 'size', 'icon', 'screenshot', 'screenshot2', 'screenshot3', 'screenshot4', 'screenshot5', 'ipadscreen1', 'ipadscreen2', 'ipadscreen3', 'ipadscreen4', 'ipadscreen5', 'watch_icon', 'watch_screen1', 'watch_screen2', 'watch_screen3', 'watch_screen4', 'watch_screen5', 'requirements', 'whatsnew', 'description', 'price', 'compatible', 'youtube_vid', 'v_poster', 'bundle_id', 'genres', 's3_key', 'bt_url', 'app_store_version'], 'safe'],
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
        $query = AppDataModel::find();

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
            'app_id' => $this->app_id,
            'release_date' => $this->release_date,
            'add_date' => $this->add_date,
            'support_watch' => $this->support_watch,
            'download' => $this->download,
            'week_download' => $this->week_download,
            'need_backup' => $this->need_backup,
            'v_approved' => $this->v_approved,
            'stars' => $this->stars,
            'min_os_version' => $this->min_os_version,
            'ipa' => $this->ipa,
        ]);

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'vendor', $this->vendor])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'screenshot', $this->screenshot])
            ->andFilterWhere(['like', 'screenshot2', $this->screenshot2])
            ->andFilterWhere(['like', 'screenshot3', $this->screenshot3])
            ->andFilterWhere(['like', 'screenshot4', $this->screenshot4])
            ->andFilterWhere(['like', 'screenshot5', $this->screenshot5])
            ->andFilterWhere(['like', 'ipadscreen1', $this->ipadscreen1])
            ->andFilterWhere(['like', 'ipadscreen2', $this->ipadscreen2])
            ->andFilterWhere(['like', 'ipadscreen3', $this->ipadscreen3])
            ->andFilterWhere(['like', 'ipadscreen4', $this->ipadscreen4])
            ->andFilterWhere(['like', 'ipadscreen5', $this->ipadscreen5])
            ->andFilterWhere(['like', 'watch_icon', $this->watch_icon])
            ->andFilterWhere(['like', 'watch_screen1', $this->watch_screen1])
            ->andFilterWhere(['like', 'watch_screen2', $this->watch_screen2])
            ->andFilterWhere(['like', 'watch_screen3', $this->watch_screen3])
            ->andFilterWhere(['like', 'watch_screen4', $this->watch_screen4])
            ->andFilterWhere(['like', 'watch_screen5', $this->watch_screen5])
            ->andFilterWhere(['like', 'requirements', $this->requirements])
            ->andFilterWhere(['like', 'whatsnew', $this->whatsnew])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'price', $this->price])
            ->andFilterWhere(['like', 'compatible', $this->compatible])
            ->andFilterWhere(['like', 'youtube_vid', $this->youtube_vid])
            ->andFilterWhere(['like', 'v_poster', $this->v_poster])
            ->andFilterWhere(['like', 'bundle_id', $this->bundle_id])
            ->andFilterWhere(['like', 'genres', $this->genres])
            ->andFilterWhere(['like', 's3_key', $this->s3_key])
            ->andFilterWhere(['like', 'bt_url', $this->bt_url])
            ->andFilterWhere(['like', 'app_store_version', $this->app_store_version]);

        return $dataProvider;
    }
}
