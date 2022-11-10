<div id="snackbar">Please add at least one product in Trigger Product.</div>
<div id="snackbar_success"></div>
<div class="wrap-1000">
    <div class="card padding-20 add-bundle">
        <h3 class="title"><?php echo isset($bundleData->bundle_label) ? lang('update_upsell_bundle') : lang('add_new_upsell_bundle') ?></h3>
        <div class="response"></div>
        <form id="bundle-form" onsubmit="return false;">
            <div class="steps step1">
                <!-- Textboxs -->
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label class="custom-label" for="bundle-name"><?php echo lang('name_of_offer') ?><span class="asterisk ">*</span></label>
                            <input class="form-control" required type="text" name="bundle_label" value="<?= isset($bundleData->bundle_label) ? $bundleData->bundle_label : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label class="custom-label" for="offer-headline"><?php echo lang('offer_title') ?>
                                <span class="asterisk ">*</span>
                            </label>
                            <button type="button" class="offer-title-suggestion btn-question" data-toggle="modal" data-target="#myModal">
                                <img src="<?php echo $this->config->item('img_url') ?>suggestions/question-mark.svg" alt="Bundle" class="img-responsive" />
                            </button>
                            <!-- Modal -->
                            <div id="myModal" class="modal fade" role="dialog">
                              <div class="modal-dialog modal-md">
                                <!-- Modal content-->
                                <div class="modal-content">                                  
                                  <div class="modal-body" style="padding: 0;">
                                    <button type="button" class="close btn-close-outside" data-dismiss="modal">&times;</button>
                                    <div class="suggestion-model" id="offer-title-suggestion">
                                        <img src="<?php echo $this->config->item('img_url') ?>suggestions/OfferTitle.png" alt="Bundle" class="img-responsive" />
                                    </div>
                                  </div>                                  
                                </div>

                              </div>
                            </div>
                            <input class="form-control " required type="text" name="bundle_title" value="<?= isset($bundleData->bundle_title) ? $bundleData->bundle_title : '' ?>">
                            
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label class="custom-label" for="offer-headline"><?php echo lang('upsell_condition') ?></label>
                            <br>
                            <label class="fancy-radio">
                                <input type="radio" name="upsell_condition" value="0" checked>
                                <span><i></i>When any filter criteria is met</span>
                            </label>
                            <br>
                            <label class="fancy-radio">
                                <input type="radio" name="upsell_condition" value="1" <?php echo (isset($bundleData->upsell_condition) && $bundleData->upsell_condition == 1) ? 'checked' : '' ?>>
                                <span><i></i>When all filter criteria are met</span>
                            </label>


                        </div>
                    </div>
                </div>
                <!-- Textboxs //-->
                <div class="devider"></div>
                <!-- Checkbox Items //-->

                <p class="strong"><?php echo lang('price_range_text') ?></p>

                <!-- Range Container -->
                <div class="range-container">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <h5 class="title">Date Range</h5>
                            <div class="min-max-container form-group">
                                <label class="label-title" for="offer-headline"><?php echo lang('start_date') ?></label>
                                <div class="date-text">
                                    <input class="form-control form-item datepicker" type="text" id="start_date" name="start_date" value="<?= !empty($bundleData->start_date) ? view_date($bundleData->start_date) : '' ?>">
                                </div>
                            </div>
                            <div class="min-max-container form-group">
                                <label class="label-title" for="offer-headline"><?php echo lang('end_date') ?></label>
                                <div class="date-text">
                                    <input class="form-control form-item datepicker" type="text" id="end_date" name="end_date" value="<?= !empty($bundleData->end_date) ? view_date($bundleData->end_date) : '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <h5 class="title">
                                Quantity Range
                                <span style="font-size: 13px; color: red; text-align: right; font-weight: 700; margin-bottom: 5px; float: right;">Min Must = 1 or More</span>
                            </h5>
                            
                            <div class="min-max-container form-group">
                                <label class="label-title" for="offer-headline"><?php echo lang('min_qty') ?></label>
                                <div class="add-remove">
                                    <a href="javascript:void(0)" class="remove btn-action qty-change" data-field="min_qty" data-type="minus">-</a>
                                    <input class="form-control form-item" onkeypress="return isNumberKey(event);" type="text" style="text-align: center" maxlength="2" name="min_qty" id='min_qty' value="<?= isset($bundleData->min_qty) ? $bundleData->min_qty : 1 ?>">
                                    <a href="javascript:void(0)" class="add btn-action qty-change" data-field="min_qty" data-type="plus">+</a>
                                </div>
                            </div>
                            <div class="min-max-container form-group">
                                <label class="label-title" for="offer-headline"><?php echo lang('max_qty') ?></label>
                                <div class="add-remove">
                                    <a href="javascript:void(0)" class="remove btn-action qty-change" data-field="max_qty" data-type="minus">-</a>
                                    <input class="form-control form-item" onkeypress="return isNumberKey(event);" type="text" style="text-align: center" maxlength="2" name="max_qty" id="max_qty" value="<?= isset($bundleData->max_qty) ? $bundleData->max_qty : '' ?>">
                                    <a href="javascript:void(0)"  class="add btn-action qty-change" data-field="max_qty" data-type="plus">+</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="devider"></div>
                <!-- Checkbox Items -->
                <div class="row">   
                    <div class="col-md-6 col-sm-6">
                        <div class="check-container upsell-condition">
                            <label class="fancy-checkbox">
                                <?php
                                $stock_checked = 'checked';
                                if (isset($bundleData->check_stock)) {
                                    $stock_checked = '';
                                    if ($bundleData->check_stock == 1)
                                        $stock_checked = 'checked';
                                }
                                //$stock_checked = (isset($bundleData->check_stock) && $bundleData->check_stock == 1) ? 'checked' : ''; 
                                ?>
                                <input type="checkbox" name="check_stock" value="1" <?php echo $stock_checked; ?>>
                                <span><i></i><p class="chk-label"><?php echo lang('show_in_stock_only') ?></p></span>
                            </label>
                            <p><?php echo lang('show_in_stock_only_desc') ?></p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="check-container upsell-condition">
                            <label class="fancy-checkbox">
                                <?php
                                $use_product_quantity = 'checked';
                                if (isset($bundleData->use_product_quantity)) {
                                    $use_product_quantity = '';
                                    if ($bundleData->use_product_quantity == 1)
                                        $use_product_quantity = 'checked';
                                }
                                ?>
                                <?php //$use_product_quantity = (isset($bundleData->use_product_quantity) && $bundleData->use_product_quantity == 1) ? 'checked' : '';  ?>
                                <input type="checkbox" name="use_product_quantity" value="1" <?php echo $use_product_quantity; ?>>
                                <span><i></i><p class="chk-label"><?php echo lang('choose_quanity_product') ?></p></span>
                            </label>
                            <p><?php echo lang('customer_choose_quantity_product') ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="reward-customization">
                            <div class="title">Reward Customization</div>
                        </div>
                    </div>
                </div>

                <div id="discount_section">
                    <?php $this->load->view('admin/bundles/discount_section', array('discountData' => $discountData, 'bundleData' => $bundleData)); ?>
                </div>

                <div class="clearfix"></div>
                <!-- Range Container //-->
                <div class="divider"></div>
                <div id="target_product_div">

                    <h3 class="title mb10"><?php echo lang('triggered_products') ?></h3>                    
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
                                    if (!empty($collectionsList)) {
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
                                    <?php $this->load->view('admin/bundles/_ajax_trigger_product_list', array('prodList' => $prodList, 'editSelectedTargetAry' => $editSelectedTargetAry)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="panel panel-default product-panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo lang('selected_trigger_products') ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="selected-items bundle-products-list">
                                        <?php
                                        $discount_type = isset($bundleData->discount_id)?$bundleData->discount_type:0;
                                        $bygx = ($discount_type==3)?'disabled':'';
                                        if (!empty($bundleProds)) {
                                            foreach ($bundleProds as $bp) {
                                                ?>
                                                <div class="sel-prods remove-prod-<?= $bp->product_id ?>">
                                                    <div class="product-search-item product-item">
                                                        <div class="search-result-img product-item-img">
                                                            <a class="preview-link" target="_blank" href="<?= $bp->product_link ?>">
                                                                <img src="<?= $bp->image ?>" alt="<?= $bp->title ?>" class="img-responsive img-search">
                                                            </a>
                                                        </div>
                                                        <!-- <div class="search-result-options"> -->
                                                        <div class="product-name">
                                                            <h5 class="product-item-name">
                                                                <a class="preview-link" target="_blank" href="<?= $bp->product_link ?>"> <?= $bp->title ?></a>
                                                            </h5>                                                    
                                                        </div>
                                                        <div class="product-n-price">
                                                            <span><?php echo $bp->ProductVariants[0]->price ?> (<?php echo $shop_currency; ?>)</span>
                                                        </div>
                                                        <div class="search-result-links">
                                                            <button type="button" data-productid="<?= $bp->product_id ?>" <?= $bygx ?> class="rem-pop-product btn btn-remove"><?php echo lang('remove') ?></button>
                                                        </div>
                                                        <div class="search-result-options">
                                                            <?php if(count($bp->ProductVariants)>1){ ?>
                                                            <select name="sel_variant" class="sel_variant form-control" id="sel_variant_<?= $bp->product_id ?>">
                                                                <?php
                                                                foreach ($bp->ProductVariants as $key2 => $ProductVariant) {
                                                                    $selected = ($ProductVariant->variant_id == $bp->variant_id) ? ' selected' : '';
                                                                    echo '<option pro_id="' . $bp->product_id . '" p="' . $ProductVariant->price . '" value="' . $ProductVariant->variant_id . '" ' . $selected . '>' . $ProductVariant->variant_title . ' (' . $ProductVariant->price . ')' . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                            <?php }?>
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
                    <h3 class="title mb10"><?php echo lang('bundle_product') ?></h3>
                    <div class="row">
                        <div class="col-md-5 col-sm-4">
                            <div class="form-group">
                                <label class="custom-label"><?php echo lang('search_by_title') ?></label>
                                <input class="form-control" maxlength="150" type="text" name="search_bundle_product" id="search_bundle_product" placeholder="Search by Product">
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-4">
                            <div class="form-group">
                                <label class="custom-label"><?php echo lang('search_by_category') ?></label>
                                <select class="form-control" id="bundle_category" name="bundle_category">
                                    <option value=""><?php echo lang('select_category') ?></option>
                                    <?php
                                    if (!empty($collectionsList)) {
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
                                <button class="btn btn-custom search-bundle-product" type="button"><?php echo lang('search') ?></button>
                            </div>
                        </div>
                    </div>
                    <!-- Trigger Products Add & Remove -->               
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="panel panel-default product-panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo lang('select_bundle_products') ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="panel-body"  id="triggered-products">
                                        <?php $this->load->view('admin/bundles/_ajax_target_product_list', array('prodList' => $prodList, 'editSelectedTriggerAry' => $editSelectedTriggerAry)); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="panel panel-default product-panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php echo lang('selected_bundle_products') ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="selected-trigger-items bundle-products-list">
                                        <?php
                                        
                                        if (!empty($bundleTrigers)) {
                                            foreach ($bundleTrigers as $bp) {
                                                ?>
                                                <div class="trigger-prods remove-bundle-<?= $bp->product_id ?>">
                                                    <div class="product-search-item product-item">
                                                        <div class="search-result-img product-item-img">
                                                            <a class="preview-link" target="_blank" href="<?= $bp->product_link ?>">
                                                                <img src="<?= $bp->image ?>" alt="<?= $bp->title ?>" class="img-responsive img-search">
                                                            </a>
                                                        </div>
                                                        <!-- <div class="search-result-options"> -->
                                                        <div class="product-name">
                                                            <h5 class="product-item-name">
                                                                <a class="preview-link" target="_blank" href="<?= $bp->product_link ?>"> <?= $bp->title ?></a>
                                                            </h5>                                                    
                                                        </div>
                                                        <div class="product-n-price">
                                                            <span><?php echo $bp->ProductVariants[0]->price ?> (<?php echo $shop_currency; ?>)</span>
                                                        </div>
                                                        <div class="search-result-links">
                                                            <button type="button" data-productid="<?= $bp->product_id ?>" <?= $bygx ?> class="rem-bundle-product btn btn-remove"><?php echo lang('remove') ?></button>
                                                        </div>
                                                        <div class="search-result-options">
                                                            <?php if(count($bp->ProductVariants)>1){ ?>
                                                            <select name="trigger_variant" class="trigger_variant form-control" id="trigger_variant_<?= $bp->product_id ?>">
                                                                <?php
                                                                foreach ($bp->ProductVariants as $key2 => $ProductVariant) {
                                                                    $selected = ($ProductVariant->variant_id == $bp->variant_id) ? ' selected' : '';
                                                                    echo '<option pro_id="' . $bp->product_id . '" p="' . $ProductVariant->price . '" value="' . $ProductVariant->variant_id . '" ' . $selected . '>' . $ProductVariant->variant_title . ' (' . $ProductVariant->price . ')' . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                            <?php } ?>
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
                <input type="hidden" id="trigger_product" name="target_product" value="<?php echo $editSelectedTarget; ?>">
                <input type="hidden" id="trigger_product_variant" name="target_product_variant" value="<?php echo $editSelectedTargetVariant; ?>">
                <input type="hidden" id="bundle_product" name="trigger_product" value="<?php echo $editSelectedTrigger; ?>">
                <input type="hidden" id="bundle_product_variant" name="trigger_product_variant" value="<?php echo $editSelectedTriggerVariant; ?>">
                <input type="hidden" name="id" id="id" value="<?= isset($bundleData->id) ? $bundleData->id : '' ?>">
                <div class="text-right">
                    <?php
                    $btnText = lang('submit');
                    if (isset($bundleData->id)) {
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
<script src="<?php echo site_url(); ?>/assets/js/bootstrap-datepicker.js"></script>
<link href="<?php echo site_url(); ?>/assets/css/datepicker3.css" rel="stylesheet" media="all" />
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<?php $this->load->view('admin/bundles/bundle_js'); ?>