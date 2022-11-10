<style type="text/css">
    .btn-guide{
        background: rgba(38,124,238,1) !important;
        color: #FFF !important;
        margin-right: 10px !important;
    }
</style>
<div id="snackbar"></div>
<div id="snackbar_success"></div>
<div class="wrap-1000">    
    <div class="panel panel-default bundle-panel">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h3 class="title"><?php echo lang('croll_sell_bundles') ?></h3>
                </div>                
                <div class="col-md-6 col-sm-12 text-right">
                    <a href="<?= site_url('cross_sell_bundle/installation') ?>" class="btn-addbundle btn-guide"><?php echo lang('installation_guide') ?></a>
                    <a href="<?= site_url('cross_sell_bundle/create') ?>" class="btn-addbundle"><?php echo lang('add_crosssell_bundle') ?></a>
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
                            <td align="left" class="text-center">
                                <?php echo lang('target_product') ?>
                            </td>
                            <td class="text-center width-triggerproducts">
                                <?php echo lang('collection') ?>
                            </td>
                            <td class="text-center width-triggerproducts">
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
                        if (!empty($bundleList)) {
                            foreach ($bundleList as $bundle) {
                                ?>
                                <tr class="bundle_<?= $bundle->id ?>">
                                    <td align="left">
                                        <label class="fancy-checkbox">
                                            <span><i></i><?= $bundle->bundle_title ?></span>
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <figure>
                                            <img src="<?= $bundle->image ?>" class="img-responsive" alt="a" height="80" width="80"/>    
                                        </figure> 
                                    </td>
                                    <td align="text-center">
                                        <label class="fancy-checkbox">
                                            <span><i></i><?= !empty($bundle->title)?$bundle->title:'N/A' ?></span>
                                        </label>
                                    </td>
                                    <td align="text-center">
                                        <label class="fancy-checkbox">
                                            <span><i></i><?= !empty($bundle->discount_code)?$bundle->discount_code:'N/A' ?></span>
                                        </label>
                                    </td>
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
                                                <a href="<?= site_url('cross_sell_bundle/create?id=' . $bundle->id) ?>"  class="btn-action btn-action-view">
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

    function changeStatus(bundle, status) {
        jQuery('#snackbar').html('');
        data = {'bundle_id': bundle};
        var status = false;
        jQuery.ajax({
            type: 'POST',
            url: '<?= site_url('cross_sell_bundle/update_bundle') ?>',
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
            url: '<?= site_url('cross_sell_bundle/delete_bundle') ?>',
            data: data,
            success: function (data) {
                console.log(data);
                jQuery('.bundle_' + bundle).remove();
                var rowCount = jQuery('tbody tr').length;
                console.log(rowCount);
                if (rowCount == 0) {
                    var cont = '<tr><td colspan="4">Create Your First Bundle..!!</td></tr>';
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