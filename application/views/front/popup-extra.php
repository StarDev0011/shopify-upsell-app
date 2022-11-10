
       
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

        <?php
        if ($total_products <= 1) {
            include('popup-data.php');
        } else {
            include('scrolling-popup.php');
        }
        ?>
   
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

