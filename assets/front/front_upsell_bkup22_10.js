base_url = 'https://localhost/tops-upsell/';
localStorage.removeItem('showpop');
localStorage.removeItem('variant');
localStorage.removeItem('finalClick');
url = window.location.href;
domain = extractHostname(url);
prodSlug = extractProductSlug(url);
var shopData = $('#shopify-features').html();
var obj = JSON.parse(shopData);
var shopId = obj.shopId;
var bundle = [];
var noOfBundle = 0;
var isPopUp = 1;
var isSkipNext = 1;
if (prodSlug != '' && prodSlug != 'all') {
    var variant_id = getCurrentProductID(prodSlug);
}

//var variantId = jQuery('select[name="id"]').val();
var variantId = $("[name='id']").val();
localStorage.setItem('variant', variantId);

cart_items = getCurrentCartItems();

jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', true);

jQuery(window).load(function () {
    jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', false);
});

/**
 * Add to cart functionality without A+B+C
 */
jQuery(document).on('click', '.add-this-cart-upsells', function (e) {
    var prod_id = jQuery(this).attr('prod-id');
    var prod_price = jQuery(this).attr('prod_price');
    var popup_bundle_id = jQuery('#popup_bundle_id').val();

    
    var idMultipleProducts = jQuery('#slider .sp').length;
    //console.log(idMultipleProducts);
    if (idMultipleProducts > 1) {
        jQuery(this).parents('.sp').removeClass('active');
        if (jQuery(this).parents('.sp').next().length < 1) {
            jQuery(this).parents('#slider').find('.sp:first').addClass('active');
        } else {
            jQuery(this).parents('.sp').next().addClass('active');
        }
        product_id = jQuery('.sp.active').attr('prod-id');
        jQuery('.add-this-cart-upsells').attr('prod-id', product_id);
        jQuery("#pr_id").val(product_id);
        jQuery(".col-item").removeClass("fadeInLeft")
        jQuery(".col-item").addClass("fadeInLeft");
    }
    setTimeout(function(){ 
       
        //console.log(idMultipleProducts);
        if (idMultipleProducts > 1) {
            jQuery('.sp').hide();
            
            jQuery('.active').show().animate({"margin-right": '-=200'});
            jQuery('.active').css('display','block');
        }
    }, 2000);
    jQuery(this).attr('disabled', true);
    add_cart_log(prod_id, popup_bundle_id, prod_price);
    addItem(prod_id, idMultipleProducts);
});



/**
 * used when any variant changed then calculate price and display
 */
jQuery(document).on('change', '.variant_options', function (e) {
    var totalPrice = calculateTotalAmount();
    jQuery("#total_upsell_price").text(totalPrice);
});

/**
 * Used for add to cart in A+B+C
 */
jQuery(document).on('click', ".add-upsell-cart", function (e) {
    e.preventDefault();
    addUpsellBundle(e);
});

/**
 * Used when any user click on Trigger product's add to cart button. Will show popup if bundle is created.
 * If variant is changed by user this function will check and shows popup
 */
jQuery(document).on('click', '[name="add"]', function (e) {
    var variantId = $("[name='id']").val();
    var localStorageVariant = localStorage.getItem('variant');
    var result = 1;
    //this will check  if variant is changed then fetch the popup data
    if ((localStorageVariant != variantId)) {
        result = getPOPUpProducts(shopId, cart_items, cart_price, cart_token);
        localStorage.setItem('showpop', '');
    }

    var finalClick = localStorage.getItem('finalClick');
    var pId = jQuery('#pr_id').val();

    if (noOfBundle > 1) {
        jQuery('.sp').removeClass('active');
        jQuery('#p_' + pId).addClass('active');
        jQuery('.sp').css('display', 'none');
        jQuery('#p_' + pId).css('display', 'block');
    }
    if (finalClick != 1) {
        //show popup when popup called    
        if ((localStorage.getItem('showpop') != 'shown') && (jQuery('.sp').length > 0) && (result == 1) && isPopUp == 1) {
            if (jQuery('.bundle_id').val()) {
                updateBundleView(jQuery('.bundle_id').val());
            }

            localStorage.setItem('showpop', 'shown');
            localStorage.setItem('variant', variantId);
            e.preventDefault(); // disable normal link function so that it doesn't refresh the page
            var docHeight = jQuery(document).height(); //grab the height of the page
            var scrollTop = jQuery(window).scrollTop(); //grab the px value from the top of the page to where you're scrolling
            jQuery('.overlay-bg').show().css({'height': docHeight}); //display your popup and set height to the page height
            jQuery('.overlay-bg').css('display', 'block');
            jQuery('.overlay-content').css({'top': scrollTop + 20 + 'px'}); //set the content 20px from the window top
            return false;
        }
    }
});

