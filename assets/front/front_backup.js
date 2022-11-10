base_url = 'https://localhost/tops-upsell/';
localStorage.removeItem('showpop');
localStorage.removeItem('variant');
localStorage.removeItem('finalClick');
url = window.location.href;
domain = extractHostname(url);
curr_page = extractPageName(url);
prodSlug = extractProductSlug(url);
var bundle = [];
if (prodSlug != '' && prodSlug != 'all') {
    var variant_id = getCurrentProductID(prodSlug);
}

cart_items = getCurrentCartItems();

checkoputtxt = jQuery('[name="checkout"]').text();
jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', true);

jQuery(window).load(function() {
    jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', false);
    //console.log('test');
});

jQuery(document).ready(function () {
    jQuery('[name="checkout"]').text('Loading...');
    
    console.log('loaded');
    // Show popup when user click on checkout button
    jQuery(document).on('click', '[name="goto_pp"], [name="goto_gc"]', function (e) {
        if (localStorage.getItem('showpop') != 'shown' && jQuery('.sp').length > 0) {
            if (jQuery('.bundle_id').val()) {
                updateBundleView(jQuery('.bundle_id').val());
            }

            localStorage.setItem('showpop', 'shown');
            e.preventDefault(); // disable normal link function so that it doesn't refresh the page
            var docHeight = jQuery(document).height(); //grab the height of the page
            var scrollTop = jQuery(window).scrollTop(); //grab the px value from the top of the page to where you're scrolling
            jQuery('.overlay-bg').show().css({'height': docHeight}); //display your popup and set height to the page height
            jQuery('.overlay-content').css({'top': scrollTop + 20 + 'px'}); //set the content 20px from the window top

            return false;
        }
    });


    // hide popup when user clicks on close button
    jQuery(document).on('click', '.close_me', function () {
        jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', false);
        jQuery('.overlay-bg').hide(); // hide the overlay
        //jQuery('[name="checkout"]').click();

        //jQuery('[name="add"]').click();
        //jQuery('.cart').submit();
    });

    //will add quanity on click of quanity increase-decrese in popup
    jQuery(document).on('keyup mouseup', '.add_quantity', function () {
        var value = jQuery(this).val();
        jQuery('.prod_quantity').val(value);
    });

    // hides the popup if user clicks anywhere outside the container
    jQuery(document).on('click', '.overlay-bg', function () {
        //jQuery('.overlay-bg').hide();
        jQuery('[name="checkout"]').click();
        return false;
    });
    // prevents the overlay from closing if user clicks inside the popup overlay
    jQuery(document).on('click', '.overlay-content', function () {
        return false;
    });

    //When user click on skip this item 	
    jQuery(document).on('click', 'a.skip-this', function () {

        jQuery(this).parents('.sp').removeClass('active');
        if (jQuery(this).parents('.sp').next().length < 1) {
            jQuery(this).parents('#slider').find('.sp:first').addClass('active');
        } else {
            jQuery(this).parents('.sp').next().addClass('active');
        }

        var bundle_name = jQuery('.active').data('bundle-name');
        var use_product_quantity = jQuery('.active').data('use_product_quantity');
        var bundle_id = jQuery('.active').data('bundle-id');
        var product = jQuery('.active').data('p-name');
        jQuery(".bundle-title").html(bundle_name);
        jQuery("#popup_bundle_id").val(bundle_id);
        
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


    });/*previous*/


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


            //productID	
        }
    });
    //jQuery('.skip-this').click();
});/*jQuery*/

    //Add to cart functionality
    jQuery(document).on('click', '.add-this-cart-upsells', function (e) {
        var prod_id = jQuery(this).attr('prod-id');
        var prod_price = jQuery(this).attr('prod_price');
        var popup_bundle_id = jQuery('#popup_bundle_id').val();
        
        jQuery(this).attr('disabled', true);
        add_cart_log(prod_id, popup_bundle_id, prod_price);
        addItem(prod_id);
    });
    
function addItem(form_id) {
    
    var frm = jQuery('#' + form_id).serialize();
    var vari = jQuery('.pro_id_'+form_id).val();
    var qty = jQuery('.quantity_'+form_id).val();
    
    jQuery.ajax({
        type: 'POST',
        url: '/cart/add.js',
        dataType: 'json',
//        data: jQuery('#' + form_id).serialize(),
        data: {'id':vari,'quantity':qty},
        async:false,
        success: function (data) {
            jQuery('.response-' + form_id).html('<span class="text tex-center">Added Successfully..!!</span>');
            localStorage.setItem('finalClick',1);
            //addDiscount();
            jQuery('[name="add"]').click();
        }
    });
}/*addtocart*/



