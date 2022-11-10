<!--<div id="product-modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg"> 
    <div class="modal-content">
      <div class="modal-body">-->
<div class="container">
    <div class="row overlay-title">
        <div class="col-sm-6 clearfix"> Select Products </div>
        <div class="col-sm-6 clearfix text-right">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
    </div>
    <div class="row products-filter">
        <div class="col-sm-6 pull-left">
            <div class="input-group" id="ser_div_btn" style="display:none">
                Search By Products <input type="radio" name="ser_btn" checked="checked" class="ser_div_btn" value="product_ser_div" />
                &nbsp;
                Search By Collections <input type="radio" name="ser_btn" class="ser_div_btn" value="collections_ser_div"/>
            </div>
            <div class="input-group ser_div" id="collections_ser_div" style="display:none">
                <input class="form-control form-item search-product-query" autocomplete="off" type="text" name="collections_keyword" value="" id="collections_keyword" placeholder="Enter atleast 3 chracters of collections title">
                <span class="input-group-btn">
                    <button class="btn btn-primary new-bundle-btn search-product-button" id="collections_search" type="button">SEARCH</button>
                </span> </div>
            <div class="input-group ser_div" id="product_ser_div">
                <input class="form-control form-item search-product-query" autocomplete="off" type="text" name="keyword" value="" id="keyword" placeholder="Enter atleast 3 chracters of product title">
                <span class="input-group-btn">
                    <button class="btn btn-primary new-bundle-btn search-product-button" id="search" type="button">SEARCH</button>
                </span> </div>

            <!--suggesstion box -->
            <div class="suggestion_div" style="min-height: 50px;position: absolute;z-index: 9999;width: 93%;background-color: rgb(255, 255, 255);color: rgb(0, 0, 0); display:none;"></div>
        </div>
        <div class="col-sm-6 pull-right product-collections collection-dest"> 
            <select name="collections" id="collections" class="product-collections-select form-control form-item" placeholder="Select a collection..." tabindex="-1">
                <option value="0" selected="selected">Collection: All</option>
                <?php
                if ($collectionsList) {
                    foreach ($collectionsList as $collection) {
                        ?>
                        <option value="<?php echo $collection->collections_id; ?>"><?php echo $collection->title; ?></option>
                    <?php }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row products-selection">
        <div class="col-sm-6 pull-left animated fadeInLeft">
            <div class="results-field products-column" style="height: 161px;">
                <div class="results-bar pull-left clearfix">
                    <div class="pull-left"> Results </div>
                    <div class="pull-right"> <a class="select-all text-danger" id="fetchall" href="#" style="color:#794b4b; font-size:11px; font-weight:700;">Clear Search</a> </div>
                </div>
                <!--results-bar-->
                <div style="clear:both"></div>
                <div class="search-results my-search-results bundle-products-list"> 
                    <!--Products Listings-->
                    <?php
                    if ($prodList) {
                        foreach ($prodList as $prod) {
                            if (!in_array($prod->product_id, $Allready_SelProducts)) {
                                ?>
                                <div class="copy-prod-<?= $prod->product_id ?>">
                                    <div class="product-search-item product-item col-xs-12">
                                        <div class="search-result-img product-item-img pull-left"> 
                                            <a class="preview-link" target="_blank" href="<?= $prod->product_link ?>">
                                                <img src="<?= $prod->image ?>" alt="<?= $prod->title ?>" class="img-thumbnail img-search"> 
                                            </a>
                                        </div>
                                        <div class="search-result-options">
                                            <h5 class="product-item-name">
                                                <a class="preview-link" target="_blank" href="<?= $prod->product_link ?>"> <?= $prod->title ?></a>
                                            </h5>
                                                <?php if ($prod->product_options != '') { ?>
                                                <div class="col-xs-12 search-result-options">
                                                    <?php
                                                    $product_options = explode('|', $prod->product_options);
                                                    $option_0 = (!empty($product_options[0] != '')) ? $product_options[0] . '/' : '';
                                                    $option_1 = (!empty($product_options[1])) ? $product_options[1] : '';
                                                    $option_2 = (!empty($product_options[2])) ? '/' . $product_options[2] : '';
                                                    ?>
                                                    <label for="sel_variant"><?php echo $option_0 . $option_1 . $option_2; ?></label>
                                                    <select name="sel_variant" class="sel_variant" id="sel_variant_<?= $prod->product_id ?>">
                                                        <?php
                                                        foreach ($prod->ProductVariants as $key2 => $ProductVariant) {
                                                            echo '<option pro_id="' . $prod->product_id . '" value="' . $ProductVariant->variant_id . '">' . $ProductVariant->variant_title . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
            <?php } ?>
                                        </div>
                                        <div class="search-result-links pull-right"> 
                                            <a class="preview-link btn btn-default" target="_blank" href="<?= $prod->product_link ?>">Preview</a>
                                            <button type="button" data-productid="<?= $prod->product_id ?>" class="copy-pop-product btn btn-success">Add</button>
                                        </div>
                                    </div>
                                    <!--product-search-item--> 
                                </div>
                                <!--parent-->
                                <?php
                            }
                        }/* foreach */
                    }
                    ?>
                </div>
                <!--search-results--> 
            </div>
        </div>
        <!--col-sm-6--> 
        <!-- right Side -->
        <div class="col-sm-6 pull-right animated fadeInRight">
            <div class="collection-mob product-collections">
                <div class="col-sm-6 pull-right product-collections collection-dest"> 
                    <select class="product-collections-select form-control form-item" placeholder="Select a collection..." tabindex="-1">
                        <option value="0" selected="selected">Collection: All</option>
                        <?php
                        if ($collectionsList) {
                            foreach ($collectionsList as $collection) {
                                ?>
                                <option value="<?php echo $collection->collections_id; ?>"><?php echo $collection->title; ?></option>
    <?php }
}
?>
                    </select>
                </div>
            </div>
            <div class="results-field products-column" style="height: 161px;">
                <div class="results-bar pull-left">
                    <div class="pull-left"> Selected Products
                        <button type="button"  class="btn btn-primary smar7-btn add-prods"> Submit </button>
                    </div>
                    <div class="pull-right"> 
                        <!--<a class="remove-all red-link" href="#">Remove All</a>--> 
                    </div>
                </div>
                <div style="clear:both"></div>
                <div class="selected-items bundle-products-list">
                    <?php
                    if ($bundle_ptype === 'p') {
                        $selectedProds = $bundleProds;
                    } else {
                        $selectedProds = $bundleTrigers;
                    }
                    if (count($selectedProds) > 0) {
                        foreach ($selectedProds as $key => $prod) {
                            $product = $this->products->get_product_by_id($prod->product_id);
                            ?>
                            <div id="item_<?= $prod->product_id ?>" class="sel-prods remove-prod-<?= $prod->product_id ?>" >
                                <div class="product-search-item product-item">
                                    <div class="search-result-img product-item-img pull-left">
                                        <?php if ($product[0]->image != '') { ?>
                                            <a class="preview-link" target="_blank" href="<?= $prod->product_link ?>"><img src="<?= $product[0]->image ?>" alt="<?= $prod->title ?>" class="img-thumbnail img-search"></a>
        <?php } ?>
                                    </div>
                                    <div class="search-result-options">
                                        <h5 class="product-item-name">
                                            <a class="preview-link" target="_blank" href="<?= $prod->product_link ?>"><?= $product[0]->title ?></a>
                                        </h5>
                                            <?php if ($product[0]->product_options != '') { ?>
                                            <div class="col-xs-12 search-result-options">
                                                <?php
                                                $product_options = explode('|', $prod->product_options);
                                                $option_0 = ($product_options[0] != '') ? $product_options[0] . '/' : '';
                                                $option_1 = ($product_options[1] != '') ? $product_options[1] . '/' : '';
                                                $option_2 = ($product_options[2] != '') ? $product_options[2] : '';

                                                //echo $bundle_ptype;
                                                ?>

                                                <label for="sel_variant"><?php echo $option_0 . $option_1 . $option_2; ?></label>
                                                <select name="sel_variant" class="sel_variant" id="sel_variant_<?= $prod->product_id ?>">
                                                    <?php
                                                    foreach ($prod->ProductVariants as $key2 => $ProductVariant) {
                                                        $sel = '';

                                                        if ($bundle_ptype === 'p') {
                                                            if ($ProductVariant->variant_id === $Allready_ProductsVariant[$key]) {
                                                                $sel = 'selected="selected"';
                                                            }
                                                        } else if ($bundle_ptype === 't') {
                                                            if ($ProductVariant->variant_id === $Allready_TriggersVariant[$key]) {
                                                                $sel = 'selected="selected"';
                                                            }
                                                        }

                                                        echo '<option ' . $sel . ' pro_id="' . $prod->product_id . '" value="' . $ProductVariant->variant_id . '">' . $ProductVariant->variant_title . '</option>';
                                                    }
                                                    ?>
                                                </select></div>
        <?php } ?>
                                    </div>
                                    <div class="search-result-links pull-right">
                                        <a class="preview-link btn btn-default" target="_blank" href="<?= $product[0]->product_link ?>">Preview</a>
                                        <button type="button" data-productid="<?= $prod->product_id ?>" class="btn btn-danger rem-pop-product">Remove</button>
                                    </div>
                                    <!--product-search-item--> 
                                </div>
                            </div>
    <?php }
} ?>
                </div>
                <div class="clearfix"></div>
                <!--<div class="col-sm-12">
                  <button type="button"  class="btn btn-primary smar7-btn center-block add-prods">Continue With Selected Products</button>
                </div>-->
            </div>
        </div>
        <!--right--> 

    </div>
</div>
<!-- </div>
</div>
<div class="clearfix"></div>
</div>
</div>-->
