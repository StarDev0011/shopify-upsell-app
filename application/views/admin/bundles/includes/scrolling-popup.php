<div class="overlay-bg">
        <div class="overlay-content scrolling">
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
            <div id="slider"  class="order-popup">
                <div class="popup-product">
                    <?php
                    $count = 0;
                    foreach ($products_array as $key => $data) {
                        $count++;
                        ?>
                        <div class="product-box-col product-sp" id="p_<?= $data[0]->product_id; ?>" prod-id="<?= $data[0]->product_id; ?>" data-p-name="<?php echo $data[0]->title; ?>" data-use_product_quantity="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-cn="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-bundle-name="<?php echo $data[0]->bundle->bundle_title; ?>" data-bundle-id="<?php echo $data[0]->bundle->id; ?>" data-discount-goal="<?php echo $data[0]->bundle->discount_goal_amount; ?>" data-headline="<?php echo $data[0]->bundle->offer_headline; ?>">
                            <div class="popup-product-pic">
                                <img id="img_<?= $data[0]->product_id; ?>" src="<?= $data[0]->image ?>" class="img-responsive" alt="a" />
                            </div>
                            <h3><?php echo $data[0]->title; ?></h3>
                            <div class="popup-product-price">
                                <span class="discount-price"><?php echo $data[0]->variants[0]->price . ' ' . $shop_currency ?></span>
                                <?php
                                if (!empty($setting) && $setting->show_sku_product == 1) {
                                    if (!empty($data[0]->variants[0]->sku)) {
                                        ?>
                                        <span class="discount-price variant-sku variant-sku-<?= $data[0]->product_id ?>">
                                            <label>SKU:</label> <?php echo $data[0]->variants[0]->sku; ?></span>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="popup-size-qty">
                                <?php if(count($data[0]->variants)>1){ ?>
                                <div class="size-qty-box size">
                                    <label>Size:</label>
                                    <select id="variant_options" name="variant_options" class="variant_options">
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
                                </div>
                                <?php } ?>
                                <?php if (!empty($data[0]->bundle) && $data[0]->bundle->use_product_quantity == 1) { ?>
                                    <div class="size-qty-box qty">
                                        <label>Qty:</label>
                                        <div class="quantity">
                                            <button class="qty-change" type="button" data-type="minus" data-product-id="<?= $data[0]->product_id ?>">-</button>
                                            <input name="add_quantity" type="text" value="1" class="add_quantity_<?= $data[0]->product_id ?> form-control" value="1">
                                            <button class="qty-change" type="button"  data-type="plus" data-product-id="<?= $data[0]->product_id ?>">+</button>
                                        </div>
                                    </div>
                                <?php } ?>
                                <form method="post" action="/cart/add" class="form_<?= $data[0]->product_id ?>" id="<?= $data[0]->product_id ?>">
                                    <input type="hidden" name="u_id" class="pro_id_<?= $data[0]->product_id ?>" value="<?php echo isset($data[0]->variants[0]) ? $data[0]->variants[0]->variant_id : 0; ?>" />
                                    <input type="hidden" name="discount_text" class="discount_text_<?= $data[0]->product_id ?>" value="<?php echo isset($data[0]->bundle) ? $data[0]->bundle->discount_text : 0; ?>" />
                                    <input type="hidden" name="discount_code" class="discount_code_<?= $data[0]->product_id ?>" value="<?php echo isset($data[0]->bundle) ? $data[0]->bundle->discount_code : 0; ?>" />
                                    <input type="hidden" name="u_quantity" class="prod_quantity quantity_<?= $data[0]->product_id ?>" value="1" />
                                </form>
                            </div>
                            <?php $cls = (!empty($data[0]->variants[0]->inventory) && $data[0]->variants[0]->inventory != 0)?'':'out-of-stock' ?>
                            <div class="addtocart-btn <?php echo $cls; ?>">
                                <?php  if (!empty($data[0]->variants[0]->inventory) && $data[0]->variants[0]->inventory != 0) { ?>
                                <button type="button" class="add-this-cart-upsells pro_<?= $data[0]->product_id ?>"  prod-id="<?= $data[0]->product_id ?>" prod_price="<?php echo (int) $data[0]->variants[0]->price ?>">Add to cart</button>
                                <?php }else{
                                    echo '<div class="popup-form-grp"><b>OUT OF STOCK</b></div>';
                                } ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="popup-product-footer">
                    <div class="checkout-button">
                        <button>No,thanks</button>
                        <button class="check-out">Check out</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>