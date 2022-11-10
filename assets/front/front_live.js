base_url = 'https://topsdemo.co.in/qa/shopify/tops-upsell/';

// Use the same code from shopify to get jQuery instance
var smbjQuery = null;
if (window.jQuery) {
    smbjQuery = window.jQuery;
} else if (window.Checkout && window.Checkout.$) {
    smbjQuery = window.Checkout.$;
}

// In checkout thankyou page this comes in handy
if (typeof $ !== "function") {
    $ = smbjQuery;
}

localStorage.removeItem('showpop');
localStorage.removeItem('variant');
localStorage.removeItem('finalClick');
url = window.location.href;
domain = extractHostname(url);

var shopId = 0;
var shopData = $('#shopify-features').html();

if (typeof shopData == 'undefined') {
    shopId = __st.a;
} else {
    var obj = JSON.parse(shopData);
    shopId = obj.shopId;
}

productSlug = extractProductSlug(url);
var variantIdFromSlug;
if (typeof productSlug == 'string') {
    productSlug = _getTargetVariant(productSlug);
    variantIdFromSlug = productSlug;
}
var bundle = [];
var key = 1;
var noOfBundle = 0;
var isPopUp = 1;
var isSkipNext = 1;
var isScrollingLayout = 0;
var bundleGoal = 0;
var discount_type = 0;
var goalText = 0;
var isBundleProductAdded = 0;
var isGoalAmount;
var popupIsNotLoaded = true;
var loadingText = "Loading ...";
var checkoutButtonSelector = [
    "#ajaxifyCart button[name='checkout']",
    "[href*='/checkout']:not([href='/tools/checkout/front_end/login'])",
    "[onclick*='/checkout']",
    "[name*='checkout']",
    "[name='checkout']",
    "[href^='/checkout']",
    "form[action='/checkout'] input[type='submit']",
    "form[action='/cart'] button[type='submit']:not([name='update'])",
    "form[action='/cart'] input[type='submit']:not([name='update'])",
    "input[name='checkout']",
    "button[name='checkout']",
    // For an individual customer
    "button.new_checkout_button",
    "button.checkoutcartbtn",
    "input[value='Check out']",
    "input[value='Checkout']",
    "input[value='Check Out']",
    /*
     * :not selector is for theme of one of our clients, in which Checkout
     * button happened to be more like Add to Cart one
     */
    "button:contains('Checkout'):not(.cart_button)",
    // Another custom theme from a client
    "button#add-to-cart:contains('Checkout')",
    /*
     * For uncommon customer's theme
     */
    "a.checkout-link",
    "form[action='/cart'] a.btn:not('.cart__continue'):not('.cart__remove'):not('.cart__update')",
    // Paypal button
    "input[name='goto_pp']",
    "button[name='goto_pp']",
    //On add to cart button Or checkout button
    "button[name='add']",
    "[name*='add']",
    "[name='add']",
//    "button.shopify-payment-button__button"
].join(", ");


var currentButtonText = "";
var checkoutButtons = [];
var key;
var productAddedFromDetailPage = 0;
var finalGoalReached = 0;

//console.log(localStorage.getItem('showpop'));
var variantId = $('select[name="id"]').val();
var variantId = $("[name='id']").val();
localStorage.setItem('variant', variantId);

cart_items = getCurrentCartItems(1);

/**
 * used when any variant changed then calculate price and display
 */
$(document).on('change', '.variant_options', function (e) {
    var img = $('option:selected', this).attr('image');
    var productID = $('option:selected', this).attr('productID');
    $('#img_' + productID).attr('src', img);
    var totalPrice = calculateTotalAmount();
    $("#total_upsell_price").text(totalPrice);
});

/**
 *
 */
$(document).on('click', '.qty-change', function (e) {
    var type = $(this).data('type');
    var productId = $(this).data('product-id');
    var currentVal = parseInt($('.add_quantity_' + productId).val());
    var input = $('.add_quantity_' + productId);
    if (!isNaN(currentVal)) {
        if ((currentVal <= 1) && type == 'minus') {
            input.val(1);
            $('.quantity_' + productId).val(1);
            return true;
        } else {
            var qtyVal = 0;
            if (type == 'minus') {
                qtyVal = currentVal - 1;
                input.val(qtyVal);
                $('.quantity_' + productId).val(qtyVal);
            } else if (type == 'plus') {
                qtyVal = currentVal + 1;
                input.val(qtyVal);
                $('.quantity_' + productId).val(qtyVal);
            }
        }
    } else {
        input.val(1);
        $('.quantity_' + productId).val(1);
    }
});

