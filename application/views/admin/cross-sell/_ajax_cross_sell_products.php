<?php
if ($prodList) {
    foreach ($prodList as $prod) {
        $idDisabled = (in_array($prod->product_id, $editSelectedBundleAry)) ? ' disabled' : '';
        //$idDisabled = (in_array($prod->product_id, $editSelectedTargetAry)) ? ' disabled' : '';
        //$idDisabled = (empty($idDisabled) && empty($idDisabled2))?'':'Disabled';
        ?>
        <div class="remove-bundle-<?= $prod->product_id ?>">
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
                    <button type="button" data-productid="<?= $prod->product_id ?>" class="rem-bundle-product btn btn-remove bundle-<?= $prod->product_id ?>" <?php echo $idDisabled; ?>>Delete</button>
                </div>
            </div>
        </div>
        <?php
    }
}else{
    echo '<span class="norecord">No Products Found</span>';
}
?>