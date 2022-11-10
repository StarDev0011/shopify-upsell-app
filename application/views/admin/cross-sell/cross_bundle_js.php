<script type="text/javascript">
    jQuery.noConflict();
    localStorage.removeItem('updateClick');
    function clickUpdate(val) {
        localStorage.setItem('updateClick', val);
    }
    var crossSellCount = 0;
        
    function bindTargetProducts(product_id, is_disable, object) {
        data = jQuery('.copy-prod-' + product_id).html();
        rem_class = 'remove-prod-' + product_id;
        jQuery('.selected-items').append('<div class="sel-prods ' + rem_class + '">' + data + '</div>');
        
        //Disables the product
        jQuery('.' + rem_class).find('button').addClass('btn-remove').removeClass('btn-add');
        jQuery('.' + rem_class).find('button').addClass('rem-pop-product').removeClass('copy-target-product');
        jQuery('.' + rem_class).find('button').html('Delete');
        if (is_disable == 1) {
            jQuery('.' + rem_class).find('button').attr('disabled', true);
            jQuery('.trig-' + product_id).attr('disabled', true);
        } else {
            jQuery(object).attr('disabled', true);
        }
        //jQuery('.bundle-' + prod_id).attr('disabled', true);
        var target_product = jQuery('#target_product').val();
        if (target_product != '') {
            target_product = target_product.split(',');
            target_product.push(product_id);
            target_product = target_product.join(',');
        } else {
            target_product = product_id;
        }
        jQuery('#target_product').val(target_product);
    }
