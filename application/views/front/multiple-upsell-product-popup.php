<div class="overlay-bg">
        <div class="overlay-content">
        <h2 class="bundle-title">
            <?= $bundle_title ?>
        </h2>
        <div class="slider-containers">
            <div id="slider-wrappers" class="upsell-product-popup">
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
                            if ($key == 0) {
                                ?>
                                <div class="sp upsell-product" id="p_<?= $data[0]->product_id; ?>" prod-id="<?= $data[0]->product_id; ?>" data-use_product_quantity="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-bundle-name="<?php echo $data[0]->bundle->bundle_title; ?>" data-bundle-id="<?php echo $data[0]->bundle->id; ?>">
                                    <div class="product-item">
                                        <div class="product-img">
                                            <figure>
                                                <img src="<?= $data[0]->image ?>" class="img-responsive" alt="a" width="150" height="150"/>
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

                                                    <?php } else {
                                                    ?>
                                                    <input type="hidden" name="mprice" id="mprice" value="<?= $data[0]->variants[0]->price ?>" />
                                                <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <form method="post" action="/cart/add" class="form_<?= $data[0]->product_id ?>" id="<?= $data[0]->product_id ?>">
                                        <input type="hidden" name="u_id" class="pro_id_<?= $data[0]->product_id ?>" value="<?php echo isset($data[0]->variants[0]) ? $data[0]->variants[0]->variant_id : 0; ?>" />
                                        <input type="hidden" name="u_quantity" class="prod_quantity quantity_<?= $data[0]->product_id ?>" value="1" />
                                    </form>
                                    <img class="add-icon" src="<?php echo $this->config->item('upsell_plus'); ?>" alt="more upsell"  height="20" width="20"/>
                                </div>

                                <?php } else { ?>
                                <div class="sp upsell-product" id="p_<?= $data[0]->product_id; ?>" prod-id="<?= $data[0]->product_id; ?>" data-use_product_quantity="<?php echo $data[0]->bundle->use_product_quantity; ?>" data-bundle-name="<?php echo $data[0]->bundle->bundle_title; ?>" data-bundle-id="<?php echo $data[0]->bundle->id; ?>">
                                    <div class="product-item">
                                        <div class="product-img">
                                            <figure>
                                                <img src="<?= $data[0]->image ?>" class="img-responsive" alt="a" width="150" height="150"/>
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

                                                    <?php } else {
                                                    ?>
                                                    <input type="hidden" name="mprice" id="mprice" value="<?= $data[0]->variants[0]->price ?>" />
                                                <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <form method="post" action="/cart/add" class="form_<?= $data[0]->product_id ?>" id="<?= $data[0]->product_id ?>">
                                        <input type="hidden" name="u_id" class="pro_id_<?= $data[0]->product_id ?>" value="<?php echo isset($data[0]->variants[0]) ? $data[0]->variants[0]->variant_id : 0; ?>" />
                                        <input type="hidden" name="u_quantity" class="prod_quantity quantity_<?= $data[0]->product_id ?>" value="1" />
                                    </form>
                                    <?php if ($count < $totalUpsells) { ?>
                                <img class="add-icon" src="<?php echo $this->config->item('upsell_plus'); ?>" alt="more upsell"  height="20" width="20"/>
                                <?php
                            } ?>
                                </div>
                            <?php } ?>

                    <?php } ?>


                        <div class="upsell-footer">
                            <?php if (!empty($data[0]->bundle) && $data[0]->bundle->use_product_quantity == 1) {  ?>
                                <div class="popup-form-grp add-qty">
                                    <strong>Qty:</strong>
                                    <input name="add_quantity" id="add_multiple_quantity" type="number" min="1" class="add_quantity form-control" value="1">
                                </div>
                            <?php } ?>

                            <label class="total-price">Total:</label> <label id="total_upsell_price" class="total-price"><?php echo $totalPrice; ?></label>
                            <button class="add-upsell-cart">Add to Cart</button>

                            <div class="smartsell_popup_button btn-details pull-right">
                                <?php
                                if (!empty($setting) && $setting->show_no_thank_link == 1) {
                                    ?>
                                    <button type="button" class="btn-link close_me pop-close-btn">No Thanks</button>
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

<style>
@import url("https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css");
.upsell-product-popup{
    width: 100%;
    display: block;
    float: left;
}
.upsell-product-popup .upsell-product{
    width: 165px;
    display: inline-block;
    float: left;
    padding: 0 10px;
    position: relative;
    min-height: 212px;
}
.upsell-product-popup .upsell-product .product-img figure {
    height: 110px;
    width: 110px;
    margin: 0 auto;
}
.upsell-product-popup .upsell-product .product-img figure >img {
    max-height: 100px;
    max-width: 100px;
}
.upsell-product-popup .upsell-product .product-detail .name{
    margin: 10px 0px 0 0;
    text-align: center;
}
.upsell-product-popup .upsell-product .add-icon{
    position: absolute;
    top: 40px;
    right: -10px;
}
.upsell-product-popup .upsell-footer{
    float: left;
    width: 100%;
    display: block;
    border-top: 1px solid #DDD;
        padding-top: 15px;
}
.upsell-product-popup .upsell-footer .total-price{
    font-size: 24px;
    font-weight: bold;
    font-family: "Work Sans",sans-serif;
    text-align: left;
    display: inline-block;
    vertical-align: top;
    margin: 0;
}
.upsell-product-popup .upsell-footer .smartsell_popup_button{
    display: block;
}
.upsell-product-popup .upsell-footer .smartsell_popup_button .btn-link{
    float: left;
    margin-top: 10px;
}
.upsell-product-popup .upsell-footer .add-qty{
    display: inline-block;
    vertical-align: top;
    width: 180px;
}
.btn-link {
    background: none;
    border: none;
    font-size: 14px
}


.popup-form-grp strong {
    margin-right: 10px
}

.popup-form-grp .add_quantity {
    width: 100px;
    display: inline-block;
    vertical-align: middle
}

.smartsell_popup_button {
    display: inline-block
}

.smartsell_popup_button .add-this-cart-upsells {
    background: #287dee;
    line-height: 44px;
    padding: 0 30px;
    display: block;
    color: #fff;
    border-radius: 2px;
    margin-top: 20px
}

.product-variant {
    padding: 5px 0;
    text-align: left
}

.variant_options:focus {
    outline: none
}

.text-center {
    text-align: center
}

.skip-this {
    font-size: 12px
}

.smartsell_popup_button .add-this-cart-upsells:hover {
    cursor: pointer
}

.border-top {
    border-top: 1px solid #ddd;
    padding-top: 30px;
    border-radius: 0 !important;
    margin-top: 10px
}

.pt40 {
    padding-top: 40px !important
}

.bundle-title {
    color: #267cee;
    font-size: 20px;
    text-align: left;
    line-height: 1.4;
    border-bottom: 1px solid #e5e5e5;
    padding: 15px;
    text-transform: capitalize;
    letter-spacing: 0;
    font-weight: normal;
    margin-bottom: 0
}

.slider-container {
    padding: 20px
}

.overlay-bg {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh !important;
    width: 100%;
    cursor: pointer;
    z-index: 1000;
    background: #000;
    background: rgba(0, 0, 0, 0.75)
}

.overlay-content {
    background: #fff;
    padding-bottom: 0;
    width: 700px;
    max-width: 100%;
    position: absolute;
    top: 50% !important;
    left: 0;
    right: 0;
    margin: 0 auto;
    transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    cursor: default;
    border-radius: 4px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.9);
    height: 96%;
    overflow: auto;
}

