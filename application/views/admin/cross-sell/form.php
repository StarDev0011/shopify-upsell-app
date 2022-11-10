<div id="snackbar">Please add at least one product in Target Product.</div>
<div id="snackbar_success"></div>
<div class="wrap-1000">
    <div class="card padding-20 add-bundle">
        <h3 class="title"><?php echo isset($bundleData->bundle_title) ? lang('update_crosssell_bundle') : lang('add_crosssell_bundle') ?></h3>
        <div class="response"></div>
        <form id="bundle-form" onsubmit="return false;">
            <div class="steps step1">
                <!-- Textboxs -->
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label class="custom-label" for="bundle_title"><?php echo lang('cross_sell_title') ?><span class="asterisk ">*</span></label>
                            <input class="form-control" required type="text" name="bundle_title" value="<?= isset($bundleData->bundle_title) ? $bundleData->bundle_title : '' ?>">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="devider"></div>

                <div id="discount_section">
                    <?php $this->load->view('admin/cross-sell/_discount_section', array('discountData' => $discountData, 'bundleData' => $bundleData)); ?>
                </div>

                
                <div class="clearfix"></div>
                <!-- Range Container //-->
                <div class="divider"></div>
                <div id="target_product_div">

                    <h3 class="title mb10"><?php echo lang('target_products') ?></h3>                    
                    <!-- Targeted Products -->
                    <div class="row">
                        <div class="col-md-5 col-sm-4">
                            <div class="form-group">
                                <label class="custom-label"><?php echo lang('search_by_title') ?></label>
                                <input class="form-control" maxlength="150" type="text" name="search_trigger_product" id="search_trigger_product" placeholder="Search by Product">
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-4">
                            <div class="form-group">
                                <label class="custom-label"><?php echo lang('search_by_category') ?></label>
                                <select class="form-control" id="triggered_category" name="triggered_category">
                                    <option value=""><?php echo lang('select_category') ?></option>
                                    <?php
                                    if (!empty($collectionsList))
                                    {
                                        foreach ($collectionsList as $col) {
                                            ?>
                                            <option value="<?php echo $col->collections_id; ?>"><?php echo $col->title; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <div class="form-group">
                                <label class="custom-label hidden-xs">&nbsp;</label>
                                <button class="btn btn-custom search-trigger-product" type="button"><?php echo lang('search') ?></button>
                            </div>
                        </div>
                    </div>   
                    <!-- Targeted Products // -->  
                    <!-- Targeted Products Add & Remove -->               
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="panel panel-default product-panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo lang('selecte_trigger_products') ?></h3>
                                </div>
                                <div class="panel-body" id="target-products">
                                    <?php $this->load->view('admin/cross-sell/_ajax_target_product_list',
                                            array('prodList' => $prodList, 'editSelectedTargetAry' => $editSelectedTargetAry));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="panel panel-default product-panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Selected Product</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="selected-items bundle-products-list">
                                        <?php
                                        if (!empty($editSelectedTargetAry))
                                        {
                                            foreach ($editSelectedTargetAry as $product){
                                            $variantPrice = isset($product['ProductVariants'][0]) ? $product['ProductVariants'][0]->price : '';
//                                            foreach ($bundleProds as $bp) {
                                            ?>
                                            <div class="sel-prods remove-prod-<?= $product['product_id'] ?>">
                                                <div class="product-search-item product-item">
                                                    <div class="search-result-img product-item-img">
                                                        <a class="preview-link" target="_blank" href="<?= $product['product_link'] ?>">
                                                            <img src="<?= $product['image'] ?>" alt="<?= $product['title'] ?>" class="img-responsive img-search">
                                                        </a>
                                                    </div>
                                                    <!-- <div class="search-result-options"> -->
                                                    <div class="product-name">
                                                        <h5 class="product-item-name">
                                                            <a class="preview-link" target="_blank" href="<?= $product['product_link'] ?>"> <?= $product['title'] ?></a>
                                                        </h5>                                                    
                                                    </div>
                                                    <div class="product-n-price">
                                                        <span class="price-pro-<?= $product['product_id'] ?>"><?php echo $variantPrice ?> (<?php echo $shop_currency; ?>)</span>
                                                    </div>
                                                    <div class="search-result-links">
                                                        <button type="button" data-productid="<?= $product['product_id'] ?>" class="rem-pop-product btn btn-remove"><?php echo lang('remove') ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Targeted Products Add & Remove // -->               
                </div>
                <div class="clearfix"></div>
                <div id="trigger_product_div">
                    <h3 class="title mb10"><?php echo lang('cross_sell_products') ?></h3>
                    <!-- Trigger Products Add & Remove -->               
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="custom-label"><?php echo lang('select_collection') ?></label>
                                <select class="form-control" id="collection_id" name="collection_id">
                                    <option value=""><?php echo lang('select_collection') ?></option>
                                    <?php
                                    if (!empty($collectionsList))
                                    {
                                        $selectedCollection = !empty($bundleData->collection_id) ? $bundleData->collection_id : '';
                                        foreach ($collectionsList as $col) {
                                            $isSelectedColl = ($col->collections_id == $selectedCollection) ? ' selected' : '';
                                            ?>
                                            <option value="<?php echo $col->collections_id; ?>" <?= $isSelectedColl ?>><?php echo $col->title; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <p style="margin: 10px 0;text-transform: uppercase;text-align: center;">or</p>
                             <div class="panel panel-default product-panel panel-small">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo lang('selecte_products') ?></h3>
                                </div>
                                <div class="panel-body" id="target-products">
                                    <?php $this->load->view('admin/cross-sell/_cross_sell_products',
                                            array('prodList' => $prodList, 'editSelectedTargetAry' => $editSelectedTargetAry,'editSelectedBundleProductAry'=>$editSelectedBundleProductAry));
                                    ?>
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-md-6 col-sm-6">
                            <div class="panel panel-default product-panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo lang('selected_cross_sell_products') ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="selected-trigger-items bundle-products-list">
                                        <?php
                                        if (!empty($editSelectedBundleAry))
                                        {
//                                        prExit($editSelectedBundleAry);
                                            foreach ($editSelectedBundleAry as
                                                        $bp) {
//                                                prExit($bp);
                                                $variantPrice = isset($bp['ProductVariants'][0]) ? $bp['ProductVariants'][0]->price : '';
                                                ?>
                                                <div class="trigger-prods remove-bundle-<?= $bp['product_id'] ?>">
                                                    <div class="product-search-item product-item">
                                                        <div class="search-result-img product-item-img">
                                                            <a class="preview-link" target="_blank" href="<?= $bp['product_link'] ?>">
                                                                <img src="<?= $bp['image'] ?>" alt="<?= $bp['title'] ?>" class="img-responsive img-search">
                                                            </a>
                                                        </div>
                                                        <!-- <div class="search-result-options"> -->
                                                        <div class="product-name">
                                                            <h5 class="product-item-name">
                                                                <a class="preview-link" target="_blank" href="<?= $bp['product_link'] ?>"> <?= $bp['title'] ?></a>
                                                            </h5>                                                    
                                                        </div>
                                                        <div class="product-n-price">
                                                            <span class="price-pro-<?= $bp['product_id'] ?>"><?php echo $variantPrice ?> (<?php echo $shop_currency; ?>)</span>
                                                        </div>
                                                        <div class="search-result-links">
                                                            <button type="button" data-productid="<?= $bp['product_id'] ?>" class="rem-bundle-product btn btn-remove"><?php echo lang('remove') ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Trigger Products Add & Remove -->               
                </div>
                <input type="hidden" id="shop_id" name="shop_id" value="<?= $this->shopId; ?>">
                <input type="hidden" id="target_product" name="target_product" value="<?php echo $editSelectedTarget; ?>">
                <input type="hidden" id="bundle_product" name="bundle_product" value="<?php echo $editSelectedBundle; ?>">
                <input type="hidden" name="id" id="id" value="<?= isset($bundleData->id) ? $bundleData->id : '' ?>">
                <div class="text-right">
                    <?php
                    $btnText = lang('submit');
                    if (isset($bundleData->id))
                    {
                        ?>
                        <button type="submit" class="btn btn-custom smar7-btn next-btn-dic next-btn width-150" onclick="clickUpdate(0)" curr="step2"><?php echo lang('update') ?></button> 
                        <?php
                        $btnText = lang('update_close');
                    }
                    ?>
                    <button type="submit" class="btn btn-custom smar7-btn next-btn-dic next-btn width-auto" onclick="clickUpdate(1)" curr="step2" next="step3" ><?php echo $btnText ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<?php $this->load->view('admin/cross-sell/cross_bundle_js'); ?>