/**
 * Calculate total amount of bundle products with qty nd price
 * @return {Number}
 */
function calculateTotalAmount() {
    var totalPrice = 0;
    var mprice = jQuery("#mprice").val();
    var quantity = 1;
    jQuery(".upsell-product").each(function (index, event) {
        if (index != 0) {
            var price = $('option:selected', this).attr('price');
            totalPrice += parseFloat(price);
        }
    });
    totalPrice = totalPrice + parseFloat(mprice);
    if (isSkipNext == 0) {
        var totalQty = jQuery('#add_multiple_quantity').val();
        if (typeof totalQty != 'undefined')
            var totalPrice = totalPrice * totalQty;
    }
    return totalPrice;
}

/**
 * Add upsell products into array and add bundle view log
 * @param {type} e
 * @return {undefined}
 */
function addUpsellBundle(e) {
    e.preventDefault();
    var upsellBundleItems = [];
    var oldBundle = '';
    jQuery(".add-upsell-cart").attr('disabled', true);
    var quantity = ($("#add_multiple_quantity").length != 0) ? jQuery('#add_multiple_quantity').val() : 1;
    jQuery(".upsell-product").each(function (index, event) {
        if (index != 0) {
            var prodId = jQuery(this).attr('prod-id');
            var bundleId = jQuery(this).data('bundle-id');
            var varId = jQuery('#variant_options_' + prodId + ' option:selected', this).val();
            if (oldBundle != bundleId) {
                var prod_price = $('#variant_options_' + prodId + ' option:selected', this).attr('price');
                add_cart_log(prodId, bundleId, prod_price);
            }
            oldBundle = bundleId;
//            quantity = ($("#add_multiple_quantity").length != 0) ? jQuery('#add_multiple_quantity').val() : jQuery('#qty_' + prodId).val();
            var i = {id: varId, quantity: quantity};
            upsellBundleItems.push(i);
        }
    });
    var variId = $("[name='id']").val();
    var da = {id: variId, quantity: quantity};
    upsellBundleItems.push(da);
    addUpsellItem(upsellBundleItems, $("#add_multiple_quantity").length, quantity, 1);
}

/**
 * Add upsell products to cart one by one
 * @param {type} variants
 * @param {type} callback
 * @return {unresolved}
 */
function addUpsellItem(variants, isDivQuantity, quantity, isLoop, idMultipleProducts) {

    if (variants.length) {
        var i = variants.shift();
        $.ajax({
            url: "/cart/add.js",
            type: "POST",
            dataType: "json",
            data: i,
            success: function (data) {
                if(variants.length!=0){
                    jQuery('#cart-response').html('Added Successfully..!!');
                    jQuery('#cart-response').css('display','block');
                    jQuery('#cart-response').fadeOut();
                }
                addUpsellItem(variants, isDivQuantity, quantity, 1, idMultipleProducts);
            },error:function (data) {
                var desc = data.responseText;
                var desc = $.parseJSON( desc );
                //console.log(desc.description);
                jQuery('#cart-response').html(desc.description);
                jQuery('#cart-response').css('display','block');
                //jQuery('#cart-response').fadeOut();
                return false;
            }
        });
    } else {
        if (idMultipleProducts == 1) {
            setTimeout(function () {
                window.location.href = "/cart";
            }, 200);
        }
    }
}

/**
 * Function for add to cart without A+B+C
 * @param {type} form_id
 * @return {undefined}
 */
function addItem(form_id, idMultipleProducts) {

    var frm = jQuery('#' + form_id).serialize();
    var vari = jQuery('.pro_id_' + form_id).val();
    var qty = jQuery('.quantity_' + form_id).val();
    var qtyAry = [];
    var variId = $("[name='id']").val();
    qtyAry.push({id: vari, quantity: qty});
    qtyAry.push({id: variId, quantity: qty});
    addUpsellItem(qtyAry, 1, qty, 1, idMultipleProducts);

    /*
     * multiple upsell and single trigger product logic
     * jQuery.ajax({
     type: 'POST',
     url: '/cart/add.js',
     dataType: 'json',
     data: {'id': vari, 'quantity': qty},
     async: false,
     success: function (data) {
     localStorage.setItem('finalClick', 1);
     jQuery('[name="add"]').click();
     }
     });*/
}

/**
 * Used for insert add to cart log
 * @param {type} prod_id
 * @param {type} popup_bundle_id
 * @param {type} prod_price
 * @return {undefined}
 */
