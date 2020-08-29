$(document).ready(function() {
    //导航栏禁用点击下拉
    $(document).off('click.bs.dropdown.data-api');
    //购物车开始
    //商品数量加减
    $(".add").click(function() {
        var $t = $(this).prev();
        var $count = parseInt($t.val()) + 1;
        $t.val($count);
        var $obj = $(this);
        totalMoney($obj, $t, $count);
    })
    $(".minus").click(function() {
        var $t = $(this).next();
        var $count = parseInt($t.val()) - 1;
        $t.val($count);
        $obj = $(this);
        totalMoney($obj, $t, $count);
    })
    $(".text_box").keyup(function() {
        if ($(this).val() == '') {
            $(this).val('1');
        }
        if ($(this).val() > 1000) {
            alert('采购数量大于1000请直接联系我们！');
            $(this).val('1');
        }
        // 如果输入非数字或者0开头的替换为空，g代表多次匹配；禁用输入法；CTR+V事件处理
        $(this).val($(this).val().replace(/\D|^0/g, ''));
        var $count = $(this).val();
        var $t = $(this);
        totalMoney($t, $t, $count);
    }).css("ime-mode", "disabled").bind("paste", function() {
        $(this).val($(this).val().replace(/\D|^0/g, ''));
    });
    //如果没有选中任何商品，不允许结算
    $('input[name="settle"]').prop('disabled', true);
    //更改数量后根据数量算出总价后修改单件商品总价，然后修改结算总价
    function totalMoney($obj, $t, $count) {
        if ($count > 1) {
            $t.prev().attr('disabled', false);
        } else {
            $t.prev().attr('disabled', true); //禁用减按钮
        }
        var $priceObj = $obj.parent().next(); //td，不是span
        var $price = parseFloat($priceObj.find(".price").html());
        var $priceTotal = $count * $price;
        var $priceTotalObj = $priceObj.next().find(".total");
        var $oldpriceTotal = parseFloat($priceTotalObj.html());
        $priceTotalObj.html($priceTotal.toFixed(2));
        var $checkbox = $t.parent().prevAll(".check").children();
        var $finaltotalObj = $obj.parents("form").find(".finaltotal");
        var $oldpriceFinal = parseFloat($finaltotalObj.html());
        //原来未勾选，直接加上单件总价，原来勾选了减去原来的然后加上现在的单件总价
        if ($checkbox.prop("checked")) {
            $finaltotal = $oldpriceFinal - $oldpriceTotal + $priceTotal;
        } else {
            $checkbox.prop('checked', true);
            $finaltotal = $oldpriceFinal + $priceTotal;
        }
        $finaltotalObj.html($finaltotal.toFixed(2));
        if ($finaltotal == 0) {
            $('input[name="settle"]').prop('disabled', true);
        } else {
            $('input[name="settle"]').prop('disabled', false);
        }
    }
    //商品全选，商品未选中去掉全选并修改价格
    $(".cart-box .selectall").click(function() {
        $(this).parent().parent().siblings().find('input[type="checkbox"]').prop("checked", this.checked);
        var $finaltotal = 0;
        var $finaltotalObj = $(this).parents("form").find(".finaltotal");
        if (this.checked) {
            $(this).parent().parent().siblings().find('input[type="checkbox"]').each(function(i, o) {
                var $priceTotal = parseFloat($(o).parents("tr").find('.total').html());
                $finaltotal += $priceTotal;
            });
        }
        $finaltotalObj.html($finaltotal.toFixed(2));
        if ($finaltotal == 0) {
            $('input[name="settle"]').prop('disabled', true);
        } else {
            $('input[name="settle"]').prop('disabled', false);
        }
    })
    //订单和购物车公用反选函数
    $(".selectinvert").click(function() {
        $(this).parents("form").children("table:first-child").find('td').children('input[type="checkbox"]').each(function(i, o) {　　　　　　
            $(o).prop("checked", !$(o).prop("checked"));　
            $(o).change();　　　
        });
    })
    $(".cart-box").find('td').children('input[type="checkbox"]').each(function(i, o) {
        $(o).change(function() {
            var $finaltotalObj = $(o).parents("form").find(".finaltotal");
            var $oldpriceFinal = parseFloat($finaltotalObj.html());
            var $priceTotal = parseFloat($(o).parents("tr").find(".total").html());
            if (!$(o).prop("checked")) {
                $(o).parents("table").find(".selectall").prop('checked', false);
                var $finaltotal = $oldpriceFinal - $priceTotal;
            }　
            else {
                var $finaltotal = $oldpriceFinal + $priceTotal;
            }
            $finaltotalObj.html($finaltotal.toFixed(2));
            if ($finaltotal == 0) {
                $('input[name="settle"]').prop('disabled', true);
            } else {
                $('input[name="settle"]').prop('disabled', false);
            }
        })　　　　　
    })
    //购物车结束
    //订单全选，订单未选中去掉全选并修改价格
    $(".order-box .selectall").click(function() {
        $(this).parent().parent().siblings().find('input[type="checkbox"]').prop("checked", this.checked);
        var $finaltotal = 0;
        var $finaltotalObj = $(this).parents("form").find(".finaltotal");
        if (this.checked) {
            $(this).parent().parent().siblings().find('input[type="checkbox"]').each(function(i, o) {
                var $priceTotal = parseFloat($(o).parents("tr").next().find('.total').html());
                $finaltotal += $priceTotal;
            });
        }
        $finaltotalObj.html($finaltotal.toFixed(2));
        if ($finaltotal == 0) {
            $('input[name="settle"]').prop('disabled', true);
        } else {
            $('input[name="settle"]').prop('disabled', false);
        }
    })
    $(".order-box").find('td').children('input[type="checkbox"]').each(function(i, o) {
        $(o).change(function() {
            var $finaltotalObj = $(o).parents("form").find(".finaltotal");
            var $oldpriceFinal = parseFloat($finaltotalObj.html());
            var $priceTotal = parseFloat($(o).parents("tr").next().find(".total").html());
            if (!$(o).prop("checked")) {
                $(o).parents("table").find(".selectall").prop('checked', false);
                var $finaltotal = $oldpriceFinal - $priceTotal;
            }　
            else {
                var $finaltotal = $oldpriceFinal + $priceTotal;
            }
            $finaltotalObj.html($finaltotal.toFixed(2));
            if ($finaltotal == 0) {
                $('input[name="settle"]').prop('disabled', true);
            } else {
                $('input[name="settle"]').prop('disabled', false);
            }
        })　　　　　
    })
    //导航字体颜色改变
    // $(".dropdown-submenu").hover(function() {
    //     $(this).find("span").css("color", 'black');
    // }, function() {
    //     $(this).find("span").css("color", 'white');
    // });
});
//动态切换背景图片
// (function() {
//     var bgCounter = 0,
//         bgimgs = ["http://yii6.com/shop_slide4.jpg", "http://yii6.com/shop_slide3.jpg", "http://yii6.com/shop_slide2.jpg", "http://yii6.com/shop_slide1.jpg"];
//     function changeBackground() {
//         bgCounter = (bgCounter + 1) % bgimgs.length;
//         $('.bg-banner').css('background', 'url(' + bgimgs[bgCounter] + ') no-repeat');
//         setTimeout(changeBackground, 3666);
//     }
//     changeBackground();
// })();