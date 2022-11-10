<div class="overlay-bg close">
     <?php
        if ($total_products <= 5) {
            $class_overylay = 'single';
        } else {
            $class_overylay = 'scrolling';
        }
        ?>
    <div class="overlay-content main-section <?php echo $class_overylay; ?>">
        <?php if ($products_array[0][0]->bundle->use_target_products == 0) { ?>
            <a href="#" class="close" style="display: block">&times;</a>
        <?php } ?>
        <h2 class="bundle-title">
            <?= $bundle_title ?>
             <?php if(!empty($products_array[0][0]->bundle->offer_headline)){ ?>
            <h3 class="offer-headline">
               <span class="bundle-inner-title"><?= $products_array[0][0]->bundle->offer_headline ?></span>
            </h3>
            <?php  } ?>
        </h2>
        <?php if (($products_array[0][0]->bundle->discount_type != 0) && ($products_array[0][0]->bundle->discount_type!=3) && $bundle_goal>0) { ?>
            <?php
            if ($reached_goal_amount == '' || $reached_goal_amount == 0) {
                $amt = 0;
                $dis = 'none';
                $msg = '';
            } else {
                $amt = $reached_ratio;
                $dis = 'block';
                $msg = str_replace('<amount>', $remain_goal_amount, $goal_text);
                //$msg = 'You are '.$remain_goal_amount.' away from getting discount. Add more product to get discount.';
            }
            ?>
            <div class="slidecontainer">
                <input type="range" min="0" data-toggle="tooltip" data-placement="right" max="100" value="<?php echo $amt; ?>" class="slider" id="common_slider" disabled="true">
                <p><span id="bundle_price"><?php echo $bundle_goal ?></span> <b>(<?php echo $shop_currency; ?>)</b></p>
            </div>

            <?php $msg = ($remain_goal_amount > 0) ? $msg : $default_text; ?>
            <span id="goal_text" style="display:<?php echo $dis; ?>"><?php echo $msg; ?></span>
        <?php } ?>
            <div id="item_added_success" class="added-msg" style="display:none"></div>
			<div class="scroller">
        <?php
        if ($total_products <= 1) {
            include('popup-data.php');
        } else {
            include('scrolling-popup.php');
        }
        ?>
		<itemplacex>
		</div>
    </div>
    <div class="overlay-content thank-you-section" style="display: none;">
        <div class="thank-you-popup">
            <div class="thank-you-like">
                <img src="<?php echo site_url(); ?>/assets/img/thank-you.png" class="img-responsive" alt="">
            </div>
            <div class="thank-you-name">Thank You</div>
            <p class="cart-success-text" id="cart_success_text"></p>
            <a class="thanks-to-backhome" data-success-text="<?= $discountText ?>" href="#">Checkout</a>
            <p class="redirect-text">Please wait! You will be redirected in 2 seconds</p>
        </div>
    </div>
    <input type="hidden" class="bundle_id" id="popup_bundle_id" value="<?= $bundle_id; ?>" />
    <input type="hidden" class="pr_id" id="pr_id" value="<?= $firstProduct; ?>" />
    <input type="hidden" class="cart_token" id="cart_token" value="" />
</div>

