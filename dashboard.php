<?php
//header("Content-Security-Policy: frame-ancestors 'self'");

?><style>body { margin:0px !important; } </style>
<?php

/*
ini_set("display_errors","Off");
error_reporting(E_ALL);
if(!isset($_GET['dashed']))
{
?>

<iframe border="0" style="border:none; width:100%; height:100%" title="Smart Cart Upsell Bundle" src="https://smartcartupsellbundle.com/auth/dashboard?hmac=<?php echo  $_SESSION['GETDATA']['hmac']; ?>&locale=en&new_design_language=true&session=<?php echo $_SESSION['GETDATA']['session']; ?>&shop=<?php echo $_SESSION['GETDATA']['shop']; ?>&<?php echo $_SESSION['GETDATA']['timestamp']; ?>&dashed=1" name="app-iframe" context="Main" style="position: relative; border: none; width: 100%; flex: 1 1 0%; display: flex;"></iframe>

<?php
die();
}
*/

?><div class="wrap-1000">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-9">
                    <div class="card height-162 padding-20">
                        <a href="<?= site_url('auth/dashboard/' . $this->shopDomain) ?>" class="refresh-dashboard">
                            <img src="<?php echo $this->config->item('img_url') ?>refresh.svg" width="20" alt="Bundle" />
                            <span>Refresh Dashboard</span>
                        </a>
                        <h3 class="title line-height-normal">
                            <?php echo lang('today_status') ?>
                            <!--Today’s Stats-->
                        </h3>
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="dash-box bundle">
                                    <div class="icon-box">
                                        <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="42" alt="Bundle" />
                                    </div>
                                    <div class="content">
                                        <p class="name">
                                            <?php echo lang('bundle_views') ?>
                                        </p>
                                        <p class="count">
                                            <?php
                                            if ($bundleViewsToday == '')
                                            {
                                                $bundleViewsToday = 0;
                                            } echo $bundleViewsToday;
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="dash-box totalamt">
                                    <div class="icon-box">
                                        <img src="<?php echo $this->config->item('img_url') ?>totalamount.svg" width="42" alt="Bundle" />
                                    </div>
                                    <div class="content">
                                        <p class="name">
<?php echo lang('added_to_cart') ?>
                                        </p>
                                        <p class="count">
<?php echo $addedCartToday; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="dash-box item-added">
                                    <div class="icon-box">
                                        <img src="<?php echo $this->config->item('img_url') ?>totalselltime.svg" width="52" alt="Bundle" />
                                    </div>
                                    <div class="content">
                                        <p class="name">
<?php echo lang('today_total_upsell') ?>
                                            <!--Total Sales-->
                                        </p>
                                        <p class="count">
<?php echo $upsellBoughtToday; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-3 padding-left-none">
                    <div class="card height-162 padding-20">
                        <div class="right-box added">
                            <table class="content">
                                <tbody>
                                    <tr>
                                        <td width="66">
                                            <img src="<?php echo $this->config->item('img_url') ?>totalselltime.svg" width="36" alt="totalselltime">
                                        </td>
                                        <td>
                                            <p class="name">Total Sales for Selected Dates</p>
                                            <p class="count" id="total_sale_filter"><?php echo $totalTodaysell; ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <input type="" name="dates" value="<?php echo date('M DD, YY') ?>" class="total-sale-date">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="panel panel-default panel-dashboard bundle">
                                <div class="panel-heading">
                                    <h3>
<?php echo lang('yesterday_status') ?>
                                        <!--Yesterday’s Stats-->
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
<?php echo lang('bundle_views') ?>
                                            </p>
                                            <p class="count">
<?php echo $bundleViewsYest; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
<?php echo lang('added_to_cart') ?>
                                            </p>
                                            <p class="count">
<?php echo $addedCartYest; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="totalamt">
                                                <img src="<?php echo $this->config->item('img_url') ?>totalamount.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
<?php echo lang('total_upsell_sales') ?>
                                                <!--Total Sales-->
                                            </p>
                                            <p class="count">
<?php echo $upsellBoughtYest; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="added">
                                                <img src="<?php echo $this->config->item('img_url') ?>totalselltime.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
<?php echo lang('revenue_from_upsell') ?><?php echo ' (' . $this->shopCurrency . ')' ?>
                                            </p>
                                            <p class="count">
                                                <?php echo $upsellAmountYest;
                                                ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
<?php echo lang('avg_revenue_from_upsell') ?><?php echo ' (' . $this->shopCurrency . ')' ?>
                                            </p>
                                            <p class="count">
                                                <?php
                                                $avg = 0;
                                                if ($upsellBoughtYest != 0 || $upsellBoughtYest != 0)
                                                {
                                                    $avg = $upsellAmountYest / $upsellBoughtYest;
                                                }
                                                echo number_format($avg, 2);
                                                ?>
                                            </p>

                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('conversion_rate') ?>
                                            </p>
                                            <p class="count">
<?php echo round($converYest, 2) . ' %'; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="panel panel-default panel-dashboard added">
                                <div class="panel-heading">
                                    <h3><?php echo lang('past_30_day_status') ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('bundle_views') ?>
                                            </p>
                                            <p class="count">
