<script type="text/javascript">
    jQuery.noConflict();
    
//    jQuery(document).on('click','.offer-title-suggestion',function(){
//        jQuery('#offer-title-suggestion').toggle();
//    });
//    
//    jQuery(document).on('click','.offer-description-suggestion',function(){
//        jQuery('#offer-description-suggestion').toggle();
//    });
//    
//    jQuery(document).on('click','.offer-headline-suggestion',function(){
//        jQuery('#offer-headline-suggestion').toggle();
//    });
//    
//    jQuery(document).on('click','.thank-you-suggestion',function(){
//        jQuery('#thank-you-suggestion').toggle();
//    });
    
    localStorage.removeItem('updateClick');

    function clickUpdate(val) {
        localStorage.setItem('updateClick', val);
    }

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
                            bindTriggerProducts(result.prerequisite_product_ids, 1,'',result.prerequisite_variant_ids);
                        });
                        jQuery.each(data.bundle_product, function (key, result) {
                            bindBundleProducts(result.entitled_product_ids, 1,'',result.entitled_variant_ids);
                        });
                    }
                    jQuery(".page-loader").hide();
                }
            });
        } else {
            jQuery('#discount_goal_div').css('display', 'block');
        }
    });

    function bindTriggerProducts(product_id, is_disable, object,variantId) {
        data = jQuery('.copy-prod-' + product_id).html();
        rem_class = 'remove-prod-' + product_id;
        jQuery('.selected-items').append('<div class="sel-prods ' + rem_class + '">' + data + '</div>');

        //Disables the product
        jQuery('.' + rem_class).find('button').addClass('btn-remove').removeClass('btn-add');
        jQuery('.' + rem_class).find('button').addClass('rem-pop-product').removeClass('copy-pop-product');
        jQuery('.' + rem_class).find('button').html('Delete');
        if (is_disable == 1) {
            jQuery('.' + rem_class).find('button').attr('disabled', true);
            jQuery('.trig-' + product_id).attr('disabled', true);
        } else {
            jQuery(object).attr('disabled', true);
        }
        //jQuery('.bundle-' + prod_id).attr('disabled', true);
        var target_product = jQuery('#trigger_product').val();
        if (target_product != '') {
            target_product = target_product.split(',');
            target_product.push(product_id);
            target_product = target_product.join(',');
        } else {
            target_product = product_id;
        }
        var target_variant = jQuery('#trigger_product_variant').val();
        var variant = jQuery('#sel_variant_' + product_id + ' :selected').val();
        if(typeof variantId != 'undefined'){
            variant = variantId;
            console.log(variant);
        }else if (typeof variant == 'undefined') {
            variant = jQuery('#sel_variant_' + product_id).val();
        }
        if (target_variant != '') {
            target_variant = target_variant.split(',');
            target_variant.push(variant + '|' + product_id);
            target_variant = target_variant.join(',');
        } else {
            target_variant = variant + '|' + product_id;
        }
        jQuery('#trigger_product').val(target_product);
        jQuery('#trigger_product_variant').val(target_variant);
    }

    function bindBundleProducts(product_id, is_disable, object,variantId) {
        data = jQuery('.copy-bundle-prod-' + product_id).html();
        rem_class = 'remove-bundle-' + product_id;
        jQuery('.selected-trigger-items').append('<div class="trigger-prods ' + rem_class + '">' + data + '</div>');
        jQuery('.' + rem_class).find('button').addClass('btn-remove').removeClass('btn-add');
        jQuery('.' + rem_class).find('button').addClass('rem-bundle-product').removeClass('copy-bundle-product');
        jQuery('.' + rem_class).find('button').html('Delete');

        if (is_disable == 1) {
            jQuery('.' + rem_class).find('button').attr('disabled', true);
            jQuery('.bundle-' + product_id).attr('disabled', true);
        } else {
            jQuery(object).attr('disabled', true);
        }

        var target_product = jQuery('#bundle_product').val();
        if (target_product != '') {
            target_product = target_product.split(',');
            target_product.push(product_id);
            target_product = target_product.join(',');
        } else {
            target_product = product_id;
        }
        var target_variant = jQuery('#bundle_product_variant').val();
        var variant = jQuery('#trigger_variant_' + product_id + ' :selected').val();
        if(typeof variantId != 'undefined'){
            variant = variantId;
        }else if (typeof variant == 'undefined') {
            variant = jQuery('#trigger_variant_' + product_id).val();
        }
        if (target_variant != '') {
            target_variant = target_variant.split(',');
            target_variant.push(variant + '|' + product_id);
            target_variant = target_variant.join(',');
        } else {
            target_variant = variant + '|' + product_id;
        }
        jQuery('#bundle_product').val(target_product);
        jQuery('#bundle_product_variant').val(target_variant);
    }

    console.log("<?php 
        foreach($this->shop->get_shops() as $row) { 
            if ($row->shop_id == 0/* shop id frop jquery */) 
            return 'SHOP_ID';
        }; 
    ?>");
    // jQuery.ajax({
    //     type: 'GET',
    //     url: '/admin/api/2021-01/price_rules.json',
    //     success: function(data) {
    //         alert(data);
    //     },
    //     error: function(xhr, status, error){
    //         var errorMessage = xhr.status + ': ' + xhr.statusText
    //         alert('Error - ' + errorMessage);
    //     }
    // });

    jQuery(document).on("click", ".discount-type", function () {
        var val = jQuery(this).val();
        var id = jQuery('#id').val();
        var shop_id = jQuery('#shop_id').val();
        jQuery('.discount-options').css('display', 'none');
        jQuery('.discount-type').removeClass('active');
        jQuery('#discount_type').val(val);
        jQuery('#offer_headline-error').remove();
        jQuery('#offer_headline').removeClass('error');
        jQuery('#discount_goal_amount-error').remove();
        jQuery('#discount_goal_amount').removeClass('error');
        jQuery('#discount_text-error').remove();
        jQuery('#discount_text').removeClass('error');
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
                    jQuery('#discount_text_label').html('Successfully added item text<span class="asterisk ">*</span>');
                    jQuery('.discount-options').css('display', 'none');
                } else {
                    jQuery('#discount_text_label').html('Success discount Text<span class="asterisk ">*</span>');
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
                                bindBundleProducts(result.entitled_product_ids, 1);
                            });
                        }
                    }
                }
                jQuery("#discount_id").append(ddContent);
                if (id != '') {
                    var type = '<?php echo isset($bundleData->discount_type) ? $bundleData->discount_type : 0 ?>';
                    if (type == val) {
                        var discount_goal_amount = '<?php echo isset($bundleData->discount_goal_amount) ? floatval($bundleData->discount_goal_amount) : 0 ?>';
                        var offer_headline = '<?php echo isset($bundleData->offer_headline) ? $bundleData->offer_headline : '' ?>';
                        var discount_text = '<?php echo isset($bundleData->discount_text) ? $bundleData->discount_text : 0 ?>';
                        var selected_discount = jQuery('#selected_discount_code').data('selected_discount_code');
                        var selected_ary = selected_discount.split('|');
                        jQuery('#discount_goal_amount').val(discount_goal_amount);
                        jQuery('#offer_headline').val(offer_headline);
                        jQuery('#discount_id').val(selected_discount);
                        jQuery('select[id^="discount_id"] option[value="' + selected_discount + '"]').attr("selected", "selected");
                        jQuery('#discount_text').val(discount_text);
                        if (type == 1)
                            activePanel(selected_ary[2], type);
                    } else {
                        jQuery('#discount_goal_amount').val('');
                        jQuery('#discount_text').val('');
                        jQuery('#offer_headline').val('');
                    }
                } else {
                    jQuery('#discount_goal_amount').val('');
                    jQuery('#discount_text').val('');
                    jQuery('#offer_headline').val('');
                }
                jQuery(".page-loader").hide();
            }
        });
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

    jQuery(document).on("change", ".trigger_variant", function () {
        var proId = jQuery('option:selected', this).attr('pro_id');
        var selectedValue = jQuery('option:selected', this).val();
        var price = jQuery('option:selected', this).attr('price');
        jQuery('.trigger-price-pro-' + proId).html(price);
        changeVariants('bundle_product_variant', proId, selectedValue);
    });

    jQuery(document).on("change", ".sel_variant", function () {
        var proId = jQuery('option:selected', this).attr('pro_id');
        var selectedValue = jQuery('option:selected', this).val();
        var price = jQuery('option:selected', this).attr('price');
        jQuery('.price-pro-' + proId).html(price);
        changeVariants('trigger_product_variant', proId, selectedValue);
    });

    function changeVariants(variantId, productId, selectedValue) {
        var bundleVariant = jQuery('#' + variantId).val();
        if (bundleVariant != '') {
            bundleVariant = bundleVariant.split(',');
            jQuery.each(bundleVariant, function (key, value) {
                var node = value.split('|');
                if (node[1] == productId) {
                    bundleVariant[key] = selectedValue + '|' + productId;
                }
            });
            bundleVariant = bundleVariant.join(',');
            jQuery('#' + variantId).val(bundleVariant);
        }
    }

    jQuery(document).on("click", ".search-trigger-product", function () {
        var shop_id = jQuery('#shop_id').val();
        var keyword = jQuery('#search_trigger_product').val();
        var category = jQuery('#triggered_category').val();
        var target_product = jQuery('#trigger_product').val();
        var another_selected = jQuery('#bundle_product').val();
        var data = {'type': 'trigger', 'category': category, 'keyword': keyword, 'shop_id': shop_id, 'selected': target_product, 'another_selected': another_selected};
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

    jQuery(document).on("click", ".search-bundle-product", function () {
        var shop_id = jQuery('#shop_id').val();
        var keyword = jQuery('#search_bundle_product').val();
        var category = jQuery('#bundle_category').val();
        var target_product = jQuery('#bundle_product').val();
        var another_selected = jQuery('#trigger_product').val();
        var data = {'type': 'target', 'category': category, 'keyword': keyword, 'shop_id': shop_id, 'selected': target_product, 'another_selected': another_selected};
        jQuery(".page-loader").show();
        jQuery.ajax({
            type: 'POST',
            url: '<?= site_url('product/search_by_keyword') ?>',
            data: data,
            dataType: 'json',
            success: function (data) {
                if (data.content != '') {
                    jQuery("#triggered-products").html(data.content);
                }
                jQuery(".page-loader").hide();
            }
        });
    });

    jQuery(document).on('click', '.qty-change', function (e) {
        var field = jQuery(this).data('field');
        var type = jQuery(this).data('type');

        var currentVal = parseInt(jQuery('#' + field).val());
        var input = jQuery('#' + field);
        if (!isNaN(currentVal)) {
            if ((currentVal <= 1) && type == 'minus') {
                if(field!='min_qty'){
                    input.val(0);
                }
                jQuery('#max_qty').removeClass('error');
                jQuery('#max_qty-error').remove();
                return true;
            } else {
                var qtyVal = 0;
                if (type == 'minus') {
                    qtyVal = currentVal - 1;
                    input.val(qtyVal);
                } else if (type == 'plus') {
                    qtyVal = currentVal + 1;
                    input.val(qtyVal);
                }
                if (field == 'min_qty' && type == 'plus') {
                    //jQuery('#max_qty').val(qtyVal);
                }
                var result = checkQty();
                if (!result) {
                    jQuery('#max_qty').removeClass('error');
                    jQuery('#max_qty-error').remove();
                    jQuery('#max_qty').addClass('error');
                    jQuery('<label id="max_qty-error" class="error" for="max_qty">Max Qty cannot be less than Min Qty.</label>').insertAfter('#max_qty');
                } else {
                    jQuery('#max_qty').removeClass('error');
                    jQuery('#max_qty-error').remove();
                }
            }
        } else {
            input.val(0);
        }
    });

    jQuery(document).on('click', '.copy-pop-product', function (e) {
        prod_id = jQuery(this).attr('data-productid');
        bindTriggerProducts(prod_id, 0, this);
    });

    jQuery(document).on('click', '.rem-pop-product', function (e) {
        prod_id = jQuery(this).attr('data-productid');
        rem_class = 'remove-prod-' + prod_id;
        var target_product = jQuery('#trigger_product').val();
        var target_product_variant = jQuery('#trigger_product_variant').val();
        var variant = '';
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
                    jQuery('.copy-prod-' + prod_id + ' .copy-pop-product').attr('disabled', false);
                    if (target_product != '') {
                        var ary = [];
                        target_product = target_product.split(',');
                        var target_product_ary = target_product_variant.split(',');
                        jQuery.each(target_product_ary, function (key, value) {
                            var v = value.split('|');
                            b = {id: v[1], variant: v[0]};
                            ary.push(b);
                        });
                        var data = jQuery.grep(ary, function (e) {
                            return e.id != prod_id;
                        });
                        var vari = [];
                        jQuery.each(data, function (key, value) {
                            vari.push(value.variant + '|' + value.id);
                        });
                        variant = vari.join(',');
                        target_product = jQuery.grep(target_product, function (value) {
                            return value != prod_id;
                        });
                        target_product = target_product.join(',');
                    }
                    jQuery('#trigger_product').val(target_product);
                    jQuery('#trigger_product_variant').val(variant);
                }
            }
        });
    });

    jQuery(document).on('click', '.copy-bundle-product', function (e) {
        prod_id = jQuery(this).attr('data-productid');
        bindBundleProducts(prod_id, 0, this);
    });

    jQuery(document).on('click', '.rem-bundle-product', function (e) {
        prod_id = jQuery(this).attr('data-productid');
        rem_class = 'remove-bundle-' + prod_id;
        var target_product = jQuery('#bundle_product').val();
        var target_product_variant = jQuery('#bundle_product_variant').val();
        var variant = '';
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

                    if (target_product != '') {
                        var ary = [];
                        target_product = target_product.split(',');
                        var target_product_ary = target_product_variant.split(',');
                        jQuery.each(target_product_ary, function (key, value) {
                            var v = value.split('|');
                            b = {id: v[1], variant: v[0]};
                            ary.push(b);
                        });
                        var data = jQuery.grep(ary, function (e) {
                            return e.id != prod_id;
                        });
                        var vari = [];
                        jQuery.each(data, function (key, value) {
                            vari.push(value.variant + '|' + value.id);
                        });
                        variant = vari.join(',');
                        target_product = jQuery.grep(target_product, function (value) {
                            return value != prod_id;
                        });
                        target_product = target_product.join(',');
                    }
                    jQuery('#bundle_product').val(target_product);
                    jQuery('#bundle_product_variant').val(variant);
                }
            }
        });
    });

    jQuery('#bundle-form').validate({
        keyup: false,
        rules: {
            bundle_label: {
                required: true,
            },
            bundle_title: {
                required: true
            },
            end_date: {
                date_validate: true,
            },
            min_qty: {
                digits: true,
            },
            max_qty: {
                digits: true,
                qty_check: true,
            },
            discount_text: {
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
            /*discount_goal_amount: {
                required: function (element) {
                    var radioValue = jQuery("#discount_type").val();
                    if (radioValue != 0) {
                        return (radioValue == '') ? false : true;
                    } else {
                        return true;
                    }
                },
            },*/

        },
        messages: {
            bundle_label: {
                required: "This field cannot be blank.",
            },
            bundle_title: {
                required: "This field cannot be blank."
            },
            discount_text: {
                number: "This field cannot be blank."
            },
        },
        submitHandler: function (form) {
            var target_product = jQuery('#trigger_product').val();
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
            var discountType = jQuery("#discount_type").val();
            if ((discountType != 0) && (discountType != 3)) {
                var arr = [];
                var triggerArr = [];
                jQuery(".selected-trigger-items .trigger-prods .product-n-price").each(function () {
                    var price = jQuery(this).find("span").html();
                    var priceAry = price.split('(');
                    price = parseFloat(jQuery.trim(priceAry[0]));
                    arr.push(price);
                });
                jQuery(".selected-items .sel-prods .product-n-price").each(function () {
                    var price = jQuery(this).find("span").html();
                    var priceAry = price.split('(');
                    price = parseFloat(jQuery.trim(priceAry[0]));
                    triggerArr.push(price);
                });

                if (!jQuery.isEmptyObject(arr)) {
                    arr.sort(function (a, b) {
                        return parseInt(a) - parseInt(b);
                    });
                    triggerArr.sort(function (a, b) {
                        return parseInt(a) - parseInt(b);
                    });
                    var goal = parseFloat(jQuery('#discount_goal_amount').val());
                    var minBundlePrice = arr[0];
                    var minTriggerPrice = triggerArr[0];
//                    var totalSum = parseFloat((+minBundlePrice) + (+minTriggerPrice));
                    var totalSum = parseFloat(minTriggerPrice);
                    var checkamount = 1;
                    if (discountType == 1) {
                        var discount_id = jQuery('#discount_id').val();
                        discount_id = discount_id.split('|');
                        if (discount_id[2] == 'percentage')
                            checkamount = 0;
                        console.log('discountType = ' + discountType);
                    }
                    if (checkamount == 1) {
                        if(goal > 0){
                            if (goal < totalSum) {
                                jQuery('#snackbar').html('Amount spent from upsell cannot be less than minimum selected trigger product.');
                                var x = document.getElementById("snackbar");
                                x.className = "show";
                                setTimeout(function () {
                                    x.className = x.className.replace("show", "");
                                }, 10000);
                                isSubmit = 0;
                                return false;
                            }
                        }
                    }
                }
            }
            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('bundles/validate_trigger_products') ?>',
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
                url: '<?= site_url('bundles/insert') ?>',
                data: jQuery('#bundle-form').serialize(),
                dataType: 'json',
                success: function (data) {
                    jQuery('.next-btn').attr('disabled', false);
                    jQuery(".page-loader").hide();
                    console.log(data.status);
                    if (data.status == "success") {
                        var clickCheck = localStorage.getItem('updateClick');
                        if (clickCheck == 1) {
                            window.location.href = "<?= site_url('bundles/index/') ?>";
                        } else {
                            jQuery('#snackbar_success').html('Upsell Bundle updated successfully..!!');
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

    jQuery.validator.addMethod("qty_check", function (value, element) {
        var check = checkQty();
        return check;
    }, "Max Qty cannot be less than Min Qty.");
    jQuery.validator.addMethod("date_validate", function (value, element) {
        var result = checkDate();
        return result;
    }, "Start Date cannot be greater than End Date");

    jQuery('.datepicker').datepicker({
        format: 'mm-dd-yyyy',
        todayHighlight: true,
        changeMonth: true,
        changeYear: true,
        startDate: 'today',
        orientation: "bottom auto",
        autoclose: true,
    });
    jQuery("#start_date").datepicker({
        todayHighlight: true,
        startDate: '+1d',
        autoclose: true
    }).on('changeDate', function () {
        //var startDate = jQuery('#start_date').val();
        //jQuery('#end_date').val(startDate);
    });
    jQuery("#end_date").datepicker({
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function () {
        var result = checkDate();
        if (!result) {
            jQuery('#end_date').removeClass('error');
            jQuery('#end_date-error').remove();
            jQuery('#end_date').addClass('error');
            jQuery('<label id="end_date-error" class="error" for="end_date">Start Date cannot be greater than End Date</label>').insertAfter('#end_date');
        } else {
            jQuery('#end_date').removeClass('error');
            jQuery('#end_date-error').remove();
        }
    });

    function checkQty() {
        var min_qty = jQuery('#min_qty').val();
        var max_qty = jQuery('#max_qty').val();

        if (parseInt(max_qty) != 0 && max_qty != '' && min_qty != '') {
            console.log(parseInt(max_qty));
            if (parseInt(min_qty) > parseInt(max_qty)) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    function checkDate() {
        var s = jQuery('#start_date').val();
        var e = jQuery('#end_date').val();
        if (s != '' && e != '') {
            var e_match = e.split('-');
            var s_match = s.split('-');
            var startDate = new Date(s_match[2], s_match[0], s_match[1]);
            var endDate = new Date(e_match[2], e_match[0], e_match[1]);
            if (startDate <= endDate) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /***** Numeric float Key  - Sanjay Rathod -11-09-2017- *****/
    jQuery('form').on('keypress', '.float_number', function (event) {
        if (event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 9)
            return true;
        else if ((event.which != 46 || jQuery(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
            event.preventDefault();

    });
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
</script>