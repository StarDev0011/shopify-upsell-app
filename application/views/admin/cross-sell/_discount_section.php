<div class="row">
    <?php
    $checked = '';
    foreach ($this->CrossSellBundle->discountType as $k => $type) {
        ?>
        <div class="col-md-3 col-sm-6">
            <div class="check-container">
                <label class="fancy-checkbox">
                    <?php 
                    $checked = '';
                    if(isset($bundleData->discount_type)){
                        if($bundleData->discount_type == $k){
                            $checked = 'active';
                        }
                    }else{
                        if($k==0){
                            $checked = 'active';
                        }
                    }?>
                    <span><i></i><p class="rad-label"><?php echo $type['title'] ?></p></span>
                </label>
                <p><?php echo $type['text'] ?></p>
                <div class="choose-btn">
                    <button type="button" name="discount_type_button" class="discount-type type-<?php echo $k; ?> <?php echo $checked; ?>" value="<?php echo $k; ?>" <?php echo $checked; ?>>Choose</button>
                </div>
            </div>
        </div>
    <?php } 
    $discount_type = isset($bundleData->discount_id)?$bundleData->discount_type:0;
    ?>
    <input type="hidden" name="discount_type" id="discount_type" value="<?php echo $discount_type; ?>">
</div>
<?php 
$discount_class = 'block'; 
$success_text = lang('offer_headline');
$discount_goal_div = 'block';
if(((isset($bundleData->discount_type) && $bundleData->discount_type==0) || !isset($bundleData->discount_type))){
    $discount_class = 'none';
    $success_text = lang('offer_headline');
}else{
    if(isset($bundleData->discount_type) && ($bundleData->discount_type==3)){
        $discount_goal_div = 'none';
    }
}
?>
<div class="discount-options" style="display: <?php echo $discount_class; ?>">
    <div class="col-md-6 col-sm-6">
        <div class="form-group">
                <label class="custom-label" id="offer_headline_label" for="discount-text">
                    <?php echo $success_text ?>
                    <span class="asterisk ">*</span>
                </label>
                <input class="form-control" id="offer_headline" type="text" name="offer_headline" value="<?= isset($bundleData->offer_headline) ? $bundleData->offer_headline : '' ?>">
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="form-group">
            <label class="custom-label"><?php echo lang('discount_code') ?><span class="asterisk ">*</span></label>
            <select class="form-control" id="discount_id" name="discount_id">
                <?php 
                $discountValue = '';
                $selectedType = '';
                $perActive = 'active ';
                $fixeActive = '';
                if(!empty($discountCodes)){
                    foreach ($discountCodes as $code){
                        if(($code->discount_id==$bundleData->discount_id)){
                            if(empty($discountValue)){
                                $discountValue = $code->value;
                            }
                            $chek = 'selected';
                            $selectedType = $code->discount_id.'|'.$code->value.'|'.$code->value_type;
                            if($code->value_type=='fixed_amount'){
                                $perActive = '';
                                $fixeActive = 'active ';
                            }elseif($code->value_type=='percentage'){
                                $discount_goal_div='none';
                            }
                            $per = $code->value_type;
                        }else{
                            $chek = '';
                        }
                        ?>
                <option value="<?php echo $code->discount_id.'|'.$code->value.'|'.$code->value_type; ?>" <?php echo $chek; ?>><?php echo $code->discount_code; ?></option>
                <?php }} ?>
            </select>
            <span id="selected_discount_code" data-selected_discount_code="<?php echo $selectedType; ?>"></span>
        </div>
    </div>    
    <?php $show_detail = (isset($bundleData->discount_type) && (($bundleData->discount_type==1)))?'block':'none';?>    
    <div class="col-md-6 col-sm-6 discount-val"  style="display: <?php echo $show_detail; ?>">
        <div class="form-group">
            <label class="custom-label" for="discount-goal-amount">Discount applied to an order</label>
            <div class="common-tab-design">
                <div id="tabone" class=""> 
                    <ul class="nav nav-pills">
                        <li class="<?php echo $perActive ?> per-li">Percentage</li>
                        <li class="<?php echo $fixeActive ?>fixed-li">Fixed Amount</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="form-group">
                <label class="custom-label" id="offer_success_text" for="success_text">
                    Thank you text
                    <span class="asterisk ">*</span>
                </label>
                <input class="form-control" id="success_text" type="text" name="success_text" value="<?= isset($bundleData->success_text) ? $bundleData->success_text : '' ?>">
        </div>
    </div>
</div>