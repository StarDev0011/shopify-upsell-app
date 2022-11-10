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
            $discountText = isset($data[0]->bundle) ? $data[0]->bundle->discount_text:'';
            $discountCode = isset($data[0]->discountcode_title) ? $data[0]->discountcode_title :'';
            ?>
            <div class="sp <?= $class ?>" id="p_<?= $data[0]->product_id; ?>" prod-id="<?= $data[0]->product_id; ?>" data-p-name="<?php echo $data[0]->title; ?>" data-use_product_quantity="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-cn="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-bundle-name="<?php echo $data[0]->bundle->bundle_title; ?>" data-bundle-id="<?php echo $data[0]->bundle->bundle_id; ?>" data-discount-goal="<?php echo $data[0]->bundle->discount_goal_amount; ?>" data-headline="<?php echo $data[0]->bundle->offer_headline; ?>">
                <div class="product-item">
                    <div class="product-img">
                        <figure>
                            <img id="img_<?= $data[0]->product_id; ?>" src="<?= $data[0]->image ?>" class="img-responsive" alt="a" />
                        </figure>
                    </div>
                    <div class="product-detail">
                        <h5 class="name" onclick="window.open('<?php echo $data[0]->product_link; ?>', '<?php echo $data[0]->title; ?>').focus();" style="cursor:pointer;"><?php echo $data[0]->title; ?></h5>
                        <div>
                        <?php if (isset($data[0]->variants)) {
                            $first_prod_var = "";
                            $first_var_price = "";
                            ?>
                        <div class="product-variant">
                            <div class="custom-control">
                                <?php if(count($data[0]->variants)>1){ ?>
                                <select id="variant_options" name="variant_options" class="variant_options form-control">
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
                                    <option productID="<?= $data[0]->product_id ?>" sku="<?php echo $variant->sku; ?>" price="<?php echo $variant->price ?>" value="<?php echo $variant->variant_id ?>" image="<?php echo $variant->image ?>">
										<?php
                                        if (strpos($variant->variant_title, "|") != false) {
                                            echo "doing";
                                            $parts = explode("|", $variant->variant_title);
                                            foreach ($parts as $part) {
                                                $variant->variant_title = $part . " ";
                                            }
                                        } else { echo "not"; }
                                        echo $variant->variant_title; //. ' (' . $shop_currency . ' ' . $variant->price . ')'
                                        ?>
                                    </option>
                                    <?php
                                        }

                                    ?>
                                    <?php } ?>
                                </select>
                                <?php }else{
                                    $first_prod_var = $data[0]->variants[0]->variant_id;
                                    $first_var_price = $data[0]->variants[0]->price ;
                                    ?>
                                    <div class="popup-form-grp">
                                    <strong>Price</strong>
                                    <span class="variant-sku-<?= $data[0]->product_id ?>"><?php echo $data[0]->variants[0]->price. ' (' . $shop_currency.')'; ?></span>
                                </div>
                              <?php  } ?>
                                <?php
                                if (!empty($setting) && $setting->show_sku_product == 1) {
                                if (!empty($data[0]->variants[0]->sku)) {
                                ?>
                                <div class="popup-form-grp">
                                    <strong>SKU</strong>
                                    <span class="variant-sku-<?= $data[0]->product_id ?>"><?php echo $data[0]->variants[0]->sku; ?></span>
                                </div>
                                <?php }
                                }
                                ?>
                                <?php if (!empty($data[0]->bundle) && $data[0]->bundle->use_product_quantity == 1) { ?>
                                <div class="popup-form-grp">
                                    <strong>Quantity</strong>
                                    <input name="add_quantity" type="number" min="1" class="add_quantity form-control" value="1">
                                </div>
                                <?php } ?>
                                <div class="btn-add smartsell_popup_button">
                                    <?php //$label = (count($products_array) > 1) ? 'Add to cart & Next' : 'Add to cart'; ?>
                                    <a class="add-this-cart-upsells pro_<?= $data[0]->product_id ?>" variant-id="<?= $first_prod_var ?>" prod-id="<?= $data[0]->product_id ?>" prod_price="<?php echo $first_var_price ?>"><?php echo 'Add to cart' ?></a>
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
                        <a class="skip-this btn btn-gray prev-item" href="#" data-slide='prev'>Previous</a>
                        <a class="skip-this btn btn-blue next-item" href="#" data-slide='next'> Next</a>
                        <a class="btn btn-blue popup_checkout" href="#" data-discount-code="<?= $discountCode ?>" data-slide='next' style="display:none;"> Checkout</a>
                        <?php
                        }
                        if (!empty($setting) && $setting->show_no_thank_link == 1) {
                        ?>
                        <a class="btn btn-gray close_me" href="#" data-slide='prev'> No Thanks</a>
                        <!--<button type="button" class="btn-link close_me pop-close-btn">No Thanks</button>-->
                        <?php } ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <form method="post" action="/cart/add" class="form_<?= $data[0]->product_id ?>" id="<?= $data[0]->product_id ?>">
                    <input type="hidden" name="u_id" class="pro_id_<?= $data[0]->product_id ?>" value="<?php echo $data[0]->variants[0]->variant_id; ?>" />
                    <input type="hidden" name="discount_text" class="discount_text_<?= $data[0]->product_id ?>" value="<?php echo $discountText; ?>" />
                    <input type="hidden" name="discount_code" class="discount_code_<?= $data[0]->product_id ?>" value="<?php echo $discountCode; ?>" />
                    <input type="hidden" name="u_quantity" class="prod_quantity quantity_<?= $data[0]->product_id ?>" value="1" />
                </form>
            </div>
                </div>
            <?php
            }
            ?>
    </div>
</div>
</div>