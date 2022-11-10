<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<div class="slider-container">
    <div id="slider"  class="order-popup">
        <div class="popup-product" id="scroll-div">
            <?php
            $count = 0;
            // var_dump($products_array);
            foreach ($products_array as $key => $data) {
                $count++;
                $discountText = isset($data[0]->bundle) ? $data[0]->bundle->discount_text:'';
                $discountCode = isset($data[0]->discountcode_title) ? $data[0]->discountcode_title :'';
                ?>
                <div class="product-box-col product-sp" id="p_<?= $data[0]->product_id; ?>" prod-id="<?= $data[0]->product_id; ?>" data-p-name="<?php echo $data[0]->title; ?>" data-use_product_quantity="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-cn="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-bundle-name="<?php echo $data[0]->bundle->bundle_title; ?>" data-bundle-id="<?php echo $data[0]->bundle->bundle_id; ?>" data-discount-goal="<?php echo $data[0]->bundle->discount_goal_amount; ?>" data-headline="<?php echo $data[0]->bundle->offer_headline; ?>">
                    <div class="popup-product-pic">
                        <img id="img_<?= $data[0]->product_id; ?>" src="<?= $data[0]->image ?>" class="img-responsive" alt="a" />
                    </div>
                    <h3 onclick="window.open('<?php echo $data[0]->product_link; ?>', '<?php echo $data[0]->title; ?>').focus();" style="cursor:pointer;"><?php echo $data[0]->title; ?></h3>
                    <div class="popup-product-price">
                        <span class="discount-price pro-price-<?= $data[0]->product_id ?>"><?php echo $data[0]->variants[0]->price . ' ' . $shop_currency ?></span>
                        <?php
                        if (!empty($setting) && $setting->show_sku_product == 1) {
                            if (!empty($data[0]->variants[0]->sku)) {
                                ?>
                                <span class="discount-price  variant-sku variant-sku-<?= $data[0]->product_id ?>">
                                    <label>SKU:</label> <?php echo $data[0]->variants[0]->sku; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="popup-size-qty">
                        <?php if (isset($data[0]->variants)) {

                            $first_prod_var = "";
                            $first_var_price = "";
                            ?>
                            <?php if (count($data[0]->variants) > 1) {
                               ?>
                                <div class="size-qty-box size">
                                    
                                    <label>
                                        <?php 
                                            if (strpos($data[0]->product_options, "|") != false) {
                                                $parts = explode("|", $data[0]->product_options);
                                                $data[0]->product_options = "";
                                                $i = 0;
                                                foreach ($parts as $part) {
                                                    $data[0]->product_options .= $part;
                                                    if ($i != count($parts) - 1) {
                                                        $data[0]->product_options .= " / ";
                                                    }
                                                    $i++;
                                                }
                                            }
                                            echo $data[0]->product_options;
                                        ?>
                                    </label>
                                    <select id="variant_options" name="variant_options" class="variant_options">
                                        <?php foreach ($data[0]->variants as $variant) { ?>
                                         <?php
                                                if ($variant->variant_title === 'Default Title') {
                                                    $variant->variant_title = 'Default Option';
                                                }
                                                else
                                                {
                                                    if($first_prod_var =="")
                                                    {
                                                        $first_prod_var = $variant->variant_id ;
                                                        $first_var_price = $variant->price ;
                                                    }
                                                ?>
                                            <option productID="<?= $data[0]->product_id ?>" sku="<?php echo $variant->sku; ?>" price="<?php echo $variant->price ?>" cur="<?php echo $shop_currency ?>" value="<?php echo $variant->variant_id ?>" image="<?php echo $variant->image ?>">
                                               <?php
                                                echo $variant->variant_title; //. ' (' . $shop_currency . ' ' . $variant->price . ')'
                                                ?>
                                            </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <?php if (!empty($data[0]->bundle) && $data[0]->bundle->use_product_quantity == 1) { ?>
                            <div class="size-qty-box qty">
                                <label>Quantity</label>
                                <div class="quantity">
                                    <button class="qty-change" type="button" data-type="minus" data-product-id="<?= $data[0]->product_id ?>">-</button>
                                    <input name="add_quantity" type="text" value="1" class="add_quantity_<?= $data[0]->product_id ?> form-control" value="1">
                                    <button class="qty-change" type="button"  data-type="plus" data-product-id="<?= $data[0]->product_id ?>">+</button>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <form method="post" action="/cart/add" class="form_<?= $data[0]->product_id ?>" id="<?= $data[0]->product_id ?>">
                            <input type="hidden" name="u_id" class="pro_id_<?= $data[0]->product_id ?>" value="<?php echo $data[0]->variants[0]->variant_id; ?>" />
                            <input type="hidden" name="discount_text" class="discount_text_<?= $data[0]->product_id ?>" value="<?php echo $discountText; ?>" />
                            <input type="hidden" name="discount_code" class="discount_code_<?= $data[0]->product_id ?>" value="<?php echo $discountCode; ?>" />
                            <input type="hidden" name="u_quantity" class="prod_quantity quantity_<?= $data[0]->product_id ?>" value="1" />
                        </form>
                    </div>
                    <div class="addtocart-btn">
                        <button type="button" class="add-this-cart-upsells pro_<?= $data[0]->product_id ?>" variant-id="<?= $first_prod_var ?>" prod-id="<?= $data[0]->product_id ?>" prod_price="<?php echo (int) $first_var_price ?>">Add to cart</button>
                        <p id="upsell-item-success_<?= $data[0]->product_id ?>" style="display:none;">Added to Cart!</p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="popup-product-footer">
            <div class="checkout-button">
                <?php if (!empty($setting) && $setting->show_no_thank_link == 1) { ?>
                    <button class="close_me" type="button">No,thanks</button>
                <?php } ?>
                <button class="check-out popup_checkout" data-discount-code="<?= $discountCode ?>" style="display:none;">Check out</button>
            </div>
        </div>
    </div>
</div>

