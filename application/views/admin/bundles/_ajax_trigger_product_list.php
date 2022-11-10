<?php
if ($prodList) {
    foreach ($prodList as $prod) {
        $idDisabled = (in_array($prod->product_id, $editSelectedTargetAry)) ? ' disabled' : '';

        $query = "SELECT product_id FROM bundle_products WHERE product_id = '" . $prod->product_id .  "' AND type = 't';";
        $otherBundle = $this->db->query($query)->row();
        $existsElsewhere = false;
        if ($otherBundle && !$idDisabled) {
            $existsElsewhere = true;
            echo "This product is in another bundle";
        }

        //$idDisabled = (in_array($prod->product_id, $editSelectedTriggerAry)) ? ' disabled' : '';
        //$idDisabled = (empty($idDisabled) && empty($idDisabled2))?'':'Disabled';
        $variantPrice = isset($prod->ProductVariants[0])?$prod->ProductVariants[0]->price:'';
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
                    <span class="price-pro-<?= $prod->product_id ?>"><?php echo $variantPrice ?> (<?php echo $shop_currency; ?>)</span>
                </div>
                <div class="search-result-links">
                    <?php if (!$existsElsewhere) {
                        echo '<button type="button" data-productid="' . $prod->product_id . '" class="copy-pop-product btn btn-add trig-'. $prod->product_id . '" ' . $idDisabled . '>Add</button>';
                    }
                    ?>
                </div>
                <?php if(count($prod->ProductVariants)>1){ ?>
                <div class="search-result-options">
                    <select name="sel_variant" class="sel_variant form-control" id="sel_variant_<?= $prod->product_id ?>">
                        <?php
                        foreach ($prod->ProductVariants as $key2 => $ProductVariant) {
                            echo '<option pro_id="' . $prod->product_id . '" price="' . $ProductVariant->price.' ('.$shop_currency.')'. '" p="' . $ProductVariant->price.'" value="' . $ProductVariant->variant_id . '">' . $ProductVariant->variant_title . ' ('.$ProductVariant->price.')'. '</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php }else{ ?>
                <input type="hidden" class="sel_variant" id="sel_variant_<?= $prod->product_id ?>" name="sel_variant" value="<?= isset($prod->ProductVariants[0]->variant_id)?$prod->ProductVariants[0]->variant_id:''; ?>">
             <?php } ?>
            </div>
        </div>
        <?php
    }
}else{
    echo '<span class="norecord">No Products Found</span>';
}