//
//    function bindBundleProducts(product_id, is_disable, object) {
//        data = jQuery('.copy-bundle-prod-' + product_id).html();
//        rem_class = 'remove-bundle-' + product_id;
//        jQuery('.selected-trigger-items').append('<div class="trigger-prods ' + rem_class + '">' + data + '</div>');
//        jQuery('.' + rem_class).find('button').addClass('btn-remove').removeClass('btn-add');
//        jQuery('.' + rem_class).find('button').addClass('rem-bundle-product').removeClass('copy-bundle-product');
//        jQuery('.' + rem_class).find('button').html('Delete');
//
//        if (is_disable == 1) {
//            jQuery('.' + rem_class).find('button').attr('disabled', true);
//            jQuery('.bundle-' + product_id).attr('disabled', true);
//        } else {
//            jQuery(object).attr('disabled', true);
//        }
//
//        var target_product = jQuery('#bundle_product').val();
//        if (target_product != '') {
//            target_product = target_product.split(',');
//            target_product.push(product_id);
//            target_product = target_product.join(',');
//        } else {
//            target_product = product_id;
//        }
//        jQuery('#bundle_product').val(target_product);
//    }
    
    jQuery(document).on("change", "#discount_id", function () {

        var discount_type = jQuery('#discount_type').val();
        if (discount_type == 3) {
            jQuery('#discount_goal_div').css('display', 'none');
            jQuery('.bundle-products-list').empty();
            jQuery('#target_product').val('');
            jQuery('#trigger_product').val('');
            jQuery('#trigger_product_variant').val('');
            jQuery('#bundle_product').val('');
            jQuery('#bundle_product_variant').val('');
            jQuery('.copy-pop-product').attr('disabled', false);
            jQuery('.copy-bundle-product').attr('disabled', false);
            jQuery(".page-loader").show();
            var shop_id = jQuery('#shop_id').val();
            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('bundles/get_discount_products') ?>',
                data: {id: jQuery(this).val(), shop_id: shop_id},
                dataType: 'json',
                success: function (data) {
                    if (data.status == 'success') {

                        jQuery.each(data.trigger_product, function (key, result) {
                            bindTargetProducts(result.prerequisite_product_ids, 1);
                        });
                        jQuery.each(data.bundle_product, function (key, result) {
                        console.log(result.entitled_product_ids);
//                            bindBundleProducts(result.entitled_product_ids, 1,'',result.entitled_variant_ids);
                            bindCrossSellProducts(result.entitled_product_ids, 1);
                        });
                    }
                    jQuery(".page-loader").hide();
                }
            });
        } else {
            jQuery('#discount_goal_div').css('display', 'block');
        }
    });
    
    jQuery(document).on("change", "#discount_id", function () {
        var val = jQuery(this).val();
        val = val.split('|');
        if (jQuery('#discount_type').val() == 1)
            activePanel(val[2]);
        jQuery('#discount_value').val(Math.abs(val[1]));
    });
    
    function activePanel(value) {
        if (value == 'percentage') {
            jQuery('.fixed-li').removeClass('active');
            jQuery('.per-li').addClass('active');
            jQuery('#discount_goal_amount').val(0);
            jQuery('#discount_goal_div').css('display', 'none');
        } else {
            jQuery('.per-li').removeClass('active');
            jQuery('.fixed-li').addClass('active');
            jQuery('#discount_goal_div').css('display', 'block');
        }
    }
    
    jQuery(document).on("click", ".discount-type", function () {
        var val = jQuery(this).val();
        var id = jQuery('#id').val();
        var shop_id = jQuery('#shop_id').val();
        jQuery('.discount-options').css('display', 'none');
        jQuery('.discount-type').removeClass('active');
        jQuery('#discount_type').val(val);
        jQuery('#offer_headline-error').remove();
        jQuery('#offer_headline').removeClass('error');
        jQuery('#discount_id-error').remove();
        jQuery('#discount_id').removeClass('error');
        jQuery(".page-loader").show();
        var selected_discount = jQuery('#selected_discount_code').data('selected_discount_code');
        jQuery.ajax({
            type: 'POST',
            url: '<?= site_url('bundles/get_discount_code') ?>',
            data: {type: val, shop_id: shop_id, id: selected_discount},
            dataType: 'json',
            success: function (data) {
                var ddContent = '<option value="">Please Select Discount Code</option>';
                jQuery("#discount_id").empty();
                jQuery('.type-' + val).addClass('active');
                if (val == 0) {
                    jQuery('#offer_headline_label').html('Offer headline<span class="asterisk ">*</span>');
                    jQuery('.discount-options').css('display', 'none');
                } else {
                    jQuery('#offer_headline_label').html('Offer headline<span class="asterisk ">*</span>');
                    console.log(val);

                    if (val == 3) {
                        jQuery('.rem-pop-product').attr('disabled', true);
                        jQuery('.rem-bundle-product').attr('disabled', true);
                        jQuery('.discount-val').hide();
                        jQuery('#discount_goal_div').css('display', 'none');
                    } else {
                        if (val == 1) {
                            jQuery('.discount-val').show();
                            jQuery('#discount_goal_div').css('display', 'none');
                        } else {
                            jQuery('.discount-val').hide();
                            jQuery('#discount_goal_div').css('display', 'block');
                        }
                        jQuery('.rem-pop-product').attr('disabled', false);
                        jQuery('.rem-bundle-product').attr('disabled', false);
                    }
                    jQuery('.discount-options').css('display', 'block');
                }
                if (data.status == 'success') {
                    jQuery.each(data.content, function (key, result) {
                        ddContent += '<option value="' + result.discount_id + '|' + result.value + '|' + result.value_type + '">' + result.title + '</option>';
                    });
                    if (data.is_bxgy == 1) {
                        if (!jQuery.isEmptyObject(data.trigger_product) && !jQuery.isEmptyObject(data.bundle_product)) {
                            jQuery('.bundle-products-list').empty();
                            jQuery('#target_product').val('');
                            jQuery('#trigger_product').val('');
                            jQuery('#trigger_product_variant').val('');
                            jQuery('#bundle_product').val('');
                            jQuery('#bundle_product_variant').val('');
                            jQuery.each(data.trigger_product, function (key, result) {
                                bindTriggerProducts(result.prerequisite_product_ids, 1);
                            });
                            jQuery.each(data.bundle_product, function (key, result) {
                                bindCrossSellProducts(result.entitled_product_ids, 1);
                            });
                        }
                    }
                }
                jQuery("#discount_id").append(ddContent);
                if (id != '') {
                    var type = '<?php echo isset($bundleData->discount_type) ? $bundleData->discount_type : 0 ?>';
                    if (type == val) {
                        var offer_headline = '<?php echo isset($bundleData->offer_headline) ? $bundleData->offer_headline : 0 ?>';
                        var selected_discount = jQuery('#selected_discount_code').data('selected_discount_code');
                        var selected_ary = selected_discount.split('|');
                        jQuery('#discount_id').val(selected_discount);
                        jQuery('select[id^="discount_id"] option[value="' + selected_discount + '"]').attr("selected", "selected");
                        jQuery('#offer_headline').val(offer_headline);
                        if (type == 1)
                            activePanel(selected_ary[2], type);
                    } else {
                        jQuery('#offer_headline').val('');
                    }
                } else {
                    jQuery('#offer_headline').val('');
                }
                jQuery(".page-loader").hide();
            }
        });
    });

    jQuery(document).on("click", ".search-trigger-product", function () {
        var shop_id = jQuery('#shop_id').val();
        var keyword = jQuery('#search_trigger_product').val();
        var category = jQuery('#triggered_category').val();
        var target_product = jQuery('#target_product').val();
        var another_selected = jQuery('#bundle_product').val();
        var data = {'type': 'target','bundle':1, 'category': category, 'keyword': keyword, 'shop_id': shop_id, 'selected': target_product, 'another_selected': another_selected};
        jQuery(".page-loader").show();
        jQuery.ajax({
            type: 'POST',
            url: '<?= site_url('product/search_by_keyword') ?>',
            data: data,
            dataType: 'json',
            success: function (data) {
                if (data.content != '') {
                    jQuery("#target-products").html(data.content);
                }
                jQuery(".page-loader").hide();
            }
        });
    });

    jQuery(document).on('click', '.copy-target-product', function (e) {
        prod_id = jQuery(this).attr('data-productid');
        var target = jQuery('#target_product').val();
        if(target!=''){
            jQuery('#snackbar').html('You can select only single product for target product.');
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function () {
                x.className = x.className.replace("show", "");
            }, 10000);
            return false;
        }
        bindTargetProducts(prod_id, 0, this);
    });
    
    jQuery(document).on('click', '.copy-cross-product', function (e) {
        prod_id = jQuery(this).attr('data-productid');
        bindCrossSellProducts(prod_id, 0, this);
    });
    
    function bindCrossSellProducts(product_id, is_disable, object) {
        
        var target_product = jQuery('#bundle_product').val();
        if (target_product != '') {
            target_product = target_product.split(',');
            crossSellCount = target_product.length;
            target_product.push(product_id);
            target_product = target_product.join(',');
        } else {
            target_product = product_id;
            crossSellCount = 1;
        }
        if(crossSellCount>=3){
            jQuery('#snackbar').html('You can add only three cross sell products.');
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function () {
                x.className = x.className.replace("show", "");
            }, 10000);
            return false;
        }
        console.log(crossSellCount);
        data = jQuery('.copy-prod-cross-sell-' + product_id).html();
        rem_class = 'remove-bundle-' + product_id;
        jQuery('.selected-trigger-items').append('<div class="' + rem_class + '">' + data + '</div>');

        //Disables the product
        jQuery('.' + rem_class).find('button').addClass('btn-remove').removeClass('btn-add');
        jQuery('.' + rem_class).find('button').addClass('rem-bundle-product').removeClass('copy-cross-product');
        jQuery('.' + rem_class).find('button').html('Delete');
        if (is_disable == 1) {
            jQuery('.' + rem_class).find('button').attr('disabled', true);
            jQuery('.trig-' + product_id).attr('disabled', true);
        } else {
            jQuery(object).attr('disabled', true);
        }
        //jQuery('.bundle-' + prod_id).attr('disabled', true);
        jQuery('#bundle_product').val(target_product);
    }
    
    jQuery(document).on('change', '#collection_id', function (e) {
        var shop_id = jQuery('#shop_id').val();
        var col = jQuery(this).val();
        if(col!=''){
        var data = {'type': 'cross-sell','bundle':1, 'category': col, 'keyword': '', 'shop_id': shop_id, 'selected': '', 'another_selected': ''};
        jQuery(".page-loader").show();
            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('product/search_by_keyword') ?>',
                data: data,
                dataType: 'json',
                success: function (data) {
                    if (data.content != '') {
                        jQuery(".selected-trigger-items").html(data.content);
                        jQuery("#bundle_product").val(data.prodIds);
                    }
                    jQuery(".page-loader").hide();
                }
            });
        }else{
            jQuery(".selected-trigger-items").empty();
            jQuery("#bundle_product").val('');
        }
    });

    jQuery(document).on('click', '.rem-pop-product', function (e) {
        prod_id = jQuery(this).attr('data-productid');
        rem_class = 'remove-prod-' + prod_id;
        var target_product = jQuery('#target_product').val();
        bootbox.confirm({
            message: 'Are you sure want to delete this product?',
            buttons: {
                confirm: {
                    label: 'Yes',
                },
                cancel: {
                    label: 'No',
                }
            },
            callback: function (result) {
                if (result)
                {
                    jQuery('.' + rem_class).remove();
                    jQuery('.copy-prod-' + prod_id + ' .copy-target-product').attr('disabled', false);
                    if (target_product != '') {
                        var ary = [];
                        target_product = target_product.split(',');
                        target_product = jQuery.grep(target_product, function (value) {
                            return value != prod_id;
                        });
                        target_product = target_product.join(',');
                    }
                    jQuery('#target_product').val(target_product);
                }
            }
        });
    });