<?php echo $bundleViewsMonth; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('added_to_cart') ?>
                                            </p>
                                            <p class="count">
<?php echo $addedCartMonth; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="totalamt">
                                                <img src="<?php echo $this->config->item('img_url') ?>totalamount.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
<?php echo lang('total_upsell_sales') ?>
                                                <!--Total Sales-->
                                            </p>
                                            <p class="count">
<?php echo $upsellBoughtMonth; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="added">
                                                <img src="<?php echo $this->config->item('img_url') ?>totalselltime.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('revenue_from_upsell') ?><?php echo ' (' . $this->shopCurrency . ')' ?>
                                            </p>
                                            <p class="count">
<?php echo $upsellAmountMonth; ?>
                                            </p>

                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('avg_revenue_from_upsell') ?><?php echo ' (' . $this->shopCurrency . ')' ?>
                                            </p>
                                            <p class="count">
                                                <?php
                                                $avg = 0;
                                                if ($upsellBoughtMonth != 0 || $upsellBoughtMonth != 0)
                                                {
                                                    $avg = $upsellAmountMonth / $upsellBoughtMonth;
                                                }
                                                echo number_format($avg, 2);
                                                ?>
                                            </p>

                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('conversion_rate') ?>
                                            </p>
                                            <p class="count">
<?php echo round($converMonth, 2) . ' %'; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="panel panel-default panel-dashboard totalamt">
                                <div class="panel-heading">
                                    <h3>
<?php echo lang('all_time_status') ?>
                                        <!--All Time Stats-->
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('bundle_views') ?>
                                            </p>
                                            <p class="count">
<?php echo $bundleViewsAll; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('added_to_cart') ?>
                                            </p>
                                            <p class="count">
<?php echo $addedCartAll; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="totalamt">
                                                <img src="<?php echo $this->config->item('img_url') ?>totalamount.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('total_upsell_sales') ?>
                                                <!--Total Sales-->
                                            </p>
                                            <p class="count">
<?php echo $upsellBoughtAll; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="added">
                                                <img src="<?php echo $this->config->item('img_url') ?>totalselltime.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('revenue_from_upsell') ?><?php echo ' (' . $this->shopCurrency . ')' ?>
                                            </p>
                                            <p class="count">
<?php echo round($upsellAmountAll,2); ?>
                                            </p>

                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('avg_revenue_from_upsell'); ?><?php echo ' (' . $this->shopCurrency . ')' ?>
                                            </p>
                                            <p class="count">
                                                <?php
                                                $avg = 0;
                                                if ($upsellBoughtAll != 0 || $upsellBoughtAll != 0)
                                                {
                                                    $avg = $upsellAmountAll / $upsellBoughtAll;
                                                }
                                                echo number_format($avg, 2);
                                                ?></p>

                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
<?php echo lang('conversion_rate') ?>
                                            </p>
                                            <p class="count">
<?php echo round($converAll, 2) . ' %'; ?>
                                            </p>
                                        </div>
                                        <div class="icon-box">
                                            <figure class="bundle">
                                                <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 padding-left-none">
                    <div class="card padding-20">
                        <div class="right-box bundle">
                            <table class="content border-bottom">
                                <tr>
                                    <td width="66">
                                        <img src="<?php echo $this->config->item('img_url') ?>totalselltime.svg" width="36" alt="Bundle" />
                                    </td>
                                    <td>
                                        <p class="name">
                                            Total Sales
                                        </p>
                                        <p class="count">
<?php echo $totalSale; ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <table class="content">
                                <tr>
                                    <td width="66">
                                        <img src="<?php echo $this->config->item('img_url') ?>totalpurchased.svg" width="36" alt="Bundle" />
                                    </td>
                                    <td>
                                        <p class="name">
<?php echo lang('total_purchased') ?>
                                        </p>
                                        <p class="count">
<?php echo $totalPurchased; ?>%
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="right-box">
                    	<input type="button" class="btn btn-info" value='Facebook Chat' onclick= "openFacebookChate();">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(window).on('load', function () {
        $('input[name="dates"]').daterangepicker({
//        maxSpan: {
//            "days": 2
//        },
//autoUpdateInput: false,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(2, 'hour'),
            locale: {
                format: 'MMM DD, YYYY'
            }
        });

        $('input[name="dates"]').on('apply.daterangepicker', function (ev, picker) {
            console.log("A new date selection was made: " + picker.startDate.format('ll') + " End Date: " + picker.endDate.format('ll'));
            $(this).val(picker.startDate.format('ll') + ' - ' + picker.endDate.format('ll'));
            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('auth/get_total_sells') ?>',
                data: {'startDate': picker.startDate.format('DD/MM/YYYY'), 'endDate': picker.endDate.format('DD/MM/YYYY'), 'shopId': '<?php echo $this->shopId ?>'},
                dataType: 'json',
                success: function (data) {
                    $('#total_sale_filter').html(data.cnt);
                }
            });
        });
    });


    
    function openFacebookChate () {
        
  	  var myWindow = window.open("/fb.php", "MsgWindow", "width=500,height=500");
	
	  
    }
    </script>