function add_cart_log(prod_id, popup_bundle_id, prod_price) {
    var cart_token = jQuery('#cart_token').val();
    jQuery.ajax({
        type: 'POST',
        url: base_url + 'auth/add_cart_log/',
        dataType: 'json',
        data: {popup_bundle_id: popup_bundle_id, product_id: prod_id, prod_price: prod_price, cart_token: cart_token},
        success: function (data) {
//            console.log(base_url);
//            console.log(prod_id);
            return false;
        }
    });

}/*addtocart*/


/**
 * When user click on skip and next button
 */
jQuery(document).on('click', 'a.skip-this', function () {

    var slide = jQuery(this).data('slide');
    //console.log(slide);
    jQuery(this).parents('.sp').removeClass('active');
    if (slide == 'next') {
        if (jQuery(this).parents('.sp').next().length < 1) {
            jQuery(this).parents('#slider').find('.sp:first').addClass('active');
        } else {
            jQuery(this).parents('.sp').next().addClass('active');
        }
    } else {
        //console.log(jQuery(this).parents('.sp').prev().length);
        if (jQuery(this).parents('.sp').prev().length != 0)
            jQuery(this).parents('.sp').prev().addClass('active').next().removeClass('active');
        else {
            $(".sp:visible").removeClass('active');
            $(".sp:last").addClass('active');
        }
    }

    var bundle_name = jQuery('.active').data('bundle-name');
    var bundle_id = jQuery('.active').data('bundle-id');
    var product = jQuery('.active').data('p-name');
    var isCancel = jQuery('.active').data('cn');
    jQuery(".bundle-title").html(bundle_name);
    jQuery("#popup_bundle_id").val(bundle_id);
    if (isCancel == 1) {
        jQuery(".close").css('display', 'block');
    } else {
        jQuery(".close").css('display', 'none');
    }

    //add bundle log when bundle is changed on view
    if (jQuery.inArray(bundle_id, bundle) == -1) {
        bundle.push(bundle_id);
        updateBundleView(bundle_id);
    }
    product_id = jQuery('.active').attr('prod-id');
    jQuery('.add-this-cart-upsells').attr('prod-id', product_id);
    jQuery("#pr_id").val(product_id);
    jQuery(".col-item").removeClass("fadeInLeft")
    jQuery(".col-item").addClass("fadeInLeft");

    jQuery('.sp').hide();
    jQuery('.active').show().animate({"margin-right": '-=200'});


});

/**
 * Will return cart object
 * @return {undefined}
 */
function getCurrentCartItems() {
    jQuery.ajax({
        type: 'GET',
        url: '/cart.js',
        dataType: 'json',
        data: '',
        success: function (data) {
            cart_items = data.items;
            cart_price = data.total_price;//data.total_price;
            cart_token = data.token;
            jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', false);
//            if ($("#upsell_products").length != 0) {
            getPOPUpProducts(shopId, data.items, cart_price, cart_token);
//            }
        }
    });
}

/**
 * Will get data from server and binds bundle popup
 * @param {type} shopId
 * @param {type} cart_items
 * @param {type} cart_price
 * @param {type} cart_token
 * @return {Number}
 */
function getPOPUpProducts(shopId, cart_items, cart_price, cart_token) {
    frontFunction = 'getPopup';
    var cnt = 0;
    if (prodSlug != '') {
        var variantId = $("[name='id']").val();
        var quantity = jQuery('input[name="quantity"]').val();
        var isUpsellDiv = $("#upsell_products").length;
        frontFunction = 'getCartPagePopup';
        data = {'shopId': shopId, 'isUpsellDiv': isUpsellDiv, 'cart_items': cart_items, 'quantity': quantity, 'cart_price': cart_price, 'product_slug': prodSlug, 'variantId': variantId};
        jQuery.ajax({
            type: 'POST',
            url: base_url + 'front/' + frontFunction,
            data: data,
            async: false,
            dataType: 'json',
            success: function (data) {
                jQuery('div.overlay-bg').remove();
                if (data.status == 'success') {
                    noOfBundle = data.no_of_bundle;
                    isPopUp = data.isPopUp;
                    isSkipNext = data.isSkipNext;
//                    jQuery('div.overlay-bg').remove();
                    if (data.isSkipNext == 1) {
                        jQuery('body').append(data.content);
                        jQuery('.sp').first().addClass('active');
                        jQuery('.sp').hide();
                        jQuery('.active').show();
                    } else {
                        if ($("#upsell_products").length != 0) {
                            console.log('div found');
                            jQuery('#upsell_products').append(data.content);
                        } else {
                            console.log('div not found');
                            jQuery('body').append(data.content);
                        }
                    }

                    jQuery('#cart_token').val(cart_token);
                    cnt = 1;
                }
                jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', false);
            },
            error: function (textStatus, errorThrown) {
                jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', false);
                getPOPUpProducts(shopId, cart_items)
            }
        });
    }
    return cnt;
}

