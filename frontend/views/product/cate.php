<?php
use common\models\Category;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '产品列表' . ' | ' . yii::$app->params['bandName'] . Yii::$app->params['suffix'];
?>
<div class="form-group field-psf-cate">
    <label class="control-label">种类</label>
    <br>
<?php
$select = ['cat_name', 'id'];
$list   = Category::find()->select($select)->where('pid=2')->asArray()->all();
foreach ($list as $key => $value) {?>
    <a href="<?=Url::to(['product/cate', 'PSF[cate]' => $value['id']])?>" class="btn">
        <?=$value['cat_name']?>
    </a>
<?php }?>
</div>
<?php $list = Category::find()->select($select)->where('pid=3')->asArray()->all();
$bands      = [];
foreach ($list as $value) {
    $bands[$value['id']] = $value['cat_name'];
}
$form = ActiveForm::begin(['method' => 'get']);
echo $form->field($model, 'band')->inline()->checkBoxList($bands);
$list   = Category::find()->select($select)->where(['pid' => $model->cate])->asArray()->all();
$series = [];
foreach ($list as $value) {
    $series[$value['id']] = $value['cat_name'];
}
echo $form->field($model, 'series')->inline()->radioList($series);
// echo Html::submitButton('搜索', ['class' => 'btn btn-primary center-block']);

?>
<input type="submit" value="搜索" class="btn btn-primary center-block">
<?php ActiveForm::end();
$condition = yii::$app->request->get();
if (isset($data['body'])): ?>
<div>
    <span><a href="<?=Url::to(['product/cate', 'orderBy' => 1, 'condition' => $condition])?>">默认排序</a></span>&nbsp;&nbsp;
    <span><a href="<?=Url::to(['product/cate', 'orderBy' => 2, 'condition' => $condition])?>">销量从高到低</a></span>&nbsp;&nbsp;
    <span><a href="<?=Url::to(['product/cate', 'orderBy' => 3, 'condition' => $condition])?>">价格从低到高</a></span>
</div>
<hr>
<div class="product">
<?php $k = 0;foreach ($data['body'] as $key => $list): ++$k;?>
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