<div class="row">
    <?php
    $checked = '';
    foreach ($this->Discounts->discountType as $k => $type) {
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
$success_text = lang('success_discount_text');
$discount_goal_div = 'block';
if(((isset($bundleData->discount_type) && $bundleData->discount_type==0) || !isset($bundleData->discount_type))){
    $discount_class = 'none';
    $success_text = lang('standard_success_text');
}else{
    if(isset($bundleData->discount_type) && ($bundleData->discount_type==3)){
        $discount_goal_div = 'none';
    }
}
?>
<div class="discount-options" style="display: <?php echo $discount_class; ?>">
    <div class="col-md-6 col-sm-6">
        <div class="form-group">
            <label class="custom-label" for="offer_headline">
                <?php echo lang('offer_headline') ?>
                <span class="asterisk ">*</span>
            </label>
            <button type="button" class="offer-headline-suggestion btn-question" data-toggle="modal" data-target="#myModal1">
                <img src="<?php echo $this->config->item('img_url') ?>suggestions/question-mark.svg" alt="Bundle" class="img-responsive" />
            </button>
            <!-- Modal -->
            <div id="myModal1" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <!-- Modal content-->
                <div class="modal-content">                                  
                  <div class="modal-body" style="padding: 0;">
                    <button type="button" class="close btn-close-outside" data-dismiss="modal">&times;</button>
                    <div class="suggestion-model" id="offer-headline-suggestion">
                        <img src="<?php echo $this->config->item('img_url') ?>suggestions/OfferHeadline.png" alt="Bundle" class="img-responsive" />
                    </div>
                  </div>                                  
                </div>

              </div>
            </div>
            <input class="form-control" id="offer_headline" type="text" name="offer_headline" value="<?= isset($bundleData->offer_headline) ? $bundleData->offer_headline : '' ?>">
            
        </div>
    </div>

    <div class="col-md-6 col-sm-6">
        <div class="form-group">
            <label class="custom-label"><?php echo lang('discount_code'); ?><span class="asterisk ">*</span></label>
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
                <option value="<?php echo $code->discount_id.'|'.$code->value.'|'.$code->value_type; ?>" <?php echo $chek; ?>><?php echo $code->title; ?></option>
                <?php }} ?>
            </select>
            <span id="selected_discount_code" data-selected_discount_code="<?php echo $selectedType; ?>"></span>
        </div>
    </div>
    <div class="col-md-6 col-sm-6" id="discount_goal_div" style="display: <?php echo $discount_goal_div; ?>">
        <div class="form-group">
            <label class="custom-label" for="discount-goal-amount"><?php echo lang('discount_goal_amount').' ('.$this->shopCurrency.')' ?></label>
            <input class="form-control number-with-dot" placeholder="(ex: If $200, customer must spend $200 to get discount)" id="discount_goal_amount" type="text" maxlength="5" name="discount_goal_amount" value="<?= isset($bundleData->discount_goal_amount) ? floatval($bundleData->discount_goal_amount) : '' ?>">
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

    <div class="col-md-6 col-sm-6 discount-val"  style="display: <?php echo $show_detail; ?>">
        <div class="form-group">
            <label class="custom-label" for="discount_value"><?php echo lang('discount_value') ?></label>
            <input class="form-control" type="text" id="discount_value" name="discount_value" value="<?php echo abs($discountValue); ?>" readonly="">
        </div>
    </div>    
</div>
<div class="col-md-6 col-sm-6" id="discount_text_div">
        <div class="form-group">
            <label class="custom-label" id="discount_text_label" for="discount-text">
                <?php echo $success_text ?>
                <span class="asterisk ">*</span>
            </label>
            <button type="button" class="thank-you-suggestion btn-question" data-toggle="modal" data-target="#myModal2">
                <img src="<?php echo $this->config->item('img_url') ?>suggestions/question-mark.svg" alt="Bundle" class="img-responsive" />
            </button>
            <div id="myModal2" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <!-- Modal content-->
                <div class="modal-content">                                  
                  <div class="modal-body" style="padding: 0;">
                    <button type="button" class="close btn-close-outside" data-dismiss="modal">&times;</button>
                     <div class="suggestion-model" id="thank-you-suggestion">
                        <img src="<?php echo $this->config->item('img_url') ?>suggestions/ThankYou.png" alt="Bundle" class="img-responsive" />
                    </div>
                  </div>                                  
                </div>

              </div>
            </div>
            <input class="form-control" id="discount_text" type="text" name="discount_text" value="<?= isset($bundleData->discount_text) ? $bundleData->discount_text : '' ?>">
           
        </div>
</div>