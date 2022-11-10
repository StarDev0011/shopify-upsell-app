<div class="crosssell-bg">
    <div class="overlay-content thank-you-section" style="display: none;">
        <div class="thank-you-popup">
            <div class="modal-close-icon">
                <a href="#" class="close-thanku"><img src="<?php echo site_url(); ?>/assets/img/cancel.png"></a>
            </div>
            <div class="thank-you-like">
                <img src="<?php echo site_url(); ?>/assets/img/thank-you.png" class="img-responsive" alt="">
            </div>
            <div class="thank-you-name">Thank You</div>
            <p class="cart-success-text" id="cart_success_text"><?php echo $success_text ?></p>
            <a class="cross-sell-thanks" data-code="<?= $targetProduct->discount_code ?>" href="#">Checkout</a>
            <p class="redirect-text">Please wait! You will be redirected in 2 seconds</p>
        </div>
    </div>
</div>
<style>
    .crosssell-bg { display: none; position: fixed; top: 0; left: 0; height: 100vh !important; width: 100%; cursor: pointer; z-index: 1000; /* high z-index */ background: #000; /* fallback */ background: rgba(0, 0, 0, 0.75); }
    .overlay-content .close { position: absolute; right: 15px; top: 0; font-size: 30px; }
    .overlay-content { background: #fff; padding-bottom: 0; width: 700px; max-width: 100%; position: absolute; top: 50% !important; left: 0; right: 0; margin: 0 auto; transform: translateY(-50%); -webkit-transform: translateY(-50%); cursor: default; border-radius: 4px; box-shadow: 0 0 5px rgba(0, 0, 0, 0.9); height: auto; }
    .overlay-content.scrolling { width: 610px; }
    .overlay-content:after { clear: both; display: block; height: 1px; content: ''; }

    /* Thank you popup */

    .thank-you-popup { text-align: center; padding: 30px;}
    .thank-you-popup .thank-you-like { max-width: 293px; float: none; display: inline-block; }
    .thank-you-popup .thank-you-like img    { width: 100%; max-width: 100%; }
    .thank-you-popup .thank-you-name    { font-size: 36px; color: #267cee; font-weight: 700; font-family: 'Open Sans', sans-serif; line-height: normal; margin-bottom: 20px;}
    .thank-you-popup p  { font-size: 13px; color: #17191c; font-weight: normal; font-family: 'Open Sans', sans-serif;}
    .thank-you-popup .cross-sell-thanks    { width: 135px; height: 34px; line-height: 34px; display: inline-block; border-radius: 6px; background: #34bfa3; font-size: 13px; font-weight: bold; font-family: 'Open Sans', sans-serif; color: #fff; margin-top: 10px;}
    .redirect-text{margin-top: 15px;font-family: 'Open Sans', sans-serif;}
    /* Thank you popup */
    .modal-close-icon { position: absolute; top: 20px; right: 20px;}
    .modal-close-icon img { width: 16px;}
</style>