function add_cart_log(prod_id, popup_bundle_id, prod_price) {
    var cart_token = jQuery('#cart_token').val();
    jQuery.ajax({
        type: 'POST',
        url: base_url + 'auth/add_cart_log/',
        dataType: 'json',
        data: {popup_bundle_id: popup_bundle_id, product_id: prod_id, prod_price: prod_price, cart_token: cart_token},
        success: function (data) {
            console.log(base_url);
            console.log(prod_id);
            return false;
        }
    });

}/*addtocart*/

function addVariant() {
    jQuery.ajax({
        type: 'POST',
        url: '/cart/add.js',
        dataType: 'json',
        data: {quantity: 1, id: variant_id},
        success: function (data) {
            jQuery('[name="add"]').click();
        }
    });

}/*addtocart*/

jQuery(document).on('click', '[name="add"]', function (e) {
    var result = getPOPUpProducts(domain, cart_items, cart_price, cart_token);
    jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', true);
    var variantId = jQuery('select[name="id"]').val();
    var localStorageVariant = localStorage.getItem('variant');
    var finalClick = localStorage.getItem('finalClick');
    var pId = jQuery('#pr_id').val();
    
    jQuery('.sp').removeClass('active');
    jQuery('#p_'+pId).addClass('active');
    jQuery('.sp').css('display','none');
    jQuery('#p_'+pId).css('display','block');
    if (finalClick != 1) {
//    console.log('localStorageVariant = '+localStorageVariant+' || variantId='+variantId);
        if ((localStorage.getItem('showpop') != 'shown') && (jQuery('.sp').length > 0) && (result == 1) && (localStorageVariant != variantId)) {
            if (jQuery('.bundle_id').val()) {
                updateBundleView(jQuery('.bundle_id').val());
            }

            localStorage.setItem('showpop', 'shown');
            localStorage.setItem('variant', variantId);
            e.preventDefault(); // disable normal link function so that it doesn't refresh the page
            var docHeight = jQuery(document).height(); //grab the height of the page
            var scrollTop = jQuery(window).scrollTop(); //grab the px value from the top of the page to where you're scrolling
            jQuery('.overlay-bg').show().css({'height': docHeight}); //display your popup and set height to the page height
            jQuery('.overlay-bg').css('display','block');
            jQuery('.overlay-content').css({'top': scrollTop + 20 + 'px'}); //set the content 20px from the window top
            jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', false);
            return false;
        }
        jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', false);
    }
    jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', false);
});

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
            //getPOPUpProducts(domain, data.items, cart_price, cart_token);
        }
    });
}

function getPOPUpProducts(domain, cart_items, cart_price, cart_token) {
    frontFunction = 'getPopup';
    var cnt = 0;
    if (prodSlug != '') {
        var variantId = jQuery('select[name="id"]').val();
        var quantity = jQuery('input[name="quantity"]').val();
        frontFunction = 'getCartPagePopup';
        data = {'domain': domain, 'cart_items': cart_items,'quantity':quantity, 'cart_price': cart_price, 'product_slug': prodSlug, 'variantId': variantId};
        jQuery.ajax({
            type: 'POST',
            url: base_url + 'front/' + frontFunction,
            data: data,
            async: false,
            success: function (data) {
                jQuery('#shopify-section-footer').append(data);
                jQuery('.sp').first().addClass('active');
                jQuery('.sp').hide();
                jQuery('.active').show();
                jQuery('[name="checkout"]').text(checkoputtxt);
                jQuery('[name="goto_pp"], [name="add"], [name="goto_gc"]').attr('disabled', false);
                jQuery('#cart_token').val(cart_token);
                cnt = 1;
            },
            error: function (textStatus, errorThrown) {
                getPOPUpProducts(domain, cart_items)
            }

        });/*ajax*/
    }
    return cnt;
}

function updateBundleView(bundle_id) {

    data = {'domain': domain, 'bundle_id': bundle_id};
    jQuery.ajax({
        type: 'POST',
        url: base_url + 'front/updateBundleView',
        data: data,
        success: function (data) {
            console.log(data);
        }
    });/*ajax*/

}

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
        console.log('abc');
        jQuery('form.product-form').attr('action', '/cart?discount=' + dis);
    } else {
        jQuery('form.product-form').attr('action', '/cart?discount=');
    }
}

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

function extractProductSlug(url) {
    var prodSlug = '';
    //find & remove protocol (http, ftp, etc.) and get hostname

    if (url.indexOf("://") > -1) {
        if (url.split('/')[4]) {
            prodSlug = url.split('/')[4];
        }

    }
    //console.log(pagename);

    return prodSlug;
}/*extractPageName*/
