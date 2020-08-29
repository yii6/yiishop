<?php
namespace common\widgets\banner;

use yii\base\Widget;

class BannerWidget extends Widget
{

    public $items = [];
    public function init()
    {
        if (empty($this->items)) {
            $this->items = [
                [
                    'label'  => '轴承汇', 'image_url' => 'http://yii6.com/shop_slide1',
                    'active' => 'active',
                ],
                ['label' => '狗年吉祥', 'image_url' => 'http://yii6.com/shop_slide4.jpg'],
                ['label' => '轴承一站式采购平台', 'image_url' => 'http://yii6.com/shop_slide_1'],
            ];
        }
    }
    public function run()
    {
        $data['items'] = $this->items;
        return $this->render('index', ['data' => $data]);
    }
}
