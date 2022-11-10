<!-- <div class="page-loader">    
    <img src="<?php echo $this->config->item('img_url') ?>loader.gif" width="160" alt="Bundle" />
</div> -->
<div class="wrap-1000">
    <div class="row">
        <div class="col-md-9 col-sm-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="card padding-20">
                        <h3 class="title line-height-normal">Today’s Status</h3>
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="dash-box bundle">
                                    <div class="icon-box">
                                        <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="42" alt="Bundle" />
                                    </div>
                                    <div class="content">
                                        <p class="name">Bundle Views Today</p>
                                        <p class="count">
                                            <?php if ($bundleViewsToday == '') {
                                            $bundleViewsToday = 0;
                                            } echo $bundleViewsToday; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="dash-box item-added">
                                    <div class="icon-box">
                                        <img src="<?php echo $this->config->item('img_url') ?>added.svg" width="52" alt="Bundle" />
                                    </div>
                                    <div class="content">
                                        <p class="name">Total Upsell Bought</p>
                                        <p class="count">
                                            <?php echo $upsellBoughtToday; ?>
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
                                        <p class="name">Total Upsell Amount</p>
                                        <p class="count">
                                            <?php if ($upsellAmountToday == '') {
                                            $upsellAmountToday = 0;
                                            } echo $upsellAmountToday; ?>
                                            <!-- <?php echo $upsellAmountToday; ?> -->
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="panel panel-default panel-dashboard bundle">
                        <div class="panel-heading">
                            <h3>Past 30 Days</h3>
                        </div>
                        <div class="panel-body">
                            <div class="report-container">
                                <div class="content">                                    
                                    <p class="name">Bundle Views</p>
                                    <p class="count"><?php echo $bundleViewsMonth; ?></p>
                                </div>
                                <div class="icon-box">
                                    <figure class="bundle">
                                        <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                    </figure>
                                </div>
                            </div>
                            <div class="report-container">
                                <div class="content">                                    
                                    <p class="name">Total Upsell Bought</p>
                                    <p class="count"><?php echo $upsellBoughtMonth; ?></p>
                                </div>
                                <div class="icon-box">
                                    <figure class="added">
                                        <img src="<?php echo $this->config->item('img_url') ?>added.svg" width="36" alt="Bundle" />
                                    </figure>
                                </div>
                            </div>
                            <div class="report-container">
                                <div class="content">                                    
                                    <p class="name">Total Upsell Amount</p>
                                    <p class="count"><?php echo $upsellAmountMonth; ?></p>
                                </div>
                                <div class="icon-box">
                                    <figure class="totalamt">
                                        <img src="<?php echo $this->config->item('img_url') ?>totalamount.svg" width="26" alt="Bundle" />
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="panel panel-default panel-dashboard added">
                        <div class="panel-heading">
                            <h3>All Time Status</h3>
                        </div>
                        <div class="panel-body">
                            <div class="report-container">
                                <div class="content">                                    
                                    <p class="name">Bundle views</p>
                                    <p class="count"><?php echo $bundleViewsAll; ?></p>
                                </div>
                                <div class="icon-box">
                                    <figure class="bundle">
                                        <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                    </figure>
                                </div>
                            </div>
                            <div class="report-container">
                                <div class="content">                                    
                                    <p class="name">Total Upsell Bought</p>
                                    <p class="count"><?php echo $upsellBoughtAll; ?></p>
                                </div>
                                <div class="icon-box">
                                    <figure class="added">
                                        <img src="<?php echo $this->config->item('img_url') ?>added.svg" width="36" alt="Bundle" />
                                    </figure>
                                </div>
                            </div>
                            <div class="report-container">
                                <div class="content">                                    
                                    <p class="name">Total Upsell Amount</p>
                                    <p class="count"><?php echo $upsellAmountAll; ?></p>
                                </div>
                                <div class="icon-box">
                                    <figure class="totalamt">
                                        <img src="<?php echo $this->config->item('img_url') ?>totalamount.svg" width="26" alt="Bundle" />
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="panel panel-default panel-dashboard totalamt">
                        <div class="panel-heading">
                            <h3>All Time Status</h3>
                        </div>
                        <div class="panel-body">
                            <div class="report-container">
                                <div class="content">                                    
                                    <p class="name">Bundle views</p>
                                    <p class="count"><?php echo $bundleViewsAll; ?></p>
                                </div>
                                <div class="icon-box">
                                    <figure class="bundle">
                                        <img src="<?php echo $this->config->item('img_url') ?>bundle.svg" width="26" alt="Bundle" />
                                    </figure>
                                </div>
                            </div>
                            <div class="report-container">
                                <div class="content">                                    
                                    <p class="name">Total Upsell Bought</p>
                                    <p class="count"><?php echo $upsellBoughtAll; ?></p>
                                </div>
                                <div class="icon-box">
                                    <figure class="added">
                                        <img src="<?php echo $this->config->item('img_url') ?>added.svg" width="36" alt="Bundle" />
                                    </figure>
                                </div>
                            </div>
                            <div class="report-container">
                                <div class="content">                                    
                                    <p class="name">Total Upsell Amount</p>
                                    <p class="count"><?php echo $upsellAmountAll; ?></p>
                                </div>
                                <div class="icon-box">
                                    <figure class="totalamt">
                                        <img src="<?php echo $this->config->item('img_url') ?>totalamount.svg" width="26" alt="Bundle" />
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 padding-left-none">
            <div class="card padding-20">
                <div class="right-box bundle">
                    <table class="content border-bottom">
                        <tr>
                            <td width="66">
                                <img src="<?php echo $this->config->item('img_url') ?>upsellview.svg" width="36" alt="Bundle" />
                            </td>
                            <td>
                                <p class="count">987</p>
                                <p class="name">Total upsells viewed</p>
                            </td>
                        </tr>
                    </table>
                    <table class="content">
                        <tr>
                            <td width="66">
                                <img src="<?php echo $this->config->item('img_url') ?>totalpurchased.svg" width="36" alt="Bundle" />
                            </td>
                            <td>
                                <p class="count">68%</p>
                                <p class="name">Total purchased</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="right-box added">                    
                    <table class="content">
                        <tr>
                            <td width="66">
                                <img src="<?php echo $this->config->item('img_url') ?>totalselltime.svg" width="36" alt="totalselltime" />
                            </td>
                            <td>
                                <p class="count">258</p>
                                <p class="name">Total sales from updates over x time</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="right-box totalamt">                    
                    <table class="content">
                        <tr>
                            <td width="66">
                                <img src="<?php echo $this->config->item('img_url') ?>totalupsellnumber.svg" width="36" alt="totalselltime" />
                            </td>
                            <td>
                                <p class="count">742</p>
                                <p class="name">Total number of upsells in the system</p>
                            </td>
                        </tr>
                    </table>
                </div>                
            </div>
        </div>
    </div>    
</div>