.overlay-content:after {
    clear: both;
    display: block;
    height: 1px;
    content: ''
}

.close-btn {
    cursor: pointer;
    border: 1px solid #333;
    padding: 2% 5%;
    background: #a9e7f9;
    background: -moz-linear-gradient(top, #a9e7f9 0%, #77d3ef 4%, #05abe0 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #a9e7f9), color-stop(4%, #77d3ef), color-stop(100%, #05abe0));
    background: -webkit-linear-gradient(top, #a9e7f9 0%, #77d3ef 4%, #05abe0 100%);
    background: -o-linear-gradient(top, #a9e7f9 0%, #77d3ef 4%, #05abe0 100%);
    background: -ms-linear-gradient(top, #a9e7f9 0%, #77d3ef 4%, #05abe0 100%);
    background: linear-gradient(to bottom, #a9e7f9 0%, #77d3ef 4%, #05abe0 100%);
    border-radius: 4px;
    box-shadow: 0 0 4px rgba(0, 0, 0, 0.3)
}

.close-btn:hover {
    background: #05abe0
}

#slider-wrapper {
    width: 100%;
    text-align: right;
    overflow: hidden
}

#slider {
    width: 100%;
    position: relative
}

#nav {
    margin-top: 20px;
    width: 100%
}

#button-previous {
    float: left
}

#button-next {
    float: right
}

