<div id="preview-<?= $bundle->id ?>" class="modal fade" role="dialog">
    <div class="modal-dialog bundle-preview-modal">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="custom-title"><?= $bundle->bundle_label ?> - Available Product</h3>
            </div>
            <div class="modal-body">
                <div id="carousel-example-generic<?= $bundle->id ?>" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <?php
                        if (!empty($bundle->products)) {
                            $count = 0;
                            $singleProduct = [];
                            $defaultVariant = '';
                            foreach ($bundle->products as $k=>$product) {
                                    $count++;
                                    $class = '';
                                    if ($count == 1) {
                                        $class = 'active';
                                        $singleProduct = $product;
                                    }
                                    ?>
                                    <div class="item <?= $class ?>" data-slide-to="<?= $k ?>">
                                        <div class="product-item">
                                            <div class="product-img">
                                                <figure>
                                                    <img src="<?= $product->image ?>" class="img-responsive" alt="a" />    
                                                </figure>                                        
                                            </div>
                                            <div class="product-detail">
                                                <h5 class="name"><?= $product->title ?></h5>
                                                <?php if ($product->product_options != '') { ?>
                                                    <div class="variant_options">
                                                        <?php
                                                        $product_options = explode('|', $product->product_options);
                                                        $option_0 = !empty($product_options[0]) ? $product_options[0] : '';
                                                        $option_1 = !empty($product_options[1]) ? '/'. $product_options[1] : '';
                                                        $option_2 = !empty($product_options[2]) ? '/' . $product_options[2] : '';
                                                        ?>
                                                        <label class="variant-label" for="variant_options"><?php echo $option_0 . $option_1 . $option_2; ?></label>
                                                        <div class="custom-control">
                                                            <select id="variant_options" name="variant_options" class="variant_options form-control" style="width: 100%;height:50px;">
                                                                <?php foreach ($product->variants as $k=>$variant) { 
                                                                        if($k==0)
                                                                            $defaultVariant = $variant->variant_id;
                                                                        ?>
                                                                    <option productID="<?= $product->product_id ?>" price="<?php echo $variant->price ?>" value="<?php echo $variant->variant_id ?>"><?php
                                                                        if ($variant->variant_title === 'Default Title') {
                                                                            $variant->variant_title = 'Default Option';
                                                                        }
                                                                        echo $variant->variant_title . ' (' . $variant->price . ')';
                                                                        ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                            }/* foreach */
                        } else {
                            ?>
                            <div class="item active">
                                <h2 class="norecord"> No product found..!! </h2>
                                <button type="button" class="close-modal btn btn-gray" data-dismiss="modal">Close</button>
                            </div><!--item-->
                            <?php
                        }
                        if (!empty($bundle->products)) {
                            ?>

                            <div class="separator_preview">
                                <!--<div class=" btn_details_preview">
                                    <a class="btn btn-success "><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                </div>-->
                                <div class="btn_add_preview">
                                    <?php if ($count > 1) { ?>
                                        <a class="btn btn-gray" href="#carousel-example-generic<?= $bundle->id ?>" data-slide='next'>Skip & Next </a>
                                    <?php } ?>
                                    <button type="button" class="close-modal btn btn-gray" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--/modals-->