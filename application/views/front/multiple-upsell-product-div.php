<style type="text/css">
    #upsell_products .overlay-bg{
        padding: 10px;
        border: 1px dashed #ccc;
        border-radius: 10px;
    }
    #upsell_products,.product-page-upsell-product,.overlay-bg,.overlay-content,.slider-containers{
        display: block;
        width: 100%;
        float: left;
    }
    .product-page-upsell-product .upsell-product{
        width: 120px;
        float: left;
        position: relative;
        padding: 0 5px;
    }
    .product-page-upsell-product .upsell-product .add-icon{
        position: absolute;
        top: 50px;
        right: -8px;
    }
    .product-page-upsell-product .upsell-product .product-variant select{
        min-height: 34px !important;
        padding: 5px 20px 5px 5px;
        background-position: right 5px center;
        border: 1px solid #e8e8e8 !important;
        background-color: #f6f8fa !important;
    }
    .product-page-upsell-product .upsell-product .product-img figure{
        width: 80px;
        height: 80px;
        margin: 0; 
        position: relative;
    }
    .product-page-upsell-product .upsell-product .product-img figure > img{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        max-height: 60px;
        max-width: 60px;
    }
    .product-page-upsell-product .upsell-footer{
        float: left;
        width: 100%;
        margin-top: 15px;
    }
    .product-page-upsell-product .upsell-footer .total-price{
        font-size: 20px;
    }
    .product-page-upsell-product .upsell-footer .btn-no-thanks{
        border: none;
        background: transparent;
        font-size: 14px;
        float: right;
    }
</style>
<div class="overlay-bg">
    <div class="overlay-content">
        <h2 class="bundle-title">
            <?= $bundle_title ?>
        </h2>
        <div class="slider-containers">
            <div id="slider-wrappers" class="product-page-upsell-product">
                <div>
                    <?php
                    $count = 0;
                    $totalUpsells = count($products_array);
                    if ($products_array) {
                        $totalPrice = 0;
                        foreach ($products_array as $key => $data) {
                            $class = '';
                            $count++;
                            if ($count == 1) {
                                $class = 'active';
                            }
                            ?>
                            <div class="upsell-product" id="p_<?= $data[0]->product_id; ?>" prod-id="<?= $data[0]->product_id; ?>" data-use_product_quantity="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-bundle-name="<?php echo $data[0]->bundle->bundle_title; ?>" data-bundle-id="<?php echo $data[0]->bundle->id; ?>">
                                <div class="product-item">
                                    <div class="product-img">
                                        <figure>
                                            <img src="<?= $data[0]->image ?>" class="img-responsive" alt="a" />
                                        </figure>
                                    </div>
                                    <div class="product-detail">
                                        <h5 class="name"><?php echo $data[0]->title; ?></h5>
                                        <?php
                                        if (isset($data[0]->variants)) {
                                            $totalPrice += $data[0]->variants[0]->price;
                                            if ($key != 0) {
                                                ?>
                                                <div class="product-variant">
                                                    <div class="custom-control">
                                                        <select id="variant_options_<?= $data[0]->product_id; ?>" name="variant_options" class="variant_options form-control">
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
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>

                                            <?php }else{ ?>
                                               <input type="hidden" name="mprice" id="mprice" value="<?= $variantPrice ?>" /> 
                                         <?php   }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <form method="post" action="/cart/add" class="form_<?= $data[0]->product_id ?>" id="<?= $data[0]->product_id ?>">
                                    <input type="hidden" name="u_id" class="pro_id_<?= $data[0]->product_id ?>" value="<?php echo isset($data[0]->variants[0]) ? $data[0]->variants[0]->variant_id : 0; ?>" />
                                    <input type="hidden" name="u_quantity" class="prod_quantity quantity_<?= $data[0]->product_id ?>" value="1" />
                                </form>
                                <?php if ($count < $totalUpsells) { ?>
                                <img src="<?php echo $this->config->item('upsell_plus'); ?>" alt="more upsell" class="add-icon"  height="16" width="16"/>
                            <?php
                            } ?>
                            </div>                             
                                
                            <?php } ?>
                        <div class="upsell-footer">
                            <div>
                                <h4 class="total-price">Total Payable Amount: <span id="total_upsell_price"><?php echo $totalPrice; ?></span></h4>
                                <button class="add-upsell-cart btn">
                                    Add all Product
                                </button>    
                            </div>
                            
                        
                            <div class="smartsell_popup_button btn-details">
                                <?php
                                if (!empty($setting) && $setting->show_no_thank_link == 1) {
                                    ?>
                                    <button type="button" class="btn-link close_me pop-close-btn btn-no-thanks">No Thanks</button>
                                <?php } ?>
                            </div>
                        </div>                        
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" class="bundle_id" id="popup_bundle_id" value="<?= $bundle_id; ?>" />
    <input type="hidden" class="pr_id" id="pr_id" value="<?= $firstProduct; ?>" />
    <input type="hidden" class="cart_token" id="cart_token" value="" />
</div>