/**
 * Updates bundle view count
 * @param {type} bundle_id
 * @return {undefined}
 */
function updateBundleView(bundle_id) {
    data = {'shopId': shopId, 'bundle_id': bundle_id};
    jQuery.ajax({
        type: 'POST',
        url: base_url + 'front/updateBundleView',
        data: data,
        success: function (data) {
//            console.log(data);
        }
    });

}

/**
 * hide popup when user clicks on close button
 */
jQuery(document).on('click', '.close', function () {
    jQuery('.overlay-bg').hide(); // hide the overlay
});

/**
 * hide popup when user clicks on close button
 */
jQuery(document).on('click', '.close_me', function () {
    jQuery('.overlay-bg').hide(); // hide the overlay
    jQuery('[name="add"]').click();
});

/**
 * Will add quanity on click of quanity increase-decrese in popup
 */
jQuery(document).on('keyup mouseup', '.add_quantity', function () {
    var value = jQuery(this).val();
    jQuery('.prod_quantity').val(value);
    if (isSkipNext == 0) {
        var totalPrice = calculateTotalAmount();
        jQuery('#total_upsell_price').text(totalPrice);
    }
});

jQuery(document).on('keyup mouseup', '[name="quantity"]', function () {
    jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', true);
    getPOPUpProducts(shopId, cart_items, cart_price, cart_token);
});


/**
 * hides the popup if user clicks anywhere outside the container
 */
jQuery(document).on('click', '.overlay-bg', function () {
    //jQuery('.overlay-bg').hide();
    jQuery('[name="checkout"]').click();
    return false;
});

/**
 * prevents the overlay from closing if user clicks inside the popup overlay
 */
jQuery(document).on('click', '.overlay-content', function () {
    return false;
});

jQuery(document).on('change', '#variant_options', function (e) {
    var variantID = jQuery(this).val();
    if (variantID != '') {
        var price = jQuery('option:selected', this).attr('price');
        var productID = jQuery('option:selected', this).attr('productID');
        var sku = jQuery('option:selected', this).attr('sku');
        jQuery('.pro_' + productID + '').attr('prod_price', price);
        jQuery('.variant-sku-' + productID).html(sku);
        jQuery('.pro_' + productID + '').attr('prod-id', variantID);
        jQuery('.form_' + productID + '').attr('id', variantID);
        jQuery('.pro_id_' + productID + '').val(variantID);
        jQuery('.pro_' + productID + '').val(variantID);
        jQuery('.add-this-cart-upsells').attr('prod-id', variantID);
    }
});

/**
 * get current product id
 * @param {type} prodSlug
 * @return {variant_id}
 */
function getCurrentProductID(prodSlug) {
    jQuery.ajax({
        type: 'GET',
        url: '/products/' + prodSlug + '.js',
        dataType: 'json',
        data: '',
        success: function (data) {
            if (typeof data.variants === "undefined") {
                variant_id = data.product.variants[0].id;
            } else {
                variant_id = data.variants[0].id;
            }
        }
    });
    return variant_id;
}

function addDiscount() {
    var dis = jQuery('.discount_code').val();
    var dis = 200;
    if (dis != '') {
        jQuery('form.product-form').attr('action', '/cart?discount=' + dis);
    } else {
        jQuery('form.product-form').attr('action', '/cart?discount=');
    }
}
/**
 * returns host name
 * @param {type} url
 * @return {unresolved}
 */
function extractHostname(url) {
    var hostname;
    //find & remove protocol (http, ftp, etc.) and get hostname
    if (url.indexOf("://") > -1) {
        hostname = url.split('/')[2];
    } else {
        hostname = url.split('/')[0];
    }
    return hostname;
}/*extractHostname*/

function extractPageName(url) {
    var pagename = '';
    //find & remove protocol (http, ftp, etc.) and get hostname

    if (url.indexOf("://") > -1) {
        pagename = url.split('/')[3];
    }
    return pagename;
}/*extractPageName*/

/**
 * return product slug
 * @param {type} url
 * @return {String}
 */
function extractProductSlug(url) {
    var prodSlug = '';
    if (url.indexOf("://") > -1) {
        if (url.split('/')[4]) {
            var urlAry = url.split('/');
            jQuery(urlAry).each(function (index, event) {
                if (event == 'products') {
                    prodSlug = urlAry[index + 1];
                    return prodSlug;
                }
            });
            //console.log(prodSlug);
        }
    }
    return prodSlug;
}
