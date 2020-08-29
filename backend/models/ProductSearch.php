<?php

namespace backend\models;

use common\models\Product;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductSearch represents the model behind the search form about `common\models\Product`.
 */
class ProductSearch extends Product
{
    public function rules()
    {
        return [
            [['id', 'cate', 'band', 'type', 'sale'], 'integer'],
            [['cate_name', 'band_name', 'type_name', 'describe', 'label_img'], 'safe'],
            [['price'], 'number'],
        ];
    }
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    public function search($params)
    {
        $query = Product::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 'cate_name', $this->cate_name])
            ->andFilterWhere(['like', 'band_name', $this->band_name])
            ->andFilterWhere(['like', 'type_name', $this->type_name]);
        return $dataProvider;
    }
}
