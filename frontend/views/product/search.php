<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '产品列表' . ' | ' . yii::$app->params['bandName'] . Yii::$app->params['suffix'];
?>
<?php
$bands  = $data['band'];
$series = $data['series'];
$cates  = $data['cate'];
$form   = ActiveForm::begin(['method' => 'get']);
echo $form->field($model, 'cate')->inline()->radioList($cates);
echo $form->field($model, 'band')->inline()->checkBoxList($bands);
echo $form->field($model, 'series')->inline()->radioList($series);
echo Html::submitButton('搜索', ['class' => 'btn btn-primary center-block']);
ActiveForm::end();
?>
<?php if (isset($data['body'])): ?>
<hr>
<div class="product">
<?php $k=0;foreach ($data['body'] as $key => $list): ++$k;?>
<div class="col-lg-2 col-md-2 col-sm-2 col-mx-2">
    <a href="<?=Url::to(['product/view', 'id' => $list['id']])?>">
        <img src="http://<?php echo $list['label_img']; ?>">
    </a>
    <table>
        <tr>
            <td class="red">￥<?=isset($list['price']) ? $list['price'] : 0.00?></td>
        </tr>
        <tr>
            <td>
                <a href="<?=Url::to(['product/view', 'id' => $list['id']])?>">
                    <?=$list['band_name']?> <?=$list['cate_name']?> <?=$list['type_name']?>
                </a>
            </td>
        </tr>
    </table>
</div>
<?php if ($k % 6 == 0): ?>
<div class="clear"></div>
<?php endif;
endforeach;?>
</div>
<div class="clear"></div>
<div class="pull-right"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
<?php endif;?>