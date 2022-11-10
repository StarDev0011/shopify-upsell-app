<div id="snackbar"></div>
<div id="snackbar_success"></div>
<div class="wrap-1000">    
    <div class="panel panel-default bundle-panel">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6 col-sm-8">
                    <h3 class="title"><?php echo lang('bundle_offers') ?></h3>
                </div>
                <div class="col-md-6 col-sm-4 text-right">
                    <a href="<?= site_url('bundles/create') ?>" class="btn-addbundle"><?php echo lang('add_upsell_bundle') ?></a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table bundle-list">
                    <thead>
                        <tr>
                            <td align="left" class="width-bundlename">
                                <?php echo lang('bundle_name') ?>
                            </td>
                            <td class="text-center width-triggerproducts">
                                <?php echo lang('trigger_products') ?>
                            </td>
                            <td class="text-center width-bundletype">
                                <?php echo lang('bundle_type') ?>
                            </td>
                            <td class="text-center width-discountcode">
                                <?php echo lang('discount_code') ?>
                            </td>
                            <td class="text-center width-status">
                                <?php echo lang('status') ?>
                            </td>
                            <td align="right" class="action-btn-last">
                                <?php echo lang('actions') ?>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($bundleList) {
                            foreach ($bundleList as $bundle) {
                                include('includes/tirggers-modal.php');
                                include('includes/preview-modal.php');
                                // var_dump($this->DiscountCodes->get_discount_by_id($bundle->id));
                                ?>
                                <tr class="bundle_<?= $bundle->id ?>"><!-- class="selected" -->
                                
                                    <td align="left">
                                        <label class="fancy-checkbox">
                                            <span><i></i><?= $bundle->bundle_label ?></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <figure>
                                            <img src="<?= $singleProduct->image ?>" class="img-responsive" alt="a" height="80" width="80"/>    
                                            <?php //if ($count > 1) { ?>
        <!--                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#preview-<?php //$bundle->id   ?>" class="trigger-products">
                                                    View More
                                                </a>-->
                                            <?php //} ?>
                                        </figure> 
                                    </td>
                                    <td class="text-center"><?= $this->Discounts->discountType[$bundle->discount_type]['title'] ?></td>
                                    <td class="text-center"><?= !empty($bundle->discount_code) ? $bundle->discount_code : 'N/A' ?></td>
                                    <td class="text-center">
                                        <?php
                                        $class = '';
                                        $checked = '';
                                        if ($bundle->status == 1) {
                                            $class = 'active';
                                            $checked = 'checked';
                                        }
                                        ?>
                                        <div class="smar7-checkbox-container <?= $class ?>">
                                            <input type="checkbox" value="1" class="smar7-checkbox" id="active_<?= $bundle->id ?>"  bundle="<?= $bundle->id ?>" <?= $checked ?>>
                                            <label for="active_<?= $bundle->id ?>"></label>
                                        </div>
                                    </td>
                                    <td align="right" class="action-btn-last">
                                        <ul class="action-ul">
                                            <li>
                                                <a href="javascript:void(0)" data-slug="<?= $singleProduct->product_slug ?>" data-variant="<?php echo $defaultVariant; ?>" data-bundle-id="<?= $bundle->id ?>" class="btn-action btn-action-view trigger-products show-trigger-product">
                                                    Preview
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?= site_url('bundles/create?id=' . $bundle->id) ?>"  class="btn-action btn-action-view">
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" class="delete-bundle btn-action btn-action-icon" bundle="<?= $bundle->id ?>"  title="Delete">
                                                    <span class="icon-delete"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>

                                <?php
                            }
                        } else {
                            ?>
                            <tr><td colspan="6">Create Your First Bundle!</td></tr>
                            <?php
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<script>
    jQuery(document).ready(function (e) {

//        jQuery('.trigger-products').on('click', function (e) {
//            console.log('avbc');
//        });
        jQuery(document).on('change', '.variant_options', function (e) {
            var img = jQuery('option:selected', this).attr('image');
            var productID = jQuery('option:selected', this).attr('productID');
            jQuery('#img_' + productID).attr('src', img);
        });

        jQuery('body').on('click', '.close-popup,.close_me', function (e) {
            console.log('abc');
            jQuery('.overlay-bg').remove();
        });

        jQuery('.show-trigger-product').on('click', function (e) {
            var prodSlug = jQuery(this).data('slug');
            var variantId = jQuery(this).data('variant');
            var bundleId = jQuery(this).data('bundle-id');
            data = {'product_slug': prodSlug, 'variantId': variantId, 'bundleId': bundleId};
            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('bundles/getCartPagePopup') ?>',
                data: data,
                async: false,
                dataType: 'json',
                success: function (data) {
                    jQuery('div.overlay-bg').remove();
                    if (data.status == 'success') {
                        if (data.isSkipNext == 1) {
                            jQuery('body').append(data.content);
                            jQuery('.overlay-bg').css('display', 'block');
                            if (data.total_products <= 5) {
                                jQuery('.sp').first().addClass('active');
                                jQuery('.sp').hide();
                                jQuery('.active').show();
                            } else {
                                jQuery(".popup-product").mCustomScrollbar({
                                    theme: "dark",
                                });
                            }
                        } else {
                            if (jQuery("#upsell_products").length != 0) {
                                console.log('div found');
                                jQuery('#upsell_products').append(data.content);
                            } else {
                                console.log('div not found');
                                jQuery('body').append(data.content);
                            }
                        }
                        cnt = 1;
                    }
                },
            });
        });

        /**
         * When user click on skip and next button
         */
        jQuery(document).on('click', 'a.skip-this', function () {

            var slide = jQuery(this).data('slide');
            //console.log(slide);
            jQuery(this).parents('.sp').removeClass('active');
            if (slide == 'next') {
                if (jQuery(this).parents('.sp').next().length < 1) {
                    jQuery(this).parents('#slider').find('.sp:first').addClass('active');
                } else {
                    jQuery(this).parents('.sp').next().addClass('active');
                }
            } else {
                //console.log(jQuery(this).parents('.sp').prev().length);
                if (jQuery(this).parents('.sp').prev().length != 0)
                    jQuery(this).parents('.sp').prev().addClass('active').next().removeClass('active');
                else {
                    jQuery(".sp:visible").removeClass('active');
                    jQuery(".sp:last").addClass('active');
                }
            }
            jQuery(".col-item").removeClass("fadeInLeft")
            jQuery(".col-item").addClass("fadeInLeft");

            jQuery('.sp').hide();
            jQuery('.sp.active').show().animate({"margin-right": '-=200'});
        });

        jQuery('.smar7-checkbox').on('click', function (e) {
            bundle = jQuery(this).attr('bundle');
            if (jQuery(this).attr('checked')) {
                status = 0;
            } else {
                status = 1;
            }
            var result = changeStatus(bundle, status);
            if (result == 'success') {
                if (jQuery(this).attr('checked')) {
                    jQuery(this).attr('checked', false);
                    jQuery(this).parent('.smar7-checkbox-container').removeClass('active');
                } else {
                    jQuery(this).attr('checked', true);
                    jQuery(this).parent('.smar7-checkbox-container').addClass('active');
                }
            }
        });

        jQuery('.delete-bundle').on('click', function (e) {
            bundle = jQuery(this).attr('bundle');

            bootbox.confirm({
                message: 'Are you sure want to delete this record?',
                buttons: {
                    confirm: {
                        label: 'Yes',
                    },
                    cancel: {
                        label: 'No',
                    }
                },
                callback: function (result) {
                    if (result)
                    {
                        delete_bundle(bundle);
                    }
                }
            });
        });
    });

    function changeStatus(bundle, status) {
        jQuery('#snackbar').html('');
        data = {'bundle_id': bundle};
        var status = false;
        jQuery.ajax({
            type: 'POST',
            url: '<?= site_url('bundles/update_bundle') ?>',
            data: data,
            dataType: 'json',
            async: false,
            success: function (data) {
                status = data.status;
                if (status == 'success') {
                    jQuery('#snackbar_success').html(data.msg);
                    var x = document.getElementById("snackbar_success");
                } else {
                    jQuery('#snackbar').html(data.msg);
                    var x = document.getElementById("snackbar");
                }
                x.className = "show";
                setTimeout(function () {
                    x.className = x.className.replace("show", "");
                }, 10000);

            }
        });
        return status;
    }

    function delete_bundle(bundle) {
        jQuery('.response').html('');
        data = {'bundle_id': bundle};
        jQuery(".page-loader").show();
        jQuery.ajax({
            type: 'POST',
            url: '<?= site_url('bundles/delete_bundle') ?>',
            data: data,
            success: function (data) {
                console.log(data);
                jQuery('.bundle_' + bundle).remove();
                var rowCount = jQuery('tbody tr').length;
                console.log(rowCount);
                if (rowCount == 0) {
                    var cont = '<tr><td colspan="6">Create Your First Bundle..!!</td></tr>';
                    jQuery(".bundle-list > tbody").append(cont);
                }
                jQuery(".page-loader").hide();
                jQuery('#snackbar_success').html('Record Deleted Successfully..!!');
                var x = document.getElementById("snackbar_success");
                x.className = "show";
                setTimeout(function () {
                    x.className = x.className.replace("show", "");
                }, 3000);


            }
        });/*ajax*/
    }
</script>