.col-item {
    border-radius: 5px;
    background: #FFF
}

.col-item .photo img {
    margin: 0 auto;
    max-height: 300px;
    width: auto !important;
    height: 90%
}

.col-item .info {
    padding-left: 10px;
    padding-bottom: 5px;
    border-radius: 0 0 5px 5px
}

.col-item:hover .info {
    background-color: #F5F5DC
}

.col-item .price {
    text-align: center;
    margin-top: 5px
}

.col-item .price h5 {
    line-height: 20px;
    margin: 0
}

.price-text-color {
    color: #219FD1
}

.col-item .info .rating {
    color: #777
}

.col-item .rating {
    float: left;
    font-size: 17px;
    text-align: right;
    line-height: 52px;
    margin-bottom: 10px;
    height: 52px
}

.clear-left {
    clear: left
}

.col-item .separator p {
    line-height: 20px;
    margin-bottom: 10px;
    margin-top: 10px;
    text-align: right
}

.col-item .separator p i {
    margin-right: 5px
}

.col-item .btn-add {
    margin-right: 10px
}

.col-item .btn-details {
    width: 50%;
    float: left;
    padding-left: 10px
}

.controls {
    margin-top: 20px
}

[data-slide="prev"] {
    margin-right: 10px
}

.col-center {
    float: none;
    margin: 0 auto
}

.carousel-top h3 {
    color: #fff;
    font-weight: bold;
    text-align: center;
    margin-bottom: 40px
}

.modal-header-new {
    padding-right: 15px
}

.text-success {
    color: #5cb85c
}

.photo {
    text-align: center;
    max-height: 300px
}

a.btn.btn-primary.pop-close-btn {
    margin-bottom: 4px
}

button.close {
    background-color: transparent;
    border: none;
    font-size: 25px;
    font-weight: bold;
    color: #7796a8;
    position: relative;
    top: -5px
}

.photo .text.tex-center {
    font-size: 18px;
    font-weight: bold;
    -webkit-animation-name: example;
    -webkit-animation-duration: 1s;
    -webkit-animation-iteration-count: infinite;
    animation-name: flashy;
    animation-duration: 1s;
    transition-timing-function: ease;
    animation-iteration-count: 1;
    color: green
}

.product-item {
    width: 100%
}


.main-product-div {
    display: block;
    width: 100%;
}
.main-product-div .product-item {
    width: auto;
    margin: 0 auto;
    display: table;
    float: none;
}


.main-product-div .product-item .product-img{
    height: 220px;
    width: 220px;
    display: inline-block;
    float: left;
    vertical-align: top;
}
.main-product-div .product-item .product-detail{
    width: calc(100% - 220px);
    display: inline-block;
    float: left;

}
.product-item .product-img figure {
    height: 200px;
    width: 200px;
    display: block;
    border: 1px solid #d3d3d3;
    border-radius: 5px;
    position: relative;
    margin: 0
}

.product-item .product-img figure >img {
    max-height: 150px;
    max-width: 150px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%)
}

.product-item .product-detail .name {
    font-size: 18px;
    color: #000000;
    text-transform: capitalize;
    margin: 10px 15px 0 0;
    text-align: left
}

