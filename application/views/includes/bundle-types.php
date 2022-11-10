<div class="form-group row bu-type">
    <div class="col-sm-12">
        <input type="hidden" name="type" id="bundle-type" value="standard">
        <label>Bundle Type</label>
        <?php
        $bType = 1;
        if (isset($bundleData[0]->bundle_type)) {
            $bType = $bundleData[0]->bundle_type;
        }
        ?>
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default mt-3">
                    <div class="panel-heading">
                        <h3 class="panel-title">Standard Upsell</h3>
                    </div>
                    <div class="panel-body"> Standard upsell will present your customers with products upon checkout depending on items in their cart </div>
                    <div class="panel-footer bu-type-option <?php if ($bType == 1) {
            echo 'active';
        } ?> next-btn" curr="step1" next="step2" bundle-type="1" data-type="standard"> <i class="fa fa-check" aria-hidden="true"></i> Choose </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Discount Upsell</h3>
                        <span class="recom">Recomended</span> </div>
                    <div class="panel-body"> Discount upsell offers a percentage discount if your customers add a certain monetary amount to their cart during
                        the upsell </div>
                    <div class="panel-footer bu-type-option <?php if ($bType == 2) {
            echo 'active';
        } ?> next-btn" curr="step1" next="step2" bundle-type="2" data-type="discount"> <i class="fa fa-check" aria-hidden="true"></i> Choose </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default mt-3">
                    <div class="panel-heading">
                        <h3 class="panel-title">Free shipping upsell</h3>
                    </div>
                    <div class="panel-body"> Free Shipping offers free shipping if your customers add a certain monetary amount to their cart during the upsell </div>
                    <div class="panel-footer bu-type-option <?php if ($bType == 3) {
            echo 'active';
        } ?> next-btn" curr="step1" next="step2" bundle-type="3" data-type="free_shipping"> <i class="fa fa-check" aria-hidden="true"></i> Choose </div>
                </div>
            </div>
        </div>
        <!--row-->

        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default mt-3">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buy One get One FREE</h3>
                    </div>
                    <div class="panel-body"> This will present a second of the same item at zeron cost with an option to charge additional shipping on the 
                        second item. </div>
                    <div class="panel-footer bu-type-option <?php if ($bType == 4) {
            echo 'active';
        } ?> next-btn" curr="step1" next="step2" bundle-type="4" data-type="buy-one"> <i class="fa fa-check" aria-hidden="true"></i> Choose </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Free Just Pay Shipping</h3>
                    </div>
                    <div class="panel-body"> This Upsell offers one of your free items of choice as an upsell and includes your shipping charge in the total. </div>
                    <div class="panel-footer bu-type-option <?php if ($bType == 5) {
            echo 'active';
        } ?> next-btn" curr="step1" next="step2" bundle-type="5" data-type="free"> <i class="fa fa-check" aria-hidden="true"></i> Choose </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default mt-3">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buy 2 Get a 3rd FREE</h3>
                    </div>
                    <div class="panel-body"> This Upsell offers your customer the chance to purchase a second of the same item to get a 3rd one for FREE </div>
                    <div class="panel-footer bu-type-option <?php if ($bType == 6) {
            echo 'active';
        } ?> next-btn" curr="step1" next="step2" bundle-type="6" data-type="buy-two"> <i class="fa fa-check" aria-hidden="true"></i> Choose </div>
                </div>
            </div>
        </div>
        <!--row--> 

    </div>
</div>
<!--/bundle-types-->
<?php
$style = 'style="display:none;"';
if ($bType == 2 or $bType == 3) {
    $style = 'style="display:block;background:#FFF"';
}
?>

<!--<div id="discount-div" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>-->
