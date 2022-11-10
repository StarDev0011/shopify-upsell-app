<?php 
	$opt1 = '';
	$opt1Class = '';
 	if(isset($bundleData[0]->useCustomCurrency) and $bundleData[0]->useCustomCurrency ==1){
		$opt1 = 'checked';
		$opt1Class = 'active';
	}
 ?>


<?php 
	$opt2 = '';
        $opt2Class = '';
 	if(isset($bundleData[0]->usePriceRanges) and $bundleData[0]->usePriceRanges ==1){
		$opt2 = 'checked';
		$opt2Class = 'active';
	}
	//$opt2 = 'checked';
	//$opt2Class = 'active';
	
 ?>
<div class="form-group-nm row">
    <div class="col-md-12">
        <div class="smar7-checkbox-container <?=$opt2Class?>">
            <input type="checkbox" class="smar7-checkbox" value="1" name="usePriceRanges" <?=$opt2?> id="use_price_ranges">
            <label for="use_price_ranges" style="margin-bottom: 0px !important;"></label>
        </div>
        <span class="smar7-checkbox-cap">
            Only make this offer available when cart total is in a specific price range
        </span>
    </div>
</div>

<?php 
	$style	=	'display:none;';
	if($opt2=='checked'){
		$style	=	'display:block;';
	}
 ?>

<div class="row price_range" id="min-max-row" style="height: 100px; <?=$style?>">
    <div class="col-md-6">
        <div class="form-group">
            <label for="min-amount">Min amount</label>
            <div class="input-group">
                <div class="input-group-addon sm7-store-currency"><?=$shop_currency?></div>
                <input class="form-control" type="text" name="min_amount" id="min-amount" value="<?=isset($bundleData[0]->min_amount)?$bundleData[0]->min_amount:'0'?>">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="max-amount">Max amount (Optional)</label>
            <div class="input-group">
                <div class="input-group-addon sm7-store-currency"><?=$shop_currency?></div>
                <input class="form-control" type="text" name="max_amount" id="max-amount" value="<?=isset($bundleData[0]->max_amount)?$bundleData[0]->max_amount:'0'?>">
            </div>
        </div>
    </div>  

  
</div>

<?php 
        $opt3 = '';
	$style	=	'display:none;';
	if($opt3=='checked'){
		$style	=	'display:block;';
	}
 ?>
<div class="row no_cycles" id="no_cycles" style="height:100px; <?=$style?>">
    <div class="col-md-6">
        <div class="form-group">
            <label for="min-amount">No of Cycles</label>
            <input class="form-control" type="text" name="noOfCycles" id="noOfCycles" value="<?=isset($bundleData[0]->noOfCycles)?$bundleData[0]->noOfCycles:'2'?>">
        </div>
    </div>
</div>

<?php 
	$opt4 = '';
	$opt4Class = '';
 	if(isset($bundleData[0]->check_stock) and $bundleData[0]->check_stock ==1){
		$opt4 = 'checked';
		$opt4Class = 'active';
	}

	$opt5 = '';
	$opt5Class = '';
 	if(isset($bundleData[0]->check_cart) and $bundleData[0]->check_cart ==1){
		$opt5 = 'checked';
		$opt5Class = 'active';
	}
 ?>
<div class="form-group-nm row">
    <div class="col-md-12">
        <div class="smar7-checkbox-container <?=$opt5Class?>">
            <input type="checkbox" class="smar7-checkbox" value="1" name="check_cart" <?=$opt5?> id="check_cart">
            <label for="check_cart" style="margin-bottom: 0px !important;"></label>
        </div>
        <span class="smar7-checkbox-cap">
            Hide items that are already in the customer's cart
        </span>
    </div>
</div>
<!--settings/options-->
