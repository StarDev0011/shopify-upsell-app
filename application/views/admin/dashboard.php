<?php //header("Content-Security-Policy: frame-ancestors 'self'"); ?>

<title>Smart Cart Upsell Bundle</title>
<link rel="shortcut icon" type="image/jpg" href="../../../favicon.ico"/>
<style>body { margin:0px !important; } </style>

<?php
    ini_set("display_errors","Off");
    error_reporting(E_ALL);
    if(!isset($_GET['dashed'])) { ?>
        <iframe border="0" style="border:none; width:100%; height:100%" title="Smart Cart Upsell Bundle" src="https://smartcartupsellbundle.com/auth/dashboard?hmac=<?php echo  $_SESSION['GETDATA']['hmac']; ?>&locale=en&new_design_language=true&session=<?php echo $_SESSION['GETDATA']['session']; ?>&shop=<?php echo $_SESSION['GETDATA']['shop']; ?>&<?php echo $_SESSION['GETDATA']['timestamp']; ?>&dashed=1" name="app-iframe" context="Main" style="position: relative; border: none; width: 100%; flex: 1 1 0%; display: flex;"></iframe>
        <?php
        die();
    }
?>
<div class="wrap-1000">
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
                <div class="col-md-3">
                    <!--<div class="card">
                        <div class="right-box bundle" style="margin-bottom:0;">
                            <table class="content">
                                <tr>
                                    <td width="66">
                                        <img src="<?php echo $this->config->item('img_url') ?>totalamount.svg" width="28" alt="Bundle" style="display:block;margin:auto;margin-bottom:5px;" />
                                        <p class="count">
                                            <?php 
                                            $query = 'SELECT price_tier, profit FROM shop WHERE myshopify_domain = "' . $shop . '";';
                                            $row = $this->db->query($query)->row();
                                            $price = $row->price_tier;
                                            if (!$price)
                                                $price = "000";
                                            echo "$" . substr($price, 0, -2) . "<small style='font-size:11px;'>/mo</small>";
                                             ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="name" style="margin-bottom:5px;">
                                            Price Tier
                                        </p>
                                        <p class="name" style="margin-top:5px;margin-bottom:0px;"><?php 
                                        if (!$row->profit)
                                            $row->profit = 0;
                                        switch(true) {
                                            case $row->profit >= 50000:
                                                echo "You're on the highest plan for having over $50k of profit.";
                                                break;
                                            case $row->profit >= 10000:
                                                echo "$" . round((50000 - $row->profit), 2) . " of profit until the $149 tier.";
                                                break;
                                            case $row->profit >= 1000:
                                                echo "$" . round((10000 - $row->profit), 2) . " of profit until the $99 tier.";
                                                break;
                                            case $row->profit >= 500:
                                                echo "$" . round((1000 - $row->profit), 2) . " of profit until the $59 tier.";
                                                break;
                                            case $row->profit >= 100:
                                                echo "$" . round((500 - $row->profit), 2) . " of profit until the $39 tier.";
                                                break;
                                            default:
                                                echo "$" . round((100 - $row->profit), 2) . " of profit until the $19 tier.";
                                                break;
                                        } ?> 
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>-->
                    <div class="card padding-20">
                        <p><b>Pick Date Range:</b></p>
                        <input type="" name="dates" value="<?php echo date('M DD, YY') ?>" class="total-sale-date">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
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
                                                <?php echo round($upsellAmountYest, 2); ?>
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
                        <div class="col-md-3 col-sm-12">
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
                                                <?php echo round($upsellAmountMonth ,2); ?>
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
                        <div class="col-md-3 col-sm-12">
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
                        <div class="col-md-3 col-sm-12">
                            <div class="panel panel-default panel-dashboard added">
                                <div class="panel-heading">
                                    <h3>Date Range Stats</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="report-container">
                                        <div class="content">
                                            <p class="name">
                                                <?php echo lang('bundle_views') ?>
                                            </p>
                                            <p class="count" id="total_views_filter">
                                                <span style="opacity:0.5;">0</span>
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
                                            <p class="count" id="total_cart_filter">
                                                <span style="opacity:0.5;">0</span>
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
                                            <p class="count" id="total_sale_filter">
                                                <span style="opacity:0.5;">0</span>
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
                                            <p class="count" id="total_upselltotal_filter">
                                                <span style="opacity:0.5;">0.00</span>
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
                                            <p class="count" id="total_upsellavg_filter">
                                                <span style="opacity:0.5;">0.00</span>
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
                                            <p class="count" id="total_conversionrate_filter">
                                                <span style="opacity:0.5;">0%</span>
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
                    <div class="right-box">
                    	<input type="button" class="btn btn-info" value='Facebook Chat' onclick= "openFacebookChate();">
                        <?php
                            
                        ?>
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
            $('#total_sale_filter').html("0");
            $('#total_views_filter').html("0");
            $('#total_cart_filter').html("0");
            $('#total_upsellavg_filter').html("0");
            $('#total_upselltotal_filter').html("0");
            $('#total_conversionrate_filter').html("0");
            jQuery.ajax({
                type: 'POST',
                url: '<?= site_url('auth/get_total_sells') ?>',
                data: {
                    'startDate': picker.startDate.format('DD/MM/YYYY'), 
                    'endDate': picker.endDate.format('DD/MM/YYYY'), 
                    'shopId': '<?php echo $this->shopId ?>'
                },
                dataType: 'json',
                success: function (data) {
                    $('#total_sale_filter').html(data.orders);
                    $('#total_views_filter').html(data.views);
                    $('#total_cart_filter').html(data.cart);
                    $('#total_upsellavg_filter').html(data.upsells_avg);
                    $('#total_upselltotal_filter').html(data.upsells_total);
                    $('#total_conversionrate_filter').html(data.conversion_rate);
                }
            });
        });
    });


    
    function openFacebookChate () {
        
  	  var myWindow = window.open("/fb.php", "MsgWindow", "width=500,height=500");
	
	  
    }
    </script>