//    jQuery(document).on('click', '.copy-bundle-product', function (e) {
//        prod_id = jQuery(this).attr('data-productid');
//        bindBundleProducts(prod_id, 0, this);
//    });

    jQuery(document).on('click', '.rem-bundle-product', function (e) {
        console.log(1);
        prod_id = jQuery(this).attr('data-productid');
        rem_class = 'remove-bundle-' + prod_id;
        var target_product = jQuery('#bundle_product').val();
        bootbox.confirm({
            message: 'Are you sure want to delete this product?',
            buttons: {
                confirm: {
                    label: 'Yes',
                },
                cancel: {
                    label: 'No',
                }
            },
            callback: function (result) {
                if (result)
                {
                    jQuery('.' + rem_class).remove();
                    jQuery('.copy-bundle-prod-' + prod_id + ' .copy-bundle-product').attr('disabled', false);
                    jQuery('.copy-prod-cross-sell-' + prod_id + ' .copy-cross-product').attr('disabled', false);
                    if (target_product != '') {
                        var ary = [];
                        target_product = target_product.split(',');
                        target_product = jQuery.grep(target_product, function (value) {
                            return value != prod_id;
                        });
                        target_product = target_product.join(',');
                    }
                    crossSellCount = crossSellCount - 1;
                    jQuery('#bundle_product').val(target_product);
                }
            }
        });
    });

    jQuery('#bundle-form').validate({
        keyup: false,
        rules: {
            bundle_title: {
                required: true,
            },
            offer_headline: {
                required: function (element) {
                    var radioValue = jQuery("#discount_type").val();
                    if (radioValue != 0) {
                        return (radioValue == '') ? false : true;
                    } else {
                        return true;
                    }
                }
            },
            discount_id: {
                required: function (element) {
                    var radioValue = jQuery("#discount_type").val();
                    if (radioValue != 0) {
                        return (radioValue == '') ? false : true;
                    } else {
                        return true;
                    }
                }
            },
            success_text: {
                required: function (element) {
                    var radioValue = jQuery("#discount_type").val();
                    if (radioValue != 0) {
                        return (radioValue == '') ? false : true;
                    } else {
                        return true;
                    }
                }
            },
        },
        messages: {
            bundle_label: {
                required: "This field cannot be blank.",
            },
            offer_headline: {
                number: "This field cannot be blank."
            }
        },
        submitHandler: function (form) {
            var target_product = jQuery('#target_product').val();
            var bundle_product = jQuery('#bundle_product').val();
            if (target_product == '' || bundle_product == '') {
                if (target_product == '') {
                    jQuery('#snackbar').html('Please add at least one product in Available Product');
                } else {
                    jQuery('#snackbar').html('Please add at least one product in Bundle Product.');
                }
                var x = document.getElementById("snackbar");
                x.className = "show";
                setTimeout(function () {
                    x.className = x.className.replace("show", "");
                }, 3000);
                return false;
            }
            
            var isSubmit = 1;
            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('cross_sell_bundle/validate_target_product') ?>',
                data: {trigger_product: target_product, id: jQuery('#id').val()},
                dataType: 'json',
                async: false,
                success: function (data) {
                    if (data.status == 'error') {
                        jQuery('#snackbar').html(data.msg);
                        var x = document.getElementById("snackbar");
                        x.className = "show";
                        setTimeout(function () {
                            x.className = x.className.replace("show", "");
                        }, 10000);
                        isSubmit = 0;
                    }
                }
            });
            
            if (isSubmit == 0)
                return false;
            
            jQuery('.next-btn').attr('disabled', true);
            jQuery(".page-loader").show();
            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('cross_sell_bundle/insert') ?>',
                data: jQuery('#bundle-form').serialize(),
                dataType: 'json',
                success: function (data) {
                    jQuery('.next-btn').attr('disabled', false);
                    jQuery(".page-loader").hide();
                    console.log(data.status);
                    if (data.status == "success") {
                        var clickCheck = localStorage.getItem('updateClick');
                        if (clickCheck == 1) {
                            window.location.href = "<?= site_url('cross_sell_bundle/index/') ?>";
                        } else {
                            jQuery('#snackbar_success').html('Cross Sell Bundle updated successfully..!!');
                            var x = document.getElementById("snackbar_success");
                            x.className = "show";
                            setTimeout(function () {
                                x.className = x.className.replace("show", "");
                            }, 3000);
                        }
                    } else {
                        jQuery('#snackbar').html(data.msg);
                        var x = document.getElementById("snackbar");
                        x.className = "show";
                        setTimeout(function () {
                            x.className = x.className.replace("show", "");
                        }, 10000);
                    }
                }
            });
        }
    });

    jQuery('#bundle-form').on('submit', function (e) {
        e.preventDefault();
    });

</script>