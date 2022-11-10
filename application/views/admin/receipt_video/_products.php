<?php
if ($prodList) {
    foreach ($prodList as $prod) {
        //$idDisabled = (in_array($prod->product_id, $editSelectedTargetAry)) ? ' disabled' : '';
        ?>
        <div class="copy-prod-<?= $prod->product_id ?>">
            <div class="product-search-item product-item">
                <div class="search-result-img product-item-img">
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
                    <span class="price-pro-<?= $prod->product_id ?>">(<?php echo $shopCurrency; ?>)</span>
                </div>
                <div class="search-result-links">
                    <button type="button" data-productid="<?= $prod->product_id ?>" class="copy-pop-product btn btn-add trig-<?= $prod->product_id ?>">Add</button>
                </div>
            </div>
        </div>
        <?php
    }
}else{
    echo '<span class="norecord">No Products Found</span>';
}