<div id="slider-wrapper">
    <div id="slider">
        <button type="button" class="close close_me pop-close-btn">×</button>
        <?php
        $count = 0;
        foreach ($products_array as $key => $data) {
            $class = '';
            $count++;
            if ($count == 1) {
                $class = 'active';
            }
            ?>
            <div class="sp <?= $class ?>" prod-id="<?= $data[0]->product_id; ?>">
                <div class="col-item animated fadeInLeft border-top"> 
                    <div class="photo"> <img src="<?= $data[0]->image ?>" style="width:20%;" class="img-responsive" alt="a" />
                        <div class="resp response-<?= $data[0]->product_id ?>"></div>
                    </div>
                    <div class="clearfix"></div>
                    <?php if (isset($data[0]->variants)) { ?>
                        <div class="separator clear-left text-center pt40">
                            <select id="variant_options" name="variant_options" class="variant_options" style="width: auto;height:50px;">
                                <?php foreach ($data[0]->variants as $variant) { ?>
                                    <option productID="<?= $data[0]->product_id ?>" price="<?php echo $variant->price ?>" value="<?php echo $variant->variant_id ?>">
                                        <?php
                                        if ($variant->variant_title === 'Default Title') {
                                            $variant->variant_title = 'Default Option';
                                        }
                                        echo $variant->variant_title . ' (' . $shop_currency . ' ' . $variant->price . ')';
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <div class="btn-add smartsell_popup_button"> <a class="add-this-cart-upsells pro_<?= $data[0]->product_id ?>" prod-id="<?= $data[0]->product_id ?>" prod_price="<?php echo (int) $data[0]->variants[0]->price ?>">Add to cart</a> </div>
                            <div class="clearfix"></div>
                        </div>
                    <?php } ?>
                </div>
                <!--col-item-->
                <div class="separator clear-left">
                    <div class="smartsell_popup_button btn-details f-left">
                        <?php if (count($products_array) > 1) { ?>
                            <a class="skip-this" href="#" data-slide='prev'> Skip This Product </a>
                        <?php } ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php //prExit($data[0]->variants[0]->variant_id); ?>
                <form method="post" action="/cart/add" class="form_<?= $data[0]->product_id ?>" id="<?= $data[0]->product_id ?>">
                    <input type="hidden" name="id" class="pro_id_<?= $data[0]->product_id ?>" value="<?php echo isset($data[0]->variants[0])?$data[0]->variants[0]->variant_id:0; ?>" />
                </form>
            </div>
            <?php
        }
        ?>
    </div></div>
