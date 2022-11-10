(function ($)
{
    "user strict";

    var smartCrossSell = function ()
    {
        var c = this;
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
        $(document).ready(function ()
        {
            s._initialize();
        });
    };

    var s = smartCrossSell.prototype;
    s.baseUrl = 'https://topsdemo.co.in/qa/shopify/tops-upsell/';
    var prodSlug;
    s.shopId = '';
    s.crossSellArray = [];
    s.currentProductVariant = '';

    s._initialize = function ()
    {
        s.getShopId();
        var url = window.location.href;
        s.prodSlug = s._extractProductSlug(url);
        s.currentProductVariant = s._getTargetVariant();
        s._loadAssets();
        s._loadCrossSellProducts();
        s._addToCartCrossSell();
        s._closeThankyouPopup();
        s._checkBoxCheck();
        s._redirectCheckout();
        var storedCrossSell = localStorage.getItem('crossSellProducts');
        
//        console.log(storedCrossSell);
//        localStorage.removeItem('crossSellbundleType');
//        localStorage.removeItem('standardCrossSellAdded');
    };
    
    s._loadAssets = function () {
        var a = 0;
        var assets = [
            ["css", s.baseUrl + "assets/front/css/slick.css"],
            ["css", s.baseUrl + "assets/front/css/slick-theme.css"],
            ["script", s.baseUrl + "assets/front/js/slick.min.js"],
            ["css", s.baseUrl + "assets/front/css/toastr.min.css"],
            ["script", s.baseUrl + "assets/front/js/toastr.min.js"]
        ],
                d = [];
        for (a = 0; a < assets.length; a++) {
            d[a] = "css" === assets[a][0] ? "link[href='" + assets[a][1] + "']" : "script[src='" + assets[a][1] + "']";
        }
        for (d.join(", "), $(d).remove(), a = 0; a < assets.length; a++) {
            var e, f;
            "css" === assets[a][0] ? (e = document.createElement("link"), e.rel = "stylesheet", e.href = assets[a][1], $("head").prepend(e)) : (f = document.createElement("script"), f.type = "text/javascript", f.async = !0, f.src = assets[a][1], $("head").append(f))
        }
    };

    s.getShopId = function () {
        var shopData = $('#shopify-features').html();
        if(typeof shopData == 'undefined'){
            s.shopId = __st.a;
        }else{
            var obj = JSON.parse(shopData);
            s.shopId = obj.shopId;
        }
    };
    
    s._getTargetVariant = function(){
        var slug = s._extractProductSlug(window.location.href);
        if(typeof (slug)=='string'){
            $.ajax({
                type: 'POST',
                url: s.baseUrl + 'front/getProductVariant',
                async: false,
                dataType: 'json',
                data: {slug: slug, 'shop_id': s.shopId},
                success: function (data) {
                    if (data.status == 'success') {
                        s.currentProductVariant = parseInt(data.variant_id);
                    }
                }
            });
        }else{
            s.currentProductVariant = slug;
        }
    };
    
    s._checkBoxCheck = function () {
        $(document).on('click', '.smart-cross-sell-cart', function () {
            var sThisVal = $(this).val();
            if (this.checked) {
                s.crossSellArray.push(sThisVal);
            } else {
                s.crossSellArray.splice($.inArray(sThisVal, s.crossSellArray), 1);
            }
            if(!jQuery.isEmptyObject(s.crossSellArray)){
                $('.cross-sell-add-cart').attr('disabled',false);
            }else{
                $('.cross-sell-add-cart').attr('disabled',true);
            }
        });
    };

    s._extractProductSlug = function (url) {
        var slug = s._queryStringParams('variant');
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
    };

    s._queryStringParams = function (sParam) {
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
    };

    //Load cross sell products
    s._loadCrossSellProducts = function ()
    {
        if (typeof currentPage !== 'undefined') {
            if (currentPage == 'product') {
                
                $.ajax({
                    type: 'POST',
                    url: s.baseUrl + 'front/getCrossSellProducts',
                    async: false,
                    dataType: 'json',
                    data: {slug: s.prodSlug, 'shop_id': s.shopId, 'slugType': typeof s.prodSlug},
                    success: function (data) {
                      
                        if (data.status == 'success') {
                            if ($("#smart-cross-sell").length != 0) {
                                $('#smart-cross-sell').append(data.content);
                                $(".regular").slick({
                                    dots: false,
                                    infinite: true,
                                    slidesToShow: 3,
                                    slidesToScroll: 3
                                });
                            }
                        }
                    }
                });
            }
        }
    };

    s._addToCartCrossSell = function () {
        $(document).on('click', '.cross-sell-add-cart', function () {
            var addedToCart = 0;
            var finalArray = [];
            var discountCode = $(this).data('dc');
            var bundleType = $(this).data('dt');
            
            if($(this).data('dt')!=0){
                s._getTargetVariant();
                if(s.currentProductVariant!=''){
                    s.crossSellArray.push(s.currentProductVariant);
                }
            }else{
                
            }
            $(s.crossSellArray).each(function (index, varientId) {
                $.ajax({
                    url: "/cart/add.js",
                    type: "POST",
                    dataType: "json",
                    async:false,
                    data: {id: varientId, quantity: 1},
                    success: function (data) {
                        
                        var storedCrossSell = localStorage.getItem('crossSellProducts');
                        if (storedCrossSell != null) {
                            finalArray = JSON.parse(storedCrossSell);
                        }
                        addedToCart = 1;
                        var found = $.inArray(parseInt(varientId), finalArray);
                        if(found === -1){
                            finalArray.push(parseInt(varientId));
                        }
                        localStorage.setItem('crossSellProducts', JSON.stringify(finalArray));
                        if(bundleType!=0){ //Not standard cross sell added to cart
                            localStorage.setItem('isCrossSellAdded', 1);
                        }else{
                            localStorage.setItem('standardCrossSellAdded', 1);
                        }
                        localStorage.setItem('crossSellbundleType', bundleType);
                    }, error: function (data) {

                    }
                });
            });
//            console.log('addedToCart = '+addedToCart);
            if (addedToCart == 1) {
//                console.log('discountCode = '+discountCode);
                localStorage.setItem('scCrossSellDiscount', discountCode);
                var textMsg = 'Product added to cart successfully!';
                toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "4000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                };
                if(discountCode==''){
                    toastr.options.timeOut = 3000; // How long the toast will display without user interaction
                    toastr.options.extendedTimeOut = 3000;
                    toastr.options.positionClass = "toast-top-right";
                } else {
                    var docHeight = $(document).height(); //grab the height of the page
                    var scrollTop = $(window).scrollTop();
                    $('.crosssell-bg').show().css({'height': docHeight});
                    $('.crosssell-bg').css('display', 'block');
                    $('.overlay-content').css({'top': scrollTop + 20 + 'px'});
                    $('.thank-you-section').css('display', 'block');
                    setTimeout(function () {
                        var checkout = "/checkout?discount=" + encodeURIComponent(discountCode);
                        window.location.href = checkout;
                    }, 100);
                    return false;
                    /*toastr.options.timeOut = 0;
                    toastr.options.extendedTimeOut = 0; 
                    toastr.options.positionClass = "toast-top-center";
                    toastr.options.tapToDismiss = false;
                    var checkout = "/checkout?discount=" + encodeURIComponent(discountCode);
                    textMsg = 'Product added to cart successfully! <br><br> Please click on below link to get discount <a href="javascript:void(0)" onClick = "window.location.href = \''+checkout+'\';"><b>Checkout</b></a>';*/
                }
                toastr.success(textMsg,'',{allowHtml: true});
            }
            return false;
        });
    };

    s._closeThankyouPopup = function () {
        $(document).on('click', '.close-thanku', function () {
            $('.crosssell-bg').hide();
            $('.thank-you-section').css('display', 'none');
        });
    };
    
    s._redirectCheckout = function () {
        $(document).on('click', '.cross-sell-thanks', function () {
            var discountCode = $(this).data('code');
            var checkout = "/checkout?discount=" + encodeURIComponent(discountCode);
            window.location.href = checkout;
        });
    };

    window.smartCrossSellApp = window.smartCrossSellApp || {};
    window.smartCrossSellApp.smartCrossSell = new smartCrossSell();
})(jQuery);