/**
 * Used for add to cart in A+B+C
 */
$(document).on('click', ".add-upsell-cart", function (e) {
    e.preventDefault();
    addUpsellBundle(e);
});

$(document).on('click', '[name="add"]', function (e) {
    productSlug = extractProductSlug(window.location.href);
    if (typeof productSlug == 'string') {
        productSlug = variantIdFromSlug;
    }
    if(localStorage.getItem('standardCrossSellAdded') == 1){
        $.ajax({
            type: 'POST',
            url: "/cart/add.js",
            dataType: "json",
            data: {quantity: 1, id: productSlug},
            success: function (data) {
                getCurrentCartItems(0);
            }
        });
    }
});

setInterval(function () {
    productSlug = extractProductSlug(window.location.href);
    if ($('.overlay-bg:visible').length == 0)
        getCurrentCartItems(1);
}, 30000);

function _getTargetVariant(slug) {
    console.log(shopId);
    var variId;
    $.ajax({
        type: 'POST',
        url: base_url + 'front/getProductVariant',
        async: false,
        dataType: 'json',
        data: {slug: slug, 'shop_id': shopId},
        success: function (data) {
            if (data.status == 'success') {
                variId = parseInt(data.variant_id);
            }
        }
    });

    return variId;
}
/**
 * Used when any user click on Trigger product's add to cart button. Will show popup if bundle is created.
 * If variant is changed by user this function will check and shows popup
 */
$("body").on('click', checkoutButtonSelector, function (e) {
//$("body").on('click',"#add_to_cart, #ajaxifyCart button[name='checkout'], [href*='/checkout']:not([href='/tools/checkout/front_end/login']), [onclick*='/checkout'], [name*='checkout'], [name='checkout'], [href^='/checkout'], form[action='/checkout'] input[type='submit'], form[action='/cart'] button[type='submit']:not([name='update'])", "form[action='/cart'] input[type='submit']:not([name='update']), input[name='checkout'], button[name='checkout'], button.new_checkout_button, button.checkoutcartbtn, input[value='Check out'], input[value='Checkout'], input[value='Check Out'], button:contains('Checkout'):not(.cart_button), button#add-to-cart:contains('Checkout'), a.checkout-link, input[name='goto_pp'], button[name='goto_pp']", function (e) {

    var loadedPage = (typeof currentPage !== 'undefined') ? currentPage : 'other';
    console.log('isCrossSellAdded = ' + localStorage.getItem('isCrossSellAdded'));
//    console.log('crossSellbundleType = ' + localStorage.getItem('crossSellbundleType'));
//    console.log('standardCrossSellAdded = ' + localStorage.getItem('standardCrossSellAdded'));
    console.log('loadedPage = ' + loadedPage);

//    if ((!target.is("button[name='add']")) && !target.is("[name*='add']") &&
//            !target.is("[name='add']")) {
//        console.log('checkout clicked');
//    }
//    return false;
    //e.preventDefault();
    if ((loadedPage == 'product')) { //Current page is product details and standard cross sell added then show upsellpopup on click of add to cart
        var target = $(e.target);
//        console.log(target);
//        console.log(target.parent().is("button[name='add']"));
        if (localStorage.getItem('standardCrossSellAdded') == 1) {
            showPopup(e);
        } else if ((!target.is("button[name='add']")) && (!target.is("[name*='add']")) &&
                (!target.is("[name='add']")) &&
                (!target.parent().is("button[name='add']")) &&
                (!target.parent().is("[name*='add']")) &&
                (!target.parent().is("[name='add']"))) {
            showPopup(e);
        }
    } else { // if cart page and standard cross sell added from detail page then show upsell popup (Don't show on discount cross-sell)
        if (localStorage.getItem('isCrossSellAdded') != 1) {
            showPopup(e);
        }
    }
});

