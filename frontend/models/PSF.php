<?php
namespace frontend\models;

use common\models\Product;
use yii\data\Pagination;
use yii\base\Model;

class PSF extends Model
{
    public $cate;
    public $band;
    public $series;
    public $keyword;
    public function attributeLabels()
    {
        return [
            'cate' => '类别',
            'band' => '品牌',
            'series'=>'系列'
        ];
    }
    public function rules()
    {
        return [
            [['keyword'], 'string', 'max' => 16],
        ];
    }
    public function getCate($curPage, $condition, $pageSize = 16, $orderBy = ['sale' => SORT_DESC])
    {
        $res = Product::getCate($condition, $curPage,$pageSize,$orderBy);
        if (count($res['data']['data'])) {
            $result['body'] = $res['data']['data'];
        }
        $pages          = new Pagination(['totalCount' => $res['data']['count'], 'pageSize' => $res['data']['pageSize']]);
        $result['page'] = $pages;
        return $result;
    }
}
