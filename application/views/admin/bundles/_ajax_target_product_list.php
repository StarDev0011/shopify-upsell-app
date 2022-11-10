<?php
if ($prodList) {
    foreach ($prodList as $prod) {
        $idDisabled = (in_array($prod->product_id, $editSelectedTriggerAry)) ? ' disabled' : '';
        //$idDisabled = (in_array($prod->product_id, $editSelectedTargetAry)) ? ' disabled' : '';
        //$idDisabled = (empty($idDisabled) && empty($idDisabled2))?'':'Disabled';
        ?>
        <div class="copy-bundle-prod-<?= $prod->product_id ?>">
            <div class="product-search-item product-item">
                <div class="search-result-img product-item-img pull-left">
                    <a class="preview-link" target="_blank" href="<?= $prod->product_link ?>">
                        <img src="<?= $prod->image ?>" alt="<?= $prod->title ?>" class="img-responsive img-search">
                    </a>
                </div>
                <!-- <div class="search-result-options"> -->
                <div class="product-name">
                    <h5 class="product-item-name">
                        <a class="preview-link" target="_blank" href="<?= $prod->product_link ?>"> <?= $prod->title ?></a>
                    </h5>
                </div>
                <div class="product-n-price">
                    <span class="trigger-price-pro-<?= $prod->product_id ?>"><?php echo isset($prod->ProductVariants[0])?$prod->ProductVariants[0]->price:'' ?> (<?php echo $shop_currency; ?>)</span>
                </div>
                <div class="search-result-links">
                    <button type="button" data-productid="<?= $prod->product_id ?>" class="copy-bundle-product btn btn-add bundle-<?= $prod->product_id ?>" <?php echo $idDisabled; ?>>Add</button>
                </div>
                <?php if(count($prod->ProductVariants)>1){ ?>
                <div class="search-result-options">
                    <select name="trigger_variant" class="trigger_variant form-control" id="trigger_variant_<?= $prod->product_id ?>">
                        <?php
                        foreach ($prod->ProductVariants as $key2 => $ProductVariant) {
                            echo '<option pro_id="' . $prod->product_id . '" p="' . $ProductVariant->price.'" price="' . $ProductVariant->price.' ('.$shop_currency.')'. '" value="' . $ProductVariant->variant_id . '">' . $ProductVariant->variant_title.' ('.$ProductVariant->price.')'. '</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php }else{ ?>
                <input type="hidden" class="trigger_variant" id="trigger_variant_<?= $prod->product_id ?>" name="trigger_variant" value="<?= isset($prod->ProductVariants[0]->variant_id)?$prod->ProductVariants[0]->variant_id:''; ?>">
             <?php } ?>
            </div>
        </div>
        <?php
    }
}else{
    echo '<span class="norecord">No Products Found</span>';
}
?>