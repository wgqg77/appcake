<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Admin as AdminModel;

/**
 * Admin represents the model behind the search form about `app\modules\admin\models\Admin`.
 */
class Admin extends AdminModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'create_time', 'login_time', 'safe_question', 'scores', 'group', 'coin', 'is_lock', 'is_admin', 'login_count'], 'integer'],
            [['user_name', 'passwd', 'sex', 'email', 'phone', 'create_ip', 'login_ip', 'safe_answer', 'face', 'nick_name', 'collect'], 'safe'],
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
        $query = AdminModel::find();

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
            'uid' => $this->uid,
            'create_time' => $this->create_time,
            'login_time' => $this->login_time,
            'safe_question' => $this->safe_question,
            'scores' => $this->scores,
            'group' => $this->group,
            'coin' => $this->coin,
            'is_lock' => $this->is_lock,
            'is_admin' => $this->is_admin,
            'login_count' => $this->login_count,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'passwd', $this->passwd])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'create_ip', $this->create_ip])
            ->andFilterWhere(['like', 'login_ip', $this->login_ip])
            ->andFilterWhere(['like', 'safe_answer', $this->safe_answer])
            ->andFilterWhere(['like', 'face', $this->face])
            ->andFilterWhere(['like', 'nick_name', $this->nick_name])
            ->andFilterWhere(['like', 'collect', $this->collect]);

        return $dataProvider;
    }
}