<style>
    @import url("https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css");
    .order-popup { text-align: left; padding: 15px 0; float: left;}
    .order-popup .popup-product { width: 100%; float: left;}
    .popup-product .product-box-col     { width: 50%; float: left; padding: 0 10px; margin-bottom: 20px;}
    .product-box-col .popup-product-pic     { width: 240px; /*float: left;*/ margin: auto; max-height: 240px; height: 240px; border-radius: 6px; border: 1px solid #ccc;}
    .product-box-col .popup-product-pic img     { width: 100%; border-radius: 6px; height: 100%; object-fit: cover; }
    .product-box-col h3     { width: 100%; float: left; font-size: 15px; font-weight: 600; color: #000000; font-family: 'Open Sans', sans-serif; line-height: 24px; border-bottom: 1px solid #e3e3e3; padding-bottom: 10px; margin: 15px 0px 0px 0px; min-height: 60px; overflow: hidden;}
    .product-box-col .popup-product-price   { width: 100%; float: left; padding: 10px 0; border-bottom: 1px solid #e3e3e3;}
    .popup-product-price span.discount-price { float: left; margin-right: 10px; font-size: 16px; color: #267cee; font-weight: 600; }
    .popup-product-price span.discount-price label { float: left; color: #212b36; margin-bottom: 0 !important;}
    .popup-product-price span.without-discount-price { float: right; font-size: 14px; color: #909090; font-weight: 400; line-height: 24px; text-decoration: line-through;}
    .popup-product-price span.discount-price.variant-sku { float: right;}
    .product-box-col .popup-size-qty { width: 100%; float: left; padding: 10px 0; border-bottom: 1px solid #e3e3e3; display: grid; grid-template-columns: 4fr 1fr; }
    /*    .popup-size-qty .size-qty-box { width: 50%; float: left; }*/
    .popup-size-qty .size-qty-box.size  { flex: none; display: grid; }
    .popup-size-qty .size-qty-box.qty  { flex: none; display: grid; }
    .popup-size-qty .size-qty-box label  { width: 90%; float: left; font-family: 'Open Sans', sans-serif; font-size: 13px; font-weight: 600; line-height: 30px; letter-spacing: 0px; text-transform: uppercase; margin-bottom: -4px;}
    .popup-size-qty .size-qty-box select    { width: 90%; height: 30px; float: left; border-radius: 6px; padding: 0 25px 0 10px; border: 1px solid #e0e0e0; color: #909090; font-size: 12px; outline: none;}
    .popup-size-qty .size-qty-box .quantity { width: 65px; float: left; border-radius: 6px; border: 1px solid #e0e0e0; height: 30px;}
    .size-qty-box .quantity button  { width: 15px; float: left; line-height: 28px; background: transparent; border: 0px; padding: 0px; text-align: center; min-width: auto; color: #3d4246;}
    .size-qty-box .quantity input   { width: 33px; float: left; padding: 0px; height: 28px; line-height: 28px; text-align: center; border-radius: 0px; border: 0px; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; font-size: 12px; color: #267cee; font-family: 'Open Sans', sans-serif; outline: none;}
    .product-box-col .addtocart-btn { width: 100%; float: left; padding: 20px 0;}
    .product-box-col .addtocart-btn button  { width: auto; float: left; font-size: 12px; font-weight: 600; font-family: 'Open Sans', sans-serif; color: #fff; background: #287dee; text-transform: uppercase; padding: 6px 10px; border: none; border-radius: 6px; line-height: 1.6; }
    .order-popup .popup-product-footer  { width: 90%; float: left; margin: 0 50px 0 20px; border-top: 1px solid #e3e3e3; padding-top: 15px;}
    .popup-product-footer .checkout-button  { width: auto; float: right;}
    .checkout-button button     { width: 97px; height: 30px; float: left; background: #f5f5f5; color: #575757; font-size: 12px; font-weight: 600; font-family: 'Open Sans', sans-serif; text-transform: uppercase; border: none; text-align: center; border-radius: 6px; margin-left: 20px; padding: 0 !important;}
    .checkout-button button.check-out   { background: #34bfa3; color: #fff;}
    /* .popup-product { max-height: 330px;} */

    /* Thank you popup */

    .thank-you-popup { text-align: center; padding: 30px;}
    .thank-you-popup .thank-you-like { max-width: 293px; float: none; display: inline-block; }
    .thank-you-popup .thank-you-like img    { width: 100%; max-width: 100%; }
    .thank-you-popup .thank-you-name    { font-size: 36px; color: #267cee; font-weight: 700; font-family: 'Open Sans', sans-serif; line-height: normal; margin-bottom: 20px;}
    .thank-you-popup p  { font-size: 13px; color: #17191c; font-weight: normal; font-family: 'Open Sans', sans-serif;}
    .thank-you-popup .thanks-to-backhome    { width: 135px; height: 34px; line-height: 34px; display: inline-block; border-radius: 6px; background: #34bfa3; font-size: 13px; font-weight: bold; font-family: 'Open Sans', sans-serif; color: #fff; margin-top: 10px;}

    /* Thank you popup */



    /*Skip and next popup*/

    .slider-container { padding: 0 10px 20px 10px;}
    .popup-price-slider { width: 100%; padding: 0 15px;}
    .popup-price-slider input { width: 100%; }
    #cart-response { text-align: center; margin: 0 0 15px 0; color: #028602; font-weight: bold; }
    .btn-link { background: none; border: none; font-size: 14px; }
    .popup-form-grp { margin-top: 10px; }
    .popup-form-grp strong { margin-right: 10px; }
    .popup-form-grp .add_quantity { width: 100px; display: inline-block; vertical-align: middle; }
    .smartsell_popup_button { display: inline-block; }
    .smartsell_popup_button .btn{ min-width: 85px;}
    .popup-btm-btn a { font-size: 14px; padding: 0 6px !important; height: 34px; line-height: 32px; margin: 0 5px; background: #f4f4f4; color: #656565; text-transform: capitalize; border-radius: 4px;display: inline-block;text-align: center;}
    .redirect-text{margin-top: 15px;font-family: 'Open Sans', sans-serif;}
    .popup-btm-btn a:hover { background: #f4f4f4; color: #656565; }
    .popup-btm-btn .btn-blue, .popup-btm-btn .btn-blue:hover { background: #287eef; color: #fff; }
    .smartsell_popup_button .add-this-cart-upsells { background: #287dee; line-height: 40px; padding: 0 20px; display: block; color: #fff; border-radius: 2px; margin-top: 20px; }
    .product-variant { padding: 5px 15px; text-align: left; }
    .variant_options:focus { outline: none; }
    .text-center { text-align: center; }
    .skip-this { font-size: 12px; }
    .smartsell_popup_button .add-this-cart-upsells:hover { cursor: pointer; }
    .border-top { border-top: 1px solid #ddd; padding-top: 30px; border-radius: 0 !important; margin-top: 10px; }
    .pt40 { padding-top: 40px !important; }
    .bundle-title { color: #267cee; font-size: 20px; text-align: center; line-height: 1.4; padding: 15px 15px 0 15px; text-transform: capitalize; letter-spacing: 0; margin-bottom: 0; font-weight: bold; font-family: 'Open Sans', sans-serif; }
    .offer-headline { color: black; font-size: 14px; text-align: center; line-height: 1.4; padding: 0 15px 15px 15px; border-bottom: 1px solid #e5e5e5; letter-spacing: 0; font-weight: 600; margin-bottom: 0; font-family: 'Open Sans', sans-serif; }
    .overlay-bg { display: none; position: fixed; top: 0; left: 0; height: 100vh !important; width: 100%; cursor: pointer; z-index: 1000; /* high z-index */ background: #000; /* fallback */ background: rgba(0, 0, 0, 0.75); }
    .overlay-content .close { position: absolute; right: 15px; top: 0; font-size: 30px; }
    .overlay-content { background: #fff; padding-bottom: 0; width: 700px; max-width: 100%; position: absolute; top: 50% !important; left: 0; right: 0; margin: 0 auto; transform: translateY(-50%); -webkit-transform: translateY(-50%); cursor: default; border-radius: 4px; box-shadow: 0 0 5px rgba(0, 0, 0, 0.9); height: auto; }
    .overlay-content.scrolling { width: 610px; }
    .overlay-content:after { clear: both; display: block; height: 1px; content: ''; }
    .close-btn { cursor: pointer; border: 1px solid #333; padding: 2% 5%; background: #a9e7f9; /* fallback */ background: -moz-linear-gradient(top, #a9e7f9 0%, #77d3ef 4%, #05abe0 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #a9e7f9), color-stop(4%, #77d3ef), color-stop(100%, #05abe0)); background: -webkit-linear-gradient(top, #a9e7f9 0%, #77d3ef 4%, #05abe0 100%); background: -o-linear-gradient(top, #a9e7f9 0%, #77d3ef 4%, #05abe0 100%); background: -ms-linear-gradient(top, #a9e7f9 0%, #77d3ef 4%, #05abe0 100%); background: linear-gradient(to bottom, #a9e7f9 0%, #77d3ef 4%, #05abe0 100%); border-radius: 4px; box-shadow: 0 0 4px rgba(0, 0, 0, 0.3); }
    .close-btn:hover { background: #05abe0; }
    /********* Custom Slider *******/
    #slider-wrapper { width: 100%; text-align: right; overflow: hidden; }
    #slider { width: 100%; position: relative; }
    .sp { width: 100%; display: none; }
    #nav { margin-top: 20px; width: 100%; }
    #button-previous { float: left; }
    #button-next { float: right; }
    /** FAHAD Pop-up **/
    .col-item { border-radius: 5px; background: #FFF; }
    .col-item .photo img { margin: 0 auto; max-height: 300px; width: auto !important; height: 90%; }
    .col-item .info { padding-left: 10px; padding-bottom: 5px; border-radius: 0 0 5px 5px; }
    .col-item:hover .info { background-color: #F5F5DC; }
    .col-item .price { text-align: center; margin-top: 5px; }
    .col-item .price h5 { line-height: 20px; margin: 0; }
    .price-text-color { color: #219FD1; }
    .col-item .info .rating { color: #777; }
    .col-item .rating {        /*width: 50%;*/
        float: left; font-size: 17px; text-align: right; line-height: 52px; margin-bottom: 10px; height: 52px; }
    /*.col-item .separator
    {
        border-top: 1px solid #E1E1E1;
    }*/
    .clear-left { clear: left; }
    .col-item .separator p { line-height: 20px; margin-bottom: 10px; margin-top: 10px; text-align: right; }
    .col-item .separator p i { margin-right: 5px; }
    .col-item .btn-add { margin-right: 10px; }
    .col-item .btn-details { width: 50%; float: left; padding-left: 10px; }
    .controls { margin-top: 20px; }
    [data-slide="prev"] { margin-right: 10px; }
    .col-center { float: none; margin: 0 auto; }
    .carousel-top h3 { color: #fff; font-weight: bold; text-align: center; margin-bottom: 40px; }
    .modal-header-new { padding-right: 15px; }
    .text-success { color: #5cb85c; }
    .photo { text-align: center; max-height: 300px; }
    a.btn.btn-primary.pop-close-btn { margin-bottom: 4px; }
    button.close { background-color: transparent; border: none; font-size: 25px; font-weight: bold; color: #7796a8; position: relative; top: -5px; }
    .photo .text.tex-center { font-size: 18px; font-weight: bold; -webkit-animation-name: example; -webkit-animation-duration: 1s; -webkit-animation-iteration-count: infinite; animation-name: flashy; animation-duration: 1s; transition-timing-function: ease; animation-iteration-count: 1; color: green; }
    .product-item { width: 100%; display: table; }
    .product-item .product-img { width: 240px; display: inline-block; float: left; }
    .product-item .product-img figure { height: 300px; width: 100%; display: block; border: 1px solid #d3d3d3; border-radius: 5px; position: relative; margin: 0; }
    .product-item .product-img figure >img { max-height: 100%; max-width: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
    .product-item .product-detail { width: calc(100% - 240px); display: inline-block; float: left; }
    .product-item .product-detail .name { font-size: 18px; color: #000000; text-transform: capitalize; margin: 0 15px 15px 15px; text-align: left; }
    .product-item .product-detail .variant_options { padding: 0 30px 0 15px; text-align: left; width: 100%; }
    .product-item .product-detail .variant_options .variant-label { font-size: 9px ; }
    .btn-gray { padding: 10px 20px !important; font-size: 16px; background: #b8bec4; color: #fff; text-transform: capitalize; font-weight: normal; letter-spacing: 0; }
    .btn-gray:hover, .btn-gray:focus, .btn-gray:active { background: #b8bec4; color: #fff; outline: 0 !important; box-shadow: none; }
    .slider-container .separator { position: absolute; right: 0; bottom: 0; padding: 0; }
    .custom-control .form-control { border: 1px solid #e8e8e8 !important; background-color: #f6f8fa !important; box-shadow: none !important; height: 36px !important; line-height: 36px; padding: 0px 15px; }
    .custom-control select.form-control { -webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 30px;        /*background: url(../../../assets/img/select_arrow.png);*/ background-repeat: no-repeat; background-position: right 10px center; }
    #cart_success_text{font-size: 20px;}
    /*Slider CSS Starts*/
    .slidecontainer {
        width: 100%;
        padding: 20px;
        display: flex;
    }
    .slidecontainer .slider {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        height: 2px;
        background: #e3e3e3;
        outline: none;
        -webkit-transition: .2s;
        transition: opacity .2s;
        padding: 0px;
        border: none;
        margin-bottom: 10px;
        margin-top: 10px;
        margin-right: 10px;
        flex: 1;
        position: relative;
    }

    .slidecontainer .slider[data-line]:after {
        content: attr(data-line);
        position: absolute;
        top: -10px;
        left: 18px;
        color: #ffffff;
    }

    .slidecontainer .slider p   {
        flex: none;
    }
    .slider:hover {
        opacity: 1;
    }
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 50px;
        height: 22px;
        border-radius: 10px;
        background: #4CAF50;
        cursor: pointer;
    }
    .slider::-moz-range-thumb {
        width: 50px;
        height: 22px;
        border-radius: 10px;
        background: #4CAF50;
        cursor: pointer;
    }
    .subtext{
        display: block;
        color: #555;
        font-weight: 600;
        font-size: 12px;
/*        text-align: left;*/
    }
    .added-msg{
        text-align: center;
        color: #3c763d;
        font-weight: 600;
        font-size: 14px;
        padding: 5px 0;
        line-height: 1;
    }
    .clicked-btn {
        border: 1px solid #999;
    }
    /*Slider CSS Ends*/
    @keyframes flashy {
        0% { color: red; letter-spacing: 1px; }
        25% { letter-spacing: 2px; }
        50% { letter-spacing: 3px; }
        75% { letter-spacing: 2px; }
        100% { color: green; letter-spacing: 1px; }
    }
    .f-left { float: left; }
    .separator.clear-left { padding-top: 25px; }
    .pro-status { width: 100%; float: left; padding: 0 15px; }
    .pro-status a { color: #257deb; display: block; float: left; }
    span#goal_text{animation:opac 0.8s}@keyframes opac{from{opacity:0} to{opacity:1}}
    span#goal_text {
        padding: 10px 15px 10px 15px;
        font-size: 14px;
        text-align: center;
        color: #3c763d;
        background: #dff0d8;
        line-height: normal;}
        *, *::before, *::after {box-sizing: border-box;}
    @media only screen and (max-width:992px) and (min-width:768px) {
        /*.slider-container .separator { width: 300px; }*/
        .btn-gray { margin-bottom: 5px; }
    }
    @media (max-width:767px) {
        .pro-status     { padding: 0px; }
        .btn-gray { margin-bottom: 5px; }
        .product-item .product-img, .product-item .product-img figure, .product-item .product-detail { width: 100%; display: block; }
        .product-item { text-align: center; }
        .product-item .product-img, .product-item .product-img figure { max-width: 200px; max-height: 200px; float: none; display: inline-block;}
        .product-item .product-img figure >img { max-width: 95%; }
        .slider-container .separator { position: inherit; right: 0; bottom: 0; padding: 20px 0; text-align: left; }
        .product-item .product-detail .name { margin: 15px 0; }
        .product-variant { padding: 15px 0; }
        .popup-product .product-box-col { width: 100%;}
        .overlay-bg { overflow-y: auto;}
        .smartsell_popup_button .add-this-cart-upsells { line-height: 34px; padding: 0 20px;}
        .popup-btm-btn a { font-size: 12px; margin: 0 2px;}
        .product-item .product-img figure { max-height: 160px; max-width: 160px;}
        .slidecontainer { padding: 10px 20px;}
        .product-item .product-detail .name { margin: 5px 0;}
        .smartsell_popup_button { width: 100%; text-align: center;}
        .popup-btm-btn a { display: inline-block; float: none;}
        .smartsell_popup_button .btn{
            min-width: 90px;
            margin: 10px 0;
        }
        #slider { padding-bottom: 100px; }
    }
    @media (max-width:480px) {
        .overlay-content { width: 90% !important; top: 20px !important; transform: none; }
        .btn-details.f-left { float: none !important; }
        .separator.clear-left { text-align: center !important; }
    }
	.scroller { height: auto; }
</style>
