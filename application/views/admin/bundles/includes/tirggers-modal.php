<div id="trigger-<?=$bundle->id?>" class="modal fade" role="dialog">
	<div class="modal-dialog bundle-preview-modal">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="custom-title"><?= $bundle->bundle_label ?> - Bundle Product</h3>
            </div>
            <div class="modal-body">
                <div id="carousel-example-generic-bundle-<?= $bundle->id ?>" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <?php
                        if (!empty($bundle->triggers)) {
                            $count = 0;
                            foreach ($prodList as $k=>$product) {
                                    $count++;
                                    $class = '';
                                    if ($count == 1) {
                                        $class = 'active';
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
                                        <a class="btn btn-gray" href="#carousel-example-generic-bundle-<?= $bundle->id ?>" data-slide='next'>Skip & Next </a>
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
	