<div class="slider-container">
    <div id="slider-wrapper">
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
            <div class="sp <?= $class ?>" id="p_<?= $data[0]->product_id; ?>" prod-id="<?= $data[0]->product_id; ?>" data-p-name="<?php echo $data[0]->title; ?>" data-use_product_quantity="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-bundle-name="<?php echo $data[0]->bundle->bundle_title; ?>" data-bundle-id="<?php echo $data[0]->bundle->id; ?>">
                    <div class="product-item">
                        <div class="product-img">
                            <figure>
                                <img src="<?= $data[0]->image ?>" class="img-responsive" alt="a" />
                            </figure>
                        </div>
                        <div class="product-detail">
                            <h5 class="name"><?php echo $data[0]->title; ?></h5>
                            <?php if (isset($data[0]->variants)) { ?>
                                <div class="product-variant">
                                    <div class="custom-control">
                                        <select id="variant_options" name="variant_options" class="variant_options form-control">
                                            <?php foreach ($data[0]->variants as $variant) { ?>
                                                <option productID="<?= $data[0]->product_id ?>" sku="<?php echo $variant->sku; ?>" price="<?php echo $variant->price ?>" value="<?php echo $variant->variant_id ?>">
                                                    <?php
                                                    if ($variant->variant_title === 'Default Title') {
                                                        $variant->variant_title = 'Default Option';
                                                    }
                                                    echo $variant->variant_title . ' (' . $shop_currency . ' ' . $variant->price . ')';
                                                    ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <?php
                                        if (!empty($setting) && $setting->show_sku_product == 1) {
                                            if (!empty($data[0]->variants[0]->sku)) {
                                                ?>
                                                <div class="popup-form-grp">
                                                    <strong>SKU:</strong>
                                                    <span class="variant-sku-<?= $data[0]->product_id ?>"><?php echo $data[0]->variants[0]->sku; ?></span>
                                                </div>
                                            <?php }
                                        } ?>
                                        <?php if (!empty($data[0]->bundle) && $data[0]->bundle->use_product_quantity == 1) { ?>
                                            <div class="popup-form-grp">
                                                <strong>Qty:</strong>
                                                <input name="add_quantity" type="number" min="1" class="add_quantity form-control" value="1">   
                                            </div>
        <?php } ?>
                                        <div class="btn-add smartsell_popup_button"> 
                                            <a class="add-this-cart-upsells pro_<?= $data[0]->product_id ?>" prod-id="<?= $data[0]->product_id ?>" prod_price="<?php echo (int) $data[0]->variants[0]->price ?>">Add to cart</a> 
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
    <?php } ?>
                        </div>
                    </div>
                    <div class="separator">
                        <div class="smartsell_popup_button btn-details f-left">
                            <?php if (count($products_array) > 1) { ?>
                                <a class="skip-this btn btn-gray" href="#" data-slide='prev'> Skip & Next</a>
                            <?php
                            }
                            if (!empty($setting) && $setting->show_no_thank_link == 1) {
                                ?>
                                <button type="button" class="btn-link close_me pop-close-btn">No Thanks</button>
    <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <form method="post" action="/cart/add" class="form_<?= $data[0]->product_id ?>" id="<?= $data[0]->product_id ?>">
                        <input type="hidden" name="u_id" class="pro_id_<?= $data[0]->product_id ?>" value="<?php echo isset($data[0]->variants[0]) ? $data[0]->variants[0]->variant_id : 0; ?>" />
                        <input type="hidden" name="u_quantity" class="prod_quantity quantity_<?= $data[0]->product_id ?>" value="1" />
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>