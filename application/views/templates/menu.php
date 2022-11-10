<?php
if (!isset($curr_uri)) {
    $curr_uri = '';
}
?>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="<?php if ($curr_uri == 'dashboard') {
    echo 'active';
} ?>" ><a href="<?= site_url('auth/dashboard/' . $this->shopDomain) ?>"><?php echo lang('dashboard') ?></a></li>
                <li class="<?php if ($curr_uri == 'bundle-list' || $curr_uri == 'bundle_create') {
    echo 'active';
} ?>"><a href="<?= site_url('bundles/index') ?>">Upsell Offer</a></li>
                <?php /* <li class="<?php if ($curr_uri == 'cross-sell-list') {
    echo 'active';
} ?>"><a href="<?= site_url('cross_sell_bundle/index/') ?>"><?php echo lang('croll_sell_bundle') ?></a></li>  
                <li class="<?php if ($curr_uri == 'receipt_video') { echo 'active'; } ?>"><a href="<?php echo site_url('receipt_video/index/') ?>"><?php echo 'Video Upsell' ?></a></li>
                */ ?>
                <li class="<?php if ($curr_uri == 'settings') {
    echo 'active';
} ?>"><a href="<?= site_url('settings/index/') ?>"><?php echo lang('settings') ?></a></li>   
                <li class="<?php if ($curr_uri == 'support') { echo 'active'; } ?>"><a href="<?php echo site_url('support/index/') ?>"><?php echo 'Support' ?></a></li>   
                <li class="<?php if ($curr_uri == 'tutorial') { echo 'active'; } ?>"><a href="<?php echo site_url('support/tutorial/') ?>"><?php echo 'Tutorials' ?></a></li>   
            </ul>
        </div>
    </div>
</nav>