function showPopup(e) {
    var variantId = $("[name='id']").val();
    var result = 1;
    var pId = $('#pr_id').val();

    if (noOfBundle > 1 && noOfBundle <= 5) {
        $('.sp').removeClass('active');
        $('#p_' + pId).addClass('active');
        $('.sp').css('display', 'none');
        $('#p_' + pId).css('display', 'block');
    }
    var finalClick = localStorage.getItem('finalClick');
    if (finalClick != 1) {
        //check if bundle type is discount code and reach goal is completed then don't show popup
        var goalAmount = parseFloat(localStorage.getItem('goalAmount'));
        if (discount_type != 0) {
            if (parseFloat(goalAmount) >= parseFloat(bundleGoal) && isBundleProductAdded == 1 && discount_type != 3) {
                return true;
            }
        }
        if ((localStorage.getItem('showpop') != 'shown') && (result == 1)) {
            if ($('.bundle_id').val()) {
                updateBundleView($('.bundle_id').val());
            }

            localStorage.setItem('showpop', 'shown');
            localStorage.setItem('variant', variantId);
            e.preventDefault(); // disable normal link function so that it doesn't refresh the page
            var docHeight = $(document).height(); //grab the height of the page
            var scrollTop = $(window).scrollTop(); //grab the px value from the top of the page to where you're scrolling
            $('.overlay-bg').show().css({'height': docHeight}); //display your popup and set height to the page height
            $('.overlay-bg').css('display', 'block');
            $('.overlay-content').css({'top': scrollTop + 20 + 'px'}); //set the content 20px from the window top

        }
    }
}


/**
 * Calculate total amount of bundle products with qty nd price
 * @return {Number}
 */
function calculateTotalAmount() {
    var totalPrice = 0;
    var mprice = $("#mprice").val();
    var quantity = 1;
    $(".upsell-product").each(function (index, event) {
        if (index != 0) {
            var price = $('option:selected', this).attr('price');
            totalPrice += parseFloat(price);
        }
    });
    totalPrice = totalPrice + parseFloat(mprice);
    if (isSkipNext == 0) {
        var totalQty = $('#add_multiple_quantity').val();
        if (typeof totalQty != 'undefined')
            var totalPrice = totalPrice * totalQty;
    }
    return totalPrice;
}


/**
 * Will return cart object
 * @return {undefined}
 */
function getCurrentCartItems(isChangeContent) {
    disableCheckoutButton(true);
    $.ajax({
        type: 'GET',
        url: '/cart.js',
        dataType: 'json',
        data: '',
        success: function (data) {
            cart_items = data.items;
            cart_price = data.total_price;//data.total_price;
            cart_token = data.token;
            var storedCrossSell = JSON.parse(localStorage.getItem('crossSellProducts'));
            var cartVariants = [];
            $.each(cart_items, function (index, jsonObject) {
                cartVariants.push(jsonObject.variant_id);
            });
            var diff = $(storedCrossSell).not(cartVariants).get();
            $.each(diff, function (index, value) {
                storedCrossSell = arrayRemove(storedCrossSell, value);
            });
            if ($.isEmptyObject(storedCrossSell)) {
                localStorage.setItem('isCrossSellAdded', 0);
                localStorage.setItem('scCrossSellDiscount', '');
                localStorage.setItem('standardCrossSellAdded', 0);
            }
            disableCheckoutButton(false);
            getPOPUpProducts(shopId, data.items, cart_price, cart_token, isChangeContent);
        }
    });
}