.product-item .product-detail .variant_options {
    padding: 0 30px 0 15px;
    text-align: left;
    width: 240px
}

.product-item .product-detail .variant_options .variant-label {
    font-size: 16px
}

.btn-gray {
    padding: 10px 20px !important;
    font-size: 16px;
    background: #b8bec4;
    color: #fff;
    text-transform: capitalize;
    font-weight: normal;
    letter-spacing: 0
}

.btn-gray:hover, .btn-gray:focus, .btn-gray:active {
    background: #b8bec4;
    color: #fff;
    outline: 0 !important;
    box-shadow: none
}

.slider-container .separator {
    position: absolute;
    right: 0;
    bottom: 0;
    padding: 0
}

.custom-control .form-control {
    border: 1px solid #e8e8e8 !important;
    background-color: #f6f8fa !important;
    box-shadow: none !important;
    height: 36px !important;
    line-height: 36px;
    padding: 0px 15px
}

.custom-control select.form-control {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    padding-right: 30px;
    background-repeat: no-repeat;
    background-position: right 10px center
}

@keyframes flashy {
    0% {
        color: red;
        letter-spacing: 1px
    }

    25% {
        letter-spacing: 2px
    }

    50% {
        letter-spacing: 3px
    }

    75% {
        letter-spacing: 2px
    }

    100% {
        color: green;
        letter-spacing: 1px
    }

}

.f-left {
    float: left
}

.separator.clear-left {
    padding-top: 25px
}

.slider-containers {
    width: 100%;
    float: left;
    padding: 15px
}

.upsell-product {
    float: left;
    margin-bottom: 20px
}


.slider-containers .add-upsell-cart {
    background-color: #557b97;
    color: #fff;
    font-family: "Work Sans",sans-serif;
    border: none;
    line-height: 40px;
    padding: 0 15px;
    border-radius: 3px;
    float: right;
}

.slider-containers .add-upsell-cart img {
    top: 0
}

.slider-containers h2 #total_upsell_price {
    float: left;
    margin-left: 40px;
    position: relative;
    top: 40px
}

@media (max-width:767px) {
    .product-item .product-img, .product-item .product-img figure, .product-item .product-detail {
        width: 100%;
        display: block
    }

    .product-item .product-img, .product-item .product-img figure {
        max-width: 200px;
        max-height: 200px;
        margin: 0 auto;
    }
    .product-item .product-detail .variant_options{
        width: 100%;
    }
    .related-product-div .upsell-product .add-icon {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
    }

    .related-product-div .upsell-product {
        margin-bottom: 30px;
    }

    .product-item .product-img figure >img {
        max-width: 95%;
        max-height: 140px
    }

    .slider-container .separator {
        position: relative;
        right: 0;
        bottom: 0;
        padding: 20px 0;
        text-align: left
    }

    .product-item .product-detail .name {
        margin: 15px 0
    }

    .product-variant {
        padding: 15px 0
    }

    .overlay-content {
        max-height: 530px;
        overflow-y: auto
    }

    .upsell-product-popup .upsell-product {
        width: 50%
    }
    .upsell-product-popup .upsell-footer .add-qty,
    .upsell-product-popup .upsell-footer .total-price,
    .slider-containers .add-upsell-cart{
        width: 100%;
        margin-bottom: 10px;
    }
    .upsell-product-popup .upsell-product .add-icon{opacity: 0;}
    .slider-containers img {
        top: 0px;
        margin-bottom: 20px
    }

    .slider-containers {
        padding-bottom: 15px
    }
    .main-product-div .product-item .product-detail {
        width: 100%;
    }
    .related-product-div .upsell-product {
        width: 100%;
    }

}

@media (max-width:480px) {
    .overlay-content {
        width: 90% !important;
        top: 20px !important;
        transform: none
    }

    .btn-details.f-left {
        float: none !important;
        margin-bottom: 6px !important
    }

    .separator.clear-left {
        text-align: center !important
    }

}

</style>
