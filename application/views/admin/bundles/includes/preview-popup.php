<div class="overlay-bg">
        <div class="overlay-content">
        <a href="#" class="close close-popup" style="display: block">&times;</a>
        <h2 class="bundle-title">
            <?= $bundle_title ?>
            
            <?php if(!empty($products_array[0][0]->bundle->offer_headline)){ ?>
            <h3 class="offer-headline">
                <span class="bundle-inner-title"><?= $products_array[0][0]->bundle->offer_headline ?></span>              
            </h3>
           <?php } ?>     
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
        <div class="slider-container">
            <div id="slider-wrapper">
                <div id="cart-response"></div>
                <div id="slider">            
                    <?php
                    $count = 0;
                    foreach ($products_array as $key => $data) {
                        $class = '';
                        $count++;
                        if ($count == 1) {
                            $class = 'active';
                        }
                        ?>
                        <div class="sp <?= $class ?>" id="p_<?= $data[0]->product_id; ?>" prod-id="<?= $data[0]->product_id; ?>" data-p-name="<?php echo $data[0]->title; ?>" data-use_product_quantity="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-cn="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-bundle-name="<?php echo $data[0]->bundle->bundle_title; ?>" data-bundle-id="<?php echo $data[0]->bundle->id; ?>">
                            <div class="product-item">
                                <div class="product-img">
                                    <figure>
                                        <img id="img_<?= $data[0]->product_id; ?>" src="<?= $data[0]->image ?>" class="img-responsive" alt="a" />
                                    </figure>
                                </div>
                                <div class="product-detail">
                                    <h5 class="name"><?php echo $data[0]->title; ?></h5>
                                    <?php if (isset($data[0]->variants)) { ?>
                                        <div class="product-variant">
                                            <div class="custom-control">
                                                <?php if (count($data[0]->variants) > 1) { ?>
                                                    <select id="variant_options" name="variant_options" class="variant_options form-control">
                                                        <?php foreach ($data[0]->variants as $variant) { ?>
                                                            <option productID="<?= $data[0]->product_id ?>" sku="<?php echo $variant->sku; ?>" price="<?php echo $variant->price ?>" value="<?php echo $variant->variant_id ?>" image="<?php echo $variant->image ?>">
                                                                <?php
                                                                if ($variant->variant_title === 'Default Title') {
                                                                    $variant->variant_title = 'Default Option';
                                                                }
                                                                echo $variant->variant_title . ' (' . $shop_currency . ' ' . $variant->price . ')';
                                                                ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <div class="popup-form-grp">
                                                        <strong>Price:</strong>
                                                        <span class="variant-sku-<?= $data[0]->product_id ?>"><?php echo $data[0]->variants[0]->price . ' (' . $shop_currency . ')'; ?></span>
                                                    </div>
                                                <?php }
                                                    if (!empty($setting) && $setting->show_sku_product == 1) {
                                                        if (!empty($data[0]->variants[0]->sku)) {
                                                            ?>
                                                            <div class="popup-form-grp">
                                                                <strong>SKU:</strong>
                                                                <span class="variant-sku-<?= $data[0]->product_id ?>"><?php echo $data[0]->variants[0]->sku; ?></span>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <?php if (!empty($data[0]->bundle) && $data[0]->bundle->use_product_quantity == 1) { ?>
                                                        <div class="popup-form-grp">
                                                            <strong>Qty:</strong>
                                                            <input name="add_quantity" type="number" min="1" class="add_quantity form-control" value="1">   
                                                        </div>
                                                    <?php } ?>
                                                    <div class="btn-add smartsell_popup_button"> 
                                                        <?php
                                                        $label = 'Add to cart';
                                                        if (!empty($data[0]->variants[0]->inventory) && $data[0]->variants[0]->inventory != 0) {
                                                            ?>
                                                            <a class="add-this-cart-upsells pro_<?= $data[0]->product_id ?>" prod-id="<?= $data[0]->product_id ?>" prod_price="<?php echo (int) $data[0]->variants[0]->price ?>"><?php echo $label ?></a>
                                                        <?php
                                                        } else {
                                                            echo '<div class="popup-form-grp"><b>OUT OF STOCK</b></div>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
        <?php } ?>
                                    </div>
                                </div>
                                <div class="separator">
                                    <div class="smartsell_popup_button btn-details f-left popup-btm-btn">
                                        <?php if (count($products_array) > 1) { ?>
                                            <a class="skip-this btn btn-gray" href="#" data-slide='prev'>Previous</a>
                                            <a class="skip-this btn btn-blue" href="#" data-slide='next'> Next</a>
                                            <a class="btn btn-blue popup_checkout" href="#" data-discount-code="" data-slide='next'> Checkout</a>
                                            <?php
                                        }
                                        //if (!empty($setting) && $setting->show_no_thank_link == 1) {
                                        ?>
                                        <a class="btn btn-gray close_me" href="#" data-slide='prev'> No Thanks</a>
                                        <!--<button type="button" class="btn-link close_me pop-close-btn">No Thanks</button>-->
        <?php //}   ?>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
        <?php
    }
    ?>
                </div>
            </div>
        </div>
            </div>
</div>