function arrayRemove(arr, value) {
    return arr.filter(function (ele) {
        return ele != value;
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
function getPOPUpProducts(shopId, cart_items, cart_price, cart_token, isChangeContent) {
    var cnt = 0;
    data = {'shopId': shopId, 'cart_price': cart_price, 'last_added_bundle': localStorage.getItem('latestBundle'), 'goal_amount': localStorage.getItem('goalAmount'), 'latest_used_bundle': localStorage.getItem('lastUsedBundle'), 'last_used_variant': localStorage.getItem('lastUsedVariant'), 'cart_items': cart_items, 'product_slug': productSlug, 'slugType': typeof productSlug};
    var goalAmount = parseFloat(localStorage.getItem('goalAmount'));
    $.ajax({
        type: 'POST',
        url: base_url + 'front/getCartPagePopup',
        data: data,
        async: false,
        dataType: 'json',
        success: function (data) {
            if (isChangeContent == 1) {
                $('div.overlay-bg').remove();
            }
            if (data.status == 'success') {
                noOfBundle = data.no_of_bundle;
                isSkipNext = data.isSkipNext;
                bundleGoal = data.bundle_goal;
                discount_type = data.discount_type;
                goalText = data.goal_away_text;
                isBundleProductAdded = data.is_bundle_product_added;
                isGoalAmount = data.isGoalAmount;
                localStorage.setItem('latestBundle', data.bundle_id);
                localStorage.setItem('goalAmount', data.goal_amount);
                localStorage.setItem('lastUsedVariant', data.last_used_variant);
                if (data.isSkipNext == 1) {
                    if (isChangeContent == 1) {
                        $('body').append(data.content);
                    }
                    if (data.no_of_bundle <= 5) {
                        if (isChangeContent == 1) {
                            $('.sp').first().addClass('active');
                            $('.sp').hide();
                            $('.active').show();
                        }
                    } else {
                        isScrollingLayout = 1
                        setTimeout(function () {
                            $(".popup-product").mCustomScrollbar({
                                theme: "dark",
                            });
                        }, 100);
                    }
                } else {
                    if ($("#upsell_products").length != 0) {
//                        console.log('div found');
                        $('#upsell_products').append(data.content);
                    } else {
//                        console.log('div not found');
                        if (isChangeContent == 1) {
                            $('body').append(data.content);
                        }
                    }
                }
                $('#cart_token').val(cart_token);
                cnt = 1;
            } else {
                if (data.is_empty_cart == 1) {
                    localStorage.removeItem('latestBundle');
                    localStorage.removeItem('goalAmount');
                    localStorage.removeItem('lastUsedVariant');
                    localStorage.removeItem('lastUsedBundle');
                }
            }
            disableCheckoutButton(false);

        },
        error: function (textStatus, errorThrown) {
            disableCheckoutButton(false);
            getPOPUpProducts(shopId, cart_items, cart_price, cart_token, isChangeContent)
        }
    });
    return cnt;
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
    $(".add-upsell-cart").attr('disabled', true);
    var quantity = ($("#add_multiple_quantity").length != 0) ? $('#add_multiple_quantity').val() : 1;
    $(".upsell-product").each(function (index, event) {
        if (index != 0) {
            var prodId = $(this).attr('prod-id');
            var bundleId = $(this).data('bundle-id');
            var varId = $('#variant_options_' + prodId + ' option:selected', this).val();
            if (oldBundle != bundleId) {
                var prod_price = $('#variant_options_' + prodId + ' option:selected', this).attr('price');
                add_cart_log(prodId, bundleId, prod_price);
            }
            oldBundle = bundleId;
//            quantity = ($("#add_multiple_quantity").length != 0) ? $('#add_multiple_quantity').val() : $('#qty_' + prodId).val();
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
 * Add to cart functionality without A+B+C
 */
$(document).on('click', '.add-this-cart-upsells', function (e) {
    var prod_id = $(this).attr('prod-id');
    var prod_price = $(this).attr('prod_price');
    var popup_bundle_id = $('#popup_bundle_id').val();
    var qty = $('.quantity_' + prod_id).val();

    $(this).html('Adding to cart');
    bundleGoal = parseFloat(bundleGoal);

    var step = 100 / 50;
    var bundleId = localStorage.getItem('latestBundle');
    var goalAmount = parseFloat(localStorage.getItem('goalAmount'));
    goalAmount = (!isNaN(goalAmount)) ? goalAmount : 0;
    $(this).attr('disabled', true);
    //console.log('discount_type' + discount_type);
    //console.log('prod_price='+prod_price+' | prod_id='+prod_id+' | goalAmount='+goalAmount+' | qty='+qty);
    //return false;

//        console.log('discount_type='+discount_type+' || isGoalAmount='+isGoalAmount);
    if (discount_type != 0 && discount_type != 3 && isGoalAmount == 1) {
        var isReachedGoal = 0;
        prod_price = parseFloat(prod_price * qty);
        goalAmount = (+goalAmount) + (+prod_price);
        var remainGoalAmount = parseFloat(bundleGoal - goalAmount);
        remainGoalAmount = remainGoalAmount.toFixed(2);
        if (goalAmount >= bundleGoal) {
            isReachedGoal = 1;
            finalGoalReached = 1;
        }

        if (remainGoalAmount < 0)
            remainGoalAmount = 0;
        var reachTimer = parseInt((parseInt(goalAmount) * 100) / parseInt(bundleGoal));
        if (reachTimer == 0)
            reachTimer = 100;

        var timer = $('#common_slider').val();
        var inter = setInterval(function () {
            timer = (+timer) + (+step);
            if (timer >= reachTimer) {
                clearInterval(inter);
                setTimeout(function () {
                    localStorage.setItem('goalAmount', goalAmount);
                    add_cart_log(prod_id, popup_bundle_id, prod_price);

                    addItem(prod_id, isReachedGoal);
                    var msg = goalText.replace('<amount>', remainGoalAmount);
                    //var msg = 'You are ' + remainGoalAmount + ' away from getting discount. Add more product to get discount';
                    $('#goal_text').html(msg);
                    $('#goal_text').css('display', 'block');
//                    if (isReachedGoal == 1) {
//                        $('.main-section').css('display', 'none');
//                        $('.thank-you-section').css('display', 'block');
//                    }
                }, 500);
            }
            $('#common_slider').val(timer);
        }, 5);
    } else {
        finalGoalReached = 1;
        if ((discount_type != 3 && discount_type != 1)) {
            if (noOfBundle == 1) {
                $('.main-section').css('display', 'none');
                $('.thank-you-section').css('display', 'block');
            }
        }
        add_cart_log(prod_id, popup_bundle_id, prod_price);
        addItem(prod_id, 1);
    }
});


/**
 * Function for add to cart without A+B+C
 * @param {type} form_id
 * @return {undefined}
 */
function addItem(form_id, isReachedGoal) {

    var frm = $('#' + form_id).serialize();
    var vari = $('.pro_id_' + form_id).val();
    var qty = $('.quantity_' + form_id).val();
    var discountText = $('.discount_text_' + form_id).val();
    var discountCode = $('.discount_code_' + form_id).val();
    var qtyAry = [];
    qtyAry.push({discountText: discountText, discountCode: discountCode, id: vari, quantity: qty});
    addUpsellItem(qtyAry, 1, qty, 1, isReachedGoal);
}


/**
 * Add upsell products to cart one by one
 * @param {type} variants
 * @param {type} callback
 * @return {unresolved}
 */
function addUpsellItem(variants, isDivQuantity, quantity, isLoop, isReachedGoal) {

    if (variants.length) {
        var i = variants.shift();
        var discountText = i.discountText;
        var discountCode = i.discountCode;
        delete i.discountText;
        delete i.discountCode;
        $('.add-this-cart-upsells').attr('disabled', true);
        $('a.thanks-to-backhome').attr('data-success-text', discountText);
        $('#cart_success_text').html($('a.thanks-to-backhome').data('success-text'));
        $.ajax({
            url: "/cart/add.js",
            type: "POST",
            dataType: "json",
            data: i,
            success: function (data) {
                if (productSlug != '' && (typeof productSlug != 'undefined')) {
                    productAddedFromDetailPage = 1;
                }
                if (noOfBundle > 1 || (discount_type != 0 && discount_type != 3)) {
                    $('#item_added_success').html('Item Added  Successfully, You Can Choose Another Item or Click Checkout.');
                    $('#item_added_success').css('display', 'block');
                    setTimeout(function () {
                        $('#item_added_success').fadeOut();
                        $('.add-this-cart-upsells').html('Add to cart');
                        $('.add-this-cart-upsells').attr('disabled', false);
//                        console.log('isReachedGoal= '+isReachedGoal+' | noOfBundle='+noOfBundle);
                        if (isReachedGoal == 1) {
                            if (noOfBundle > 1) {
                                $('.popup_checkout').attr('data-discount-code', discountCode);
                            } else {
                                $('.main-section').css('display', 'none');
                                $('.thank-you-section').css('display', 'block');
                                var checkout = "/checkout?discount=" + encodeURIComponent(discountCode);
                                window.location.href = checkout;
                                return false;
                            }
                        } else {
                            if (noOfBundle > 1) {
                                moveNextItem();
                                return false;
                            } else if (noOfBundle <= 1 && isReachedGoal == 0) {
                                return false;
                            }
                        }
                        if (noOfBundle > 1) {
                            moveNextItem();
                        } else {
                            $('.main-section').css('display', 'none');
                            $('.thank-you-section').css('display', 'block');
                            var checkout = "/checkout?discount=" + encodeURIComponent(discountCode);
                            window.location.href = checkout;
                        }
                    }, 4000);
                } else {
                    $('.main-section').css('display', 'none');
                    $('.thank-you-section').css('display', 'block');
                    setTimeout(function () {
                        var checkout = "/checkout?discount=" + encodeURIComponent(discountCode);
                        window.location.href = checkout;
                    }, 100);
                }
            }, error: function (data) {
                var desc = data.responseText;
                var desc = $.parseJSON(desc);
                $('#cart-response').html(desc.description);
                $('#cart-response').css('display', 'block');
                $('.add-this-cart-upsells').attr('disabled', false);
                return false;
            }
        });
    }
}

$(document).on('click', 'a.popup_checkout,.check-out', function (e) {
    $('.main-section').css('display', 'none');
    $('.thank-you-section').css('display', 'block');

    setTimeout(function () {
        var discountCode = $('.popup_checkout').data('discount-code');
        var checkout = "/checkout";
        console.log(finalGoalReached);
        if (finalGoalReached == 1) {
            checkout = "/checkout?discount=" + encodeURIComponent(discountCode);
        }
        window.location.href = checkout;
    }, 200);
});


/**
 * Used for insert add to cart log
 * @param {type} prod_id
 * @param {type} popup_bundle_id
 * @param {type} prod_price
 * @return {undefined}
 */
function add_cart_log(prod_id, popup_bundle_id, prod_price) {
    var cart_token = $('#cart_token').val();
    localStorage.setItem('lastUsedBundle', popup_bundle_id);
    $.ajax({
        type: 'POST',
        url: base_url + 'auth/add_cart_log/',
        dataType: 'json',
        data: {popup_bundle_id: popup_bundle_id, 'shop_id': shopId, product_id: prod_id, prod_price: prod_price, cart_token: cart_token},
        success: function (data) {
            return true;
        }
    });
}/*addtocart*/


function moveNextItem() {
    var temp = $('#slider').find('.sp.active');
//    console.log(temp.next('.sp').length);
    if (temp.next('.sp').length < 1) {
        temp.removeClass('active');
        $('#slider').find('.sp:first').addClass('active');
        var btn = $('.prev-item.btn-blue');
//        if (btn.length > 1) {
//            var btngray = $('.skip-this.btn-gray');
//            btn.removeClass('btn-blue').addClass('btn-gray');
//            btngray.removeClass('btn-gray').addClass('btn-blue');
//            $('.skip-this.btn-gray').addClass('clicked-btn');
//        }
    } else {
        temp.removeClass('active');
        temp.next('.sp').addClass('active');
        var btn = $('.next-item.btn-blue');
//        if (btn.length > 1) {
//            var btngray = $('.skip-this.btn-gray');
//            btn.removeClass('btn-blue').addClass('btn-gray');
//            btngray.removeClass('btn-gray').addClass('btn-blue');
//            $('.skip-this.btn-gray').addClass('clicked-btn');
//        }
    }
    activeRecordBinding();
}

/**
 * When user click on skip and next button
 */
$(document).on('click', 'a.skip-this', function () {
    var slide = $(this).data('slide');
    $(this).parents('.sp').removeClass('active');
    if (slide == 'next') {
        if ($(this).parents('.sp').next().length < 1) {
            $(this).parents('#slider').find('.sp:first').addClass('active');
        } else {
            $(this).parents('.sp').next().addClass('active');
        }
        var btn = $('.next-item.btn-blue');
//        if (btn.length > 1) {
//            var btngray = $('.skip-this.btn-gray');
//            btn.removeClass('btn-blue').addClass('btn-gray');
//            btngray.removeClass('btn-gray').addClass('btn-blue');
//            $('.skip-this.btn-gray').addClass('clicked-btn');
//        }
    } else {
        if ($(this).parents('.sp').prev().length != 0)
            $(this).parents('.sp').prev().addClass('active').next().removeClass('active');
        else {
            $(".sp:visible").removeClass('active');
            $(".sp:last").addClass('active');
        }
        var btn = $('.prev-item.btn-blue');
//        if (btn.length > 1) {
//            var btngray = $('.skip-this.btn-gray');
//            btn.removeClass('btn-blue').addClass('btn-gray');
//            btngray.removeClass('btn-gray').addClass('btn-blue');
//            $('.skip-this.btn-gray').addClass('clicked-btn');
//        }
    }

    activeRecordBinding()
});


function activeRecordBinding() {
    var bundle_name = $('.active').data('bundle-name');
    var discount_goal = $('.active').data('discount-goal');
    var headline = $('.active').data('headline');
    var bundle_id = $('.active').data('bundle-id');
    var product = $('.active').data('p-name');
    var isCancel = $('.active').data('cn');
    $(".bundle-inner-title").html(bundle_name);
    $("#discount_goal_text").html(discount_goal);
    $(".bundle-inner-title").html(headline);
    $("#popup_bundle_id").val(bundle_id);
    $("#bundle_price").html(discount_goal);
    $("#bundle_price").val(0);

    if (isCancel == 1) {
        $(".close").css('display', 'block');
    } else {
        $(".close").css('display', 'none');
    }

    //add bundle log when bundle is changed on view
    if ($.inArray(bundle_id, bundle) == -1) {
        bundle.push(bundle_id);
        updateBundleView(bundle_id);
    }
    product_id = $('.active').attr('prod-id');
    $('.add-this-cart-upsells').attr('prod-id', product_id);
    $("#pr_id").val(product_id);
    $(".col-item").removeClass("fadeInLeft");
    $(".col-item").addClass("fadeInLeft");
    $('.sp').hide();
    $('.active').show().animate({"margin-right": '-=200'});
}

/**
 * Updates bundle view count
 * @param {type} bundle_id
 * @return {undefined}
 */
function updateBundleView(bundle_id) {
    data = {'shopId': shopId, 'bundle_id': bundle_id};
    $.ajax({
        type: 'POST',
        url: base_url + 'front/updateBundleView',
        data: data,
        success: function (data) {
        }
    });
}

/**
 * hide popup when user clicks on close button
 */
$(document).on('click', '.close', function () {
    if (productSlug == '' || (typeof productSlug == 'undefined')) {
        localStorage.setItem('showpop', '');
    }
    $('.overlay-bg').hide(); // hide the overlay
});

/**
 * hide popup when user clicks on close button
 */
$(document).on('click', '.thanks-to-backhome, .close_me', function () {
    localStorage.setItem('showpop', 'shown');
    window.location.href = "/checkout";
});

/**
 * Will add quanity on click of quanity increase-decrese in popup
 */
$(document).on('keyup mouseup', '.add_quantity', function () {
    var value = $(this).val();
    $('.prod_quantity').val(value);
    if (isSkipNext == 0) {
        var totalPrice = calculateTotalAmount();
        $('#total_upsell_price').text(totalPrice);
    }
});


$(document).on('keyup mouseup', '[name="quantity"], [name="updates[]"]', function () {
    disableCheckoutButton(true);
    getPOPUpProducts(shopId, cart_items, cart_price, cart_token, 1);
});

/**
 * prevents the overlay from closing if user clicks inside the popup overlay
 */
$(document).on('click', '.overlay-content', function () {
    return false;
});

$(document).on('change', '#variant_options', function (e) {
    var variantID = $(this).val();
    if (variantID != '') {
        var price = $('option:selected', this).attr('price');
        var productID = $('option:selected', this).attr('productID');
        var sku = $('option:selected', this).attr('sku');
        var cur = $('option:selected', this).attr('cur');
        $('.pro_' + productID + '').attr('prod_price', price);
        $('.pro-price-' + productID).html(price + ' ' + cur);
        $('.variant-sku-' + productID).html(sku);
        $('.form_' + productID + '').attr('id', variantID);
        $('.pro_id_' + productID + '').val(variantID);
        $('.pro_' + productID + '').val(variantID);
        $('.add-this-cart-upsells').attr('variant-id', variantID);
    }
});

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
}

function extractPageName(url) {
    var pagename = '';
    if (url.indexOf("://") > -1) {
        pagename = url.split('/')[3];
    }
    return pagename;
}

/**
 * return product slug
 * @param {type} url
 * @return {String}
 */
function extractProductSlug(url) {
    var slug = _queryStringParams('variant');
    if (slug == '' || typeof slug == 'undefined') {
        if (url.indexOf("://") > -1) {
            if (url.split('/')[4]) {
                var urlAry = url.split('/');
                $(urlAry).each(function (index, event) {
                    if (event == 'products') {
                        slug = urlAry[index + 1];
                        return slug;
                    }
                });
            }
        }
    } else {
        slug = parseInt(slug);
    }
    return slug;
}

function _queryStringParams(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}
;

function disableCheckoutButton(status) {
    for (var i in checkoutButtons) {
        if (typeof checkoutButtons[i].element !== "undefined") {
            var element = checkoutButtons[i].element;
            var setLoadingText = true;
            $(element).prop("disabled", status);
            if (status) {
                if (setLoadingText) {
                    changeButtonText(checkoutButtons[i], "loading");
                }
                if (element.tagName === "A") {
                    $(element).click(function () {
                        return false;
                    });
                }
            } else {
                if (setLoadingText) {
                    changeButtonText(checkoutButtons[i], "default");
                }
                if (element.tagName === "A") {
                    $(element).unbind("click");
                }
            }
        }
    }
//    console.log(checkoutButtonSelector);
//    $(checkoutButtonSelector).attr("disabled", status);
}

function _getElementText(element) {
    var text = "Checkout";

    if (typeof element !== "undefined") {
        var type = element.tagName;

        switch (type) {
            case "A":
            case "BUTTON":
                text = $(element).html();
                break;
            case "INPUT":
                text = $(element).val();
                break;
            default:
                text = "Checkout";
        }
    }

    // dirty fix for conflict customer theme or another app handling the checkout button at the same time
    if (text === "Verifying") {
        text = "Checkout";
    }

    return text;
}

function getCurrentButtonText() {

    return _getElementText($(checkoutButtonSelector).get(0));
}

function _changeButtonText(element, text, action) {

    var type = element.tagName;
    switch (type) {
        case "A":
        case "BUTTON":
            if (action === "loading") {
                $(element).addClass("sma7-disabled");
            } else {
                $(element).removeClass("sma7-disabled");
            }
            $(element).html(text);
            break;
        case "INPUT":
            $(element).val(text);
            break;
    }
}

function changeButtonText(obj, action) {

    currentButtonText = obj.text;
    var text = currentButtonText;
    if (action === "loading") {
        text = loadingText;
    }
    if (action === "default") {
        text = (currentButtonText === loadingText) ? "Checkout" : currentButtonText;
    }
    if (text.length < 2) {
        text = "Checkout";
    }

    if ($(obj.element).attr("name") !== "goto_pp") {
        _changeButtonText(obj.element, text, action);
    }
}

function setCheckoutButtons() {
    checkoutButtons = [];

    $(checkoutButtonSelector).each(function () {
//        console.log(this);
        var buttonObj = {element: this, text: _getElementText(this)};
        checkoutButtons.push(buttonObj);
    });
//    console.log(checkoutButtons);
}

var insertListener = function (event) {
    if (event.animationName === "bundleInserted") {
        if ($(checkoutButtonSelector).length && popupIsNotLoaded) {
            setCheckoutButtons();
            currentButtonText = getCurrentButtonText();
            getCurrentCartItems(1);
            popupIsNotLoaded = false;
        }
    }
};

// ready() commented because of mobile issues
popupIsNotLoaded = true;

function loadAssets() {
    var a = 0;
    var assets = [
        ["css", base_url + "assets/front/css/mscroll.css"],
        ["css", base_url + "assets/front/css/style.css"],
        ["script", "https://cdn.jsdelivr.net/jquery.mcustomscrollbar/3.0.6/jquery.mCustomScrollbar.concat.min.js"]
    ],
            d = [];
    for (a = 0; a < assets.length; a++) {
        d[a] = "css" === assets[a][0] ? "link[href='" + assets[a][1] + "']" : "script[src='" + assets[a][1] + "']";
    }
    for (d.join(", "), $(d).remove(), a = 0; a < assets.length; a++) {
        var e, f;
        "css" === assets[a][0] ? (e = document.createElement("link"), e.rel = "stylesheet", e.href = assets[a][1], $("head").prepend(e)) : (f = document.createElement("script"), f.type = "text/javascript", f.async = !0, f.src = assets[a][1], $("head").append(f))
    }
}

function get_receipt_videos() {

    data = {'shopId': shopId};
    $.ajax({
        type: 'POST',
        url: base_url + 'front/getRandomVideo',
        data: data,
        dataType: 'json',
        async: false,
        success: function (data) {
            if (data.status == 'success') {
                $('#videoSelected').attr('src', data.video);
                $('#rVideoSelected').attr('href', data.redirectUrl);
                if (data.title != '') {
                    $('#video_title').text(data.title);
                }
                $('#videoSelected').on("load", function () {
                    $('#rVideoSelected').css('display', 'inline-block');
                    $('#rVideoSelected').css(JSON.parse(data.style)[0]);
                    $('#rVideoSelected').text(data.text);
                });
                $('#count_url').val(data.rand);
                $('#count_url').val(data.rand);
                key = data.rand;
            }
        },
        error: function (textStatus, errorThrown) {
            //alert("something went wrong");
        }
    });

}

$(document).on('click', "#rVideoSelected", function (e) {

    data = {'shopId': shopId, 'key': key};
    $.ajax({
        type: 'POST',
        url: base_url + 'front/updateVideoCount',
        data: data,
        async: false,
        success: function (data) {

        },
        error: function (textStatus, errorThrown) {
            //alert("something went wrong");
        }
    });

});

get_receipt_videos();



loadAssets();

document.addEventListener("animationstart", insertListener, false); // standard + firefox
document.addEventListener("MSAnimationStart", insertListener, false); // IE
document.addEventListener("webkitAnimationStart", insertListener, false); // Chrome + Safari