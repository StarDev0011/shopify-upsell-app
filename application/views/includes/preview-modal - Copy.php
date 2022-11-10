<div id="preview-<?= $bundle->id ?>" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header-new">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                
                    <div class="carousel-top">
                        <h3><?= $bundle->bundle_label ?></h3>
                    </div>
                    <div id="carousel-example-generic" class="carousel slide hidden-xs" data-ride="carousel">
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <?php
                            if (!empty($bundle->products)) {
                            $products = explode('#', $bundle->products);
                            $count = 0;
                            foreach ($prodList as $product) {
                            if (in_array($product->product_id, $products)) {
                            $count++;
                            $class = '';
                            if ($count == 1) {
                            $class = 'active';
                            }
                            ?>
                            <div class="item <?= $class ?>">
                                <div class="col-sm-6 col-center">
                                    <div class="col-item">
                                        <div class="info">
                                            <div class="row">
                                                <div class="price text-center">
                                                    <h5>
                                                    <?= $product->title ?></h5>
                                                    <!--<h5 class="text-success"> $0.00</h5>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="photo_preview">
                                            <img src="<?= $product->image ?>" class="img-responsive" alt="a" />
                                        </div>
                                        <?php if ($product->product_options != '') { ?>
                                        <div class="col-xs-12 variant_options">
                                            <?php
                                            $product_options = explode('|', $product->product_options);
                                            $option_0 = !empty($product_options[0]) ? $product_options[0] . '/' : '';
                                            $option_1 = !empty($product_options[1]) ? $product_options[1] : '';
                                            $option_2 = !empty($product_options[2]) ? '/' . $product_options[2] : '';
                                            ?>
                                            <label for="variant_options"><?php echo $option_0 . $option_1 . $option_2; ?></label>
                                            <select id="variant_options" name="variant_options" class="variant_options" style="width: auto;height:50px;">
                                                <?php foreach ($product->variants as $variant) { ?>
                                                <option productID="<?= $product->product_id ?>" price="<?php echo $variant->price ?>" value="<?php echo $variant->variant_id ?>"><?php
                                                    if ($variant->variant_title === 'Default Title') {
                                                    $variant->variant_title = 'Default Option';
                                                    }
                                                    echo $variant->variant_title . ' (' . $variant->price . ')';
                                                ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php } ?>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            }/* foreach */
                            } else {
                            ?>
                            <div class="item active">
                                <div class="row">
                                    <div class="col-sm-6 col-center">
                                        <div class="col-item">
                                            <h2> No Products Found in Bundle..!! </h2>
                                            </div><!--col-item-->
                                        </div>
                                    </div>
                                    </div><!--item-->
                                    <?php
                                    }
                                    if (!empty($bundle->products)) {
                                    ?>
                                    <div class="col-sm-6 col-center">
                                        <div class="separator_preview">
                                            <!--<div class=" btn_details_preview">
                                                <a class="btn btn-success "><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                            </div>-->
                                            <div class="btn_add_preview">
                                                <a class="btn btn-default" href="#carousel-example-generic" data-slide='prev'>Skip This Product </a>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!--/modals-->