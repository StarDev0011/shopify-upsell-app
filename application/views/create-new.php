<?php include_once('templates/header.php'); ?>
<link href="<?php echo site_url(); ?>/assets/css/selectize.default.css" rel="stylesheet" media="all" />
<div class="container bg-white">
    <div class="col-sm-10 col-center">
        <ul class="bundle-tabs">
            <li class="active"><a href="javascript:void(0);" class="rel rel-step1 active" rel="step1">Basics</a></li>
            <li><a href="javascript:void(0);" class="rel rel-step2" rel="step2"> <b>/</b> Bundle Type</a></li>
            <li><a href="javascript:void(0);" class="rel rel-step3" rel="step3"> <b>/</b> Products</a></li>
            <li><a href="javascript:void(0);" class="rel rel-step4" rel="step4"> <b>/</b> Settings</a></li>
        </ul>
        <form id="bundle-form" onsubmit="return false;">
            <div class="steps animated fadeInLeft step1">
                <?php include_once('includes/bundle-types.php'); ?>
                <div class="divider"></div>
                <div class="text-right"> <a href="<?php echo site_url('bundles/index') ?>" class="btn btn-default go-back" role="button">Cancel</a>
                    <button type="button" class="btn btn-success smar7-btn next-btn" curr="step1" next="step2" >Continue</button>
                </div>
            </div>
            <!--step1-->

            <div class="steps animated fadeInLeft step2" style="display:none;">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="bundle-name">Bundle Name</label>
                        <input class="form-control" required type="text" name="bundle_label" id="bundle-name" value="<?= isset($bundleData[0]->bundle_label) ? $bundleData[0]->bundle_label : '' ?>" placeholder="Enter Bundle Name">
                    </div>
                </div>
                <!--/title-->

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="offer-headline">Offer Headline</label>
                        <input class="form-control form-item" required type="text" name="headline" id="offer-headline" value="<?= isset($bundleData[0]->headline) ? $bundleData[0]->headline : '' ?>" placeholder="Enter Offer Headline">
                    </div>
                </div>
                <!--/headline-->

                <div class="divider"></div>
                <div class="text-right"> <a href="javascript:void(0);" class="btn btn-default go-back" role="button" curr="step2" prev="step1">Back</a>
                    <button type="button" class="btn btn-success smar7-btn next-btn-dic next-btn" curr="step2" next="step3" >Continue</button>
                </div>
            </div>
            <!--/step2-->

            <div class="steps animated fadeInLeft step3" style="display:none;">
                <?php
                /* $products =  array();
                  $triggers =  array();
                  if(isset($bundleData[0]->products) and $bundleData[0]->products!=''){
                  $products = explode('#', $bundleData[0]->products);
                  }
                  if(isset($bundleData[0]->triggers) and $bundleData[0]->triggers!=''){
                  $triggers = explode('#', $bundleData[0]->triggers);
                  } */
                ?>
                <div class="form-group row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-info btn-block select-products-button" bundle_ptype="t" id="selected-triggers-button23" data-toggle="modal" data-target="#product-modal"> Trigger Products <span class="badge sm7-triggers-count">
                                        <?= count($bundleTrigers) ?>
                                    </span> </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 pt-3 help-container"> <i class="fa fa-question-circle smar7-info" aria-hidden="true"></i> <span class="help">Having these products in your customers cart will cause the upsell popup to trigger</span> </div>
                        </div>
                        <div class="search-results bundle-products-list add-prod-div sel-triggers-list animated fadeInUp" style="display:none"><!--here display--></div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div data-toggle="modal" class="show_me_model" data-target="#product-modal" style="display:none"></div>
                                <button type="button" class="btn btn-info btn-block select-products-button" bundle_ptype="p" id="selected-products-button23" data-toggle="modal" data-target="#product-modal"> Bundle Products <span class="badge sm7-products-count">
                                        <?= count($bundleProds) ?>
                                    </span> </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 pt-3 help-container"> <i class="fa fa-question-circle smar7-info" aria-hidden="true"></i>
                                <p class="help">These are the products your customer will see in their upsell</p>
                            </div>
                        </div>
                        <br>
                        <div class="search-results bundle-products-list add-prod-div sel-products-list animated fadeInUp" style="top:-12px;display:none"><!--here display--></div>
                    </div>
                    <!--<div class="col-md-4">
                  <label for="">&nbsp;</label>
                  <button type="button" class="btn btn-info btn-block select-collectionPicker-button" id="selected-collectionPicker-button"> Bundle Collection <span class="badge sm7-products-count">
                    <?= count($bundleProds) ?>
                  </span> </button>
                  <div class="search-results bundle-products-list add-prod-div sel-products-list animated fadeInUp" style="margin-top:26px;"></div>
                </div>-->
                </div>
                <!--/trigger product-->
                <div class="divider"></div>
                <div class="text-right"> <a href="javascript:void(0);" class="btn btn-default go-back" role="button" curr="step3" prev="step2">Back</a>
                    <button type="button" class="btn btn-success smar7-btn next-btn" curr="step3" next="step4" >Continue</button>
                </div>
            </div>
            <!--/step3-->

            <div class="steps animated fadeInLeft step4" style="display:none;">
                <div class="extra-options">
                    <?php include_once('includes/bundle-options.php'); ?>
                    <?php include_once('includes/locale.php'); ?>
                    <?php include_once('includes/bundle-settings.php'); ?>
                </div>
                <div class="form-group row bundle-discount" <?= $style ?>>
                    <!--<div class="row overlay-title">
                      <div class="col-sm-6 clearfix"> Select Products </div>
                      <div class="col-sm-6 clearfix text-right">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                    </div>-->
                    <div class="col-sm-12 pull-left">

                        <div class="col-md-12">
                            <label for="bundle-name">Discount Code</label>
                            <a href="https://<?= $shop ?>/admin/discounts" target="_blank"><i class="fa fa-question-circle smar7-info" aria-hidden="true"></i></a> <p style="color:#F00; font-weight:bold">Discount Code must be created in Shopify first - Click the Question to create your bundle discount code.</p>

                        </div>
                        <div class="col-md-4"><input class="form-control" type="text" name="discount_code" id="discount-code" value="<?= isset($bundleData[0]->discount_code) ? $bundleData[0]->discount_code : '' ?>">
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-info btn-block select-products-button" bundle_ptype="t" id="selected-triggers-button" data-toggle="modal" data-target="#product-modal"> Trigger Products <span class="badge sm7-triggers-count">
                                            <?= count($bundleTrigers) ?>
                                        </span> </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 pt-3 help-container"> <i class="fa fa-question-circle smar7-info" aria-hidden="true"></i> <span class="help">Having these products in your customers cart will cause the upsell popup to trigger</span> </div>
                            </div>
                            <div class="search-results bundle-products-list add-prod-div sel-triggers-list animated fadeInUp" style="display:none"><!--here display--></div>

                        </div>

                        <div class="col-md-4">
                            <button type="button" class="btn btn-info btn-block select-products-button" id="selected-products-button" data-toggle="modal" data-target="#product-modal"> Bundle Products <span class="badge sm7-products-count">
                                    <?= count($bundleProds) ?>
                                </span> </button>
                            <div class="search-results bundle-products-list add-prod-div sel-products-list animated fadeInUp" style="margin-top:26px;display:none"><!--here display--></div>
                            <div class="row"> <i class="fa fa-question-circle smar7-info" aria-hidden="true"></i> <span class="help">These are the products your customer will see in their upsell</span> </div>
                        </div>

                    </div>
                </div>
                <div class="disc-opt" style="display:none;">
                    <h4><span class="text-danger">*</span> Discount code will be applied to all products in cart item, to check products please go to <a href="https://<?= $shop ?>/admin/discounts" target="_blank">discount</a> tab.</h4>
                </div>
                <div class="divider" style="margin-top:20px;"></div>
                <div class="response"></div>
                <div class="text-right"> <a href="javascript:void(0);" class="btn btn-default prev-btn-dic go-back" role="button" curr="step4" prev="step3">Back</a>
                    <button type="submit" class="btn btn-success smar7-btn save-button">Save</button>
                </div>
            </div>
            <!--/step4-->

            <?php
            $products = array();
            if (count($bundleProds) > 0) {
                foreach ($bundleProds as $prods) {
                    array_push($products, $prods->product_id);
                }/* foreach */
            }

            $products_variant = array();
            if (count($bundleProds) > 0) {
                foreach ($bundleProds as $prods) {
                    array_push($products_variant, $prods->variant_id);
                }/* foreach */
            }

            $triggers = array();
            if (count($bundleTrigers) > 0) {
                foreach ($bundleTrigers as $trig) {
                    array_push($triggers, $trig->product_id);
                }/* foreach */
            }

            $triggers_variant = array();
            if (count($bundleTrigers) > 0) {
                foreach ($bundleTrigers as $trig) {
                    array_push($triggers_variant, $trig->variant_id);
                }/* foreach */
            }


            if (count($products) > 0) {
                $products = implode('#', $products);
                $products = trim($products, '#');
            } else {
                $products = '';
            }
            if (count($products_variant) > 0) {
                $products_variant = implode('#', $products_variant);
                $products_variant = trim($products_variant, '#');
            } else {
                $products_variant = '';
            }

            if (count($triggers) > 0) {
                $triggers = implode('#', $triggers);
                $triggers = trim($triggers, '#');
            } else {
                $triggers = '';
            }
            if (count($triggers_variant) > 0) {
                $triggers_variant = implode('#', $triggers_variant);
                $triggers_variant = trim($triggers_variant, '#');
            } else {
                $triggers_variant = '';
            }
            ?>
            <input type="hidden" id="current" value="">
            <input type="hidden" id="products" name="products" value="<?= isset($products) ? $products : '' ?>" >
            <input type="hidden" id="products_variant" name="products_variant" value="<?= isset($products_variant) ? $products_variant : '' ?>" >
            <input type="hidden" id="triggers" name="triggers" value="<?= isset($triggers) ? $triggers : '' ?>" >
            <input type="hidden" id="triggers_variant" name="triggers_variant" value="<?= isset($triggers_variant) ? $triggers_variant : '' ?>" >
            <input type="hidden" id="shop_id" name="shop_id" value="<?= $shop_id ?>">
            <input type="hidden" name="bundle_type" id="bundle_type" value="<?= isset($bundleData[0]->bundle_type) ? $bundleData[0]->bundle_type : '1' ?>">
            <input type="hidden" name="bundle_id" id="bundle_id" value="<?= isset($bundleData[0]->id) ? $bundleData[0]->id : '' ?>">
        </form>
    </div>
    <!--col-md-8--> 
</div>
<!--container-->
<?php include_once('templates/footer.php'); ?>
<!--modals-->
<div id="product-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg"> 
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <?php //include_once('includes/product-modal.php'); ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<style>
    .rel {
        display: none;
    }
    .rel.active {
        display: block;
    }
    select#discount_applies {
        height: 40px;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 10px;
    }
</style>
<script>
    jQuery.noConflict();
    jQuery(document).ready(function (e) {

        jQuery('.select-products-button').on('click', function (event) {
            event.preventDefault();
            var bundle_id = '0';
            bundle_id = jQuery('#bundle_id').val();

            var bundle_ptype = jQuery(this).attr('bundle_ptype');

            jQuery('.modal-body').html('<div class="container product-modal"><div class="row overlay-title"><div class="col-sm-6 clearfix"> Loading...</div></div></div>');
            //jQuery('.show_me_model').click();

            //jQuery(this).attr('disabled', 'disabled');

            //var myhml = jQuery(this).html();

            //jQuery(this).html('Loading...');
            var sel_pro = '';
            var sel_triggers_variant = '';
            var sel_products_variant = '';
            if (bundle_ptype == 't') {
                sel_pro = jQuery('#triggers').val();
                sel_triggers_variant = jQuery('#triggers_variant').val();
            } else {
                sel_pro = jQuery('#products').val();
                sel_products_variant = jQuery('#products_variant').val();
            }

            jQuery.ajax({
                type: 'POST',
                url: '<?php echo site_url('bundles/load_modal') ?>',
                data: {bundle_id: bundle_id,shop:'<?php echo $this->session->userdata('shop'); ?>', bundle_ptype: bundle_ptype, sel_pro: sel_pro, sel_products_variant: sel_products_variant, sel_triggers_variant: sel_triggers_variant},
                success: function (data) {
                    jQuery('.modal-body').html(data);
                }
            });
        });

        jQuery('.next-btn').on('click', function (e) {
            curr = jQuery(this).attr('curr');
            next = jQuery(this).attr('next');
            flag = true;

            if (curr == 'step2') {
                if (jQuery('#bundle-name').val() == '') {
                    jQuery('#bundle-name').css('border', '1px solid #F00');
                    flag = false;
                } else {
                    jQuery('#bundle-name').css('border', '1px solid #ccc');
                }

                if (jQuery('#offer-headline').val() == '') {
                    jQuery('#offer-headline').css('border', '1px solid #F00');
                    flag = false;
                } else {
                    jQuery('#offer-headline').css('border', '1px solid #ccc');
                }
            }

            if (curr == 'step1' && (jQuery('#bundle_type').val() == '2')) {

                if (jQuery('#discount-code').val() == '') {
                    jQuery('#discount-code').css('border', '1px solid #F00');
                    flag = false;
                } else {
                    jQuery('#discount-code').css('border', '1px solid #ccc');
                }
            }

            if (flag) {

                jQuery('.bundle-tabs').find('li').removeClass('active');
                jQuery('.rel-' + next).addClass('active');
                jQuery('.rel-' + next).parent('li').addClass('active');

                jQuery('.' + curr).hide();
                jQuery('.' + next).show();
            }

        });

        jQuery('.go-back').on('click', function (e) {
            curr = jQuery(this).attr('curr');
            prev = jQuery(this).attr('prev');

            jQuery('.rel-' + curr).removeClass('active');
            jQuery('.rel-' + curr).parent('li').removeClass('active');

            jQuery('.' + curr).hide();
            jQuery('.' + prev).show();

        });


        jQuery('.rel').on('click', function (e) {
            rel = jQuery(this).attr('rel');
            jQuery('.steps').hide();
            jQuery('.' + rel).show();

        });

        jQuery('.smar7-checkbox').on('click', function (e) {
            rel_id = jQuery(this).attr('id');
            if (jQuery(this).attr('checked')) {
                jQuery(this).attr('checked', false);
                jQuery(this).parent('.smar7-checkbox-container').removeClass('active');

                if (rel_id == 'use_price_ranges') {
                    jQuery('#min-max-row').hide();
                } else if (rel_id == 'use_custom_cycles') {
                    jQuery('#no_cycles').hide();
                }
            } else {
                jQuery(this).attr('checked', true);
                jQuery(this).parent('.smar7-checkbox-container').addClass('active');

                if (rel_id == 'use_price_ranges') {
                    jQuery('#min-max-row').show();
                } else if (rel_id == 'use_custom_cycles') {
                    jQuery('#no_cycles').show();
                }

            }

        });


        jQuery('#selected-triggers-button').on('click', function (e) {
            jQuery('#current').val('triggers');
            jQuery('.selected-items').html('');
        });
        jQuery('#selected-triggers-button23').on('click', function (e) {
            jQuery('#current').val('triggers');
            jQuery('.selected-items').html('');
        });
        ///selected-triggers-button
        jQuery('#selected-products-button23').on('click', function (e) {

            jQuery('#current').val('products');
            jQuery('.selected-items').html('');
        });

        jQuery('#selected-products-button').on('click', function (e) {

            jQuery('#current').val('products');
            jQuery('.selected-items').html('');
        });

        var bundle_type = jQuery('#bundle_type').val();
        if (bundle_type == '2' || bundle_type == '3') {
            jQuery('.bundle-discount').show();
            jQuery('.next-btn-dic').attr('next', 'step4');
            //jQuery('.extra-options').hide();
            jQuery('.disc-opt').show();
        } else {
            jQuery('.go-back').attr('next', 'step3');
            //jQuery('.extra-options').show();
            jQuery('.disc-opt').hide();
        }


        /** Bundle Types **/
        jQuery('.bu-type-option').on('click', function (e) {
            jQuery('.bundle-discount').hide();
            var bundle_type = jQuery(this).attr('bundle-type');
            jQuery('.bu-type-option').removeClass('active');
            jQuery(this).addClass('active');
            jQuery('#bundle_type').val(bundle_type);
            if (bundle_type == '2' || bundle_type == '3') {
                jQuery('.bundle-discount').show();
                jQuery('.next-btn-dic').attr('next', 'step4');
                jQuery('.prev-btn-dic').attr('prev', 'step2');
                //jQuery('.extra-options').hide();
                jQuery('.disc-opt').show();
            } else {
                jQuery('.prev-btn-dic').attr('prev', 'step2');
                jQuery('.next-btn-dic').attr('next', 'step3');
                //jQuery('.extra-options').show();
                jQuery('.disc-opt').hide();
            }
        });

        //jQuery(".product-collections-select").selectize();
        jQuery('.product-collections-select').selectize({});
        jQuery(document).on('change', '#collections', function (e) {
            var collection_id = jQuery('#collections option:selected').val();
            jQuery.ajax({
                url: '<?php echo site_url() . 'bundles/get_pro_by_collections'; ?>',
                type: 'POST',
                dataType: 'html',
                data: {
                    collection_id: collection_id,
                },
                error: function () {

                },
                success: function (reshtml) {
                    //console.log(reshtml);
                    jQuery('.selected-items').html('');
                    jQuery('.selected-items').html(reshtml);
                }
            });
        });

        jQuery(document).on('click', '.copy-pop-product', function (e) {
            prod_id = jQuery(this).attr('data-productid');
            data = jQuery('.copy-prod-' + prod_id).html();
            rem_class = 'remove-prod-' + prod_id;
            jQuery('.selected-items').append('<div class="sel-prods ' + rem_class + '">' + data + '</div>');

            jQuery('.' + rem_class).find('button').addClass('btn-danger').removeClass('btn-success');
            jQuery('.' + rem_class).find('button').addClass('rem-pop-product').removeClass('copy-pop-product');
            jQuery('.' + rem_class).find('button').html('Remove');

            //sel_variant_11877324420
            //jQuery('#sel_variant_'+prod_id+'').change();
            jQuery(this).attr('disabled', true);
        });/*copy-content*/

        jQuery(document).on('click', '.rem-pop-product', function (e) {
            prod_id = jQuery(this).attr('data-productid');
            rem_class = 'remove-prod-' + prod_id;
            jQuery('.' + rem_class).remove();

            jQuery('.copy-prod-' + prod_id + ' .copy-pop-product').attr('disabled', false);

        });/*remove-product*/
        //jQuery('.add-prods').on('click',
        jQuery(document).on('click', '.add-prods', function (e) {
            var count = 0;
            product_id = '';
            sel_variant = '';

            jQuery('.sel-prods').each(function (e) {
                product_id += jQuery(this).find('button').attr('data-productid') + '#';

                img_src = jQuery(this).find('img').attr('src');
                prod_title = jQuery(this).find('.product-item-name').html();
                curr_class = 'sel-products-list';

                if (jQuery('#current').val() == 'triggers') {
                    curr_class = 'sel-triggers-list';
                }

                jQuery('.' + curr_class).show();

                jQuery('.' + curr_class).append('<div class="product-search-item product-item col-xs-12"><div class="search-result-img product-item-img pull-left"><img src="' + img_src + '" class="img-thumbnail img-search"></div><div class="search-result-options"><h5 class="product-item-name mod3-head">' + prod_title + '</h5></div></div>');
                count++;

                sel_variant += jQuery(this).find('.sel_variant :selected').val() + '#';
            });

            console.log(sel_variant);

            if (jQuery('#current').val() == 'triggers') {
                jQuery('.sm7-triggers-count').html(count);
                jQuery('#triggers').val(product_id.trim('#'));

                jQuery('#triggers_variant').val(sel_variant.trim('#'));
            } else {
                jQuery('.sm7-products-count').html(count);
                jQuery('#products').val(product_id.trim('#'));

                /*jQuery('.sel-prods').each(function(e){
                 sel_variant += jQuery('.sel_variant').find(":selected").val()+'#';
                 });*/

                jQuery('#products_variant').val(sel_variant.trim('#'));

            }

            jQuery('.close').click();
        });
        /*jQuery(document).on('change','.sel_variant', function(e){
         sel_variant = jQuery(this).find(":selected").val();		
         final_val = sel_variant+'#';
         jQuery('#products_variant').val(final_val.trim('#'));
         });*/
        jQuery('#bundle-form').on('submit', function (e) {
            data = jQuery(this).serialize();
            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('bundles/createBundle') ?>',
                data: data,
                success: function (data) {
                    console.log(data);
                    jQuery('.response').html('<div class="alert alert-success"><i class="fa fa-thumbs-up"></i> Updated Successfully..!!</div>');
                    jQuery('.save-button').attr('disabled', true);
                    window.location.href = "<?= site_url('bundles/index/') ?>";
                }
            });/*ajax*/

        });

        /********* Search Options **********
         jQuery(document).on("click",".ser_div_btn",function(){
         var show_val = jQuery(this).val();
         jQuery('.ser_div').hide();
         jQuery('#'+show_val+'').show();
         
         });*/

        /********* Search Functionality collections_keyword**********
         jQuery(document).on("keyup","#collections_keyword",function(){  
         var shop_id = jQuery('#shop_id').val();
         var keyword = jQuery(this).val();
         if(keyword!=''){
         if(keyword.length > 2){
         var data= { 'keyword':keyword, 'shop_id':shop_id, 'collections':collections};
         $(".suggestion_div").show();
         jQuery(".suggestion_div").html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');
         
         jQuery.ajax({       
         type: 'POST',
         url: '<?= site_url('product/search_by_keyword') ?>',
         data: data,     
         success : function(data){
         if(data!=''){
         $(".suggestion_div").html(data);
         }        
         }
         });
         }
         
         }//if keyword
         else{
         $(".suggestion_div").hide();
         $(".suggestion_div").html('');
         }
         }); *//*"keyup","#keyword"*/

        /********* Search Functionality product_keyword***********/
        jQuery(document).on("keyup", "#keyword", function () {
            var shop_id = jQuery('#shop_id').val();
            var keyword = jQuery(this).val();
            if (keyword != '') {
                if (keyword.length > 2) {
                    var data = {'keyword': keyword, 'shop_id': shop_id};
                    $(".suggestion_div").show();
                    jQuery(".suggestion_div").html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');

                    jQuery.ajax({
                        type: 'POST',
                        url: '<?= site_url('product/search_by_keyword') ?>',
                        data: data,
                        success: function (data) {
                            if (data != '') {
                                $(".suggestion_div").html(data);
                            }
                        }
                    });
                }

            }//if keyword
            else {
                $(".suggestion_div").hide();
                $(".suggestion_div").html('');
            }
        });/*"keyup","#keyword"*/


        jQuery(document).on("click", ".select_title", function () {
            var my_val = jQuery(this).attr('prod-title');
            jQuery("#keyword").val(my_val);
            jQuery(".suggestion_div").hide('slow');
        });/*searched_string*/

        jQuery(document).click(function (e) {
            if (e.target.id == 'keyword') {
            } else {
                jQuery(".suggestion_div").hide();
            }
        });

        /** Search by Button **/

        jQuery(document).on("click", "#search", function () {

            var keyword = jQuery('#keyword').val();
            var shop_id = jQuery('#shop_id').val();

            if (keyword != '') {
                if (keyword.length > 2) {
                    var data = {'keyword': keyword, 'shop_id': shop_id};

                    jQuery(".my-search-results").html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');

                    jQuery.ajax({
                        type: 'POST',
                        url: '<?= site_url('product/search_by_string') ?>',
                        data: data,
                        success: function (data) {
                            if (data != '') {
                                jQuery(".my-search-results").html(data);
                            }
                        }
                    });
                } else {
                    jQuery('#keyword').css('border', '#F00');
                }

            } else {
                jQuery('#keyword').css('border', '#F00');
            }
        }); /*serach*/


        jQuery(document).on("click", "#fetchall", function () {

            var keyword = '';
            var shop_id = jQuery('#shop_id').val();
            var data = {'keyword': keyword, 'shop_id': shop_id};

            jQuery(".my-search-results").html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>');

            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('product/search_by_string') ?>',
                data: data,
                success: function (data) {
                    if (data != '') {
                        jQuery(".my-search-results").html(data);
                    }
                }
            });

        });/*reset*/



    });
</script>
