<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon.png">
    <link href="<?php echo site_url();?>/assets/css/bootstrap.min.css" rel="stylesheet" media="all" />
    <link href="<?php echo site_url();?>/assets/css/style.css" rel="stylesheet" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>/assets/css/daterangepicker.css" />
    <link href="<?php echo site_url();?>/assets/css/main.css" rel="stylesheet" media="all" />
    <link href="<?php echo site_url();?>/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo site_url();?>/assets/css/responsive.css" rel="stylesheet" media="all" />
    <link href="<?php echo site_url();?>/assets/front/css/mscroll.css" rel="stylesheet" media="all" />

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo site_url();?>assets/js/custom.js"></script>
    <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
    
    <script src="<?php echo site_url();?>assets/js/bootbox.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo site_url(); ?>/assets/js/daterangepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo site_url(); ?>/assets/js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
		ShopifyApp.init({
			apiKey : '<?php echo $this->config->item('shopify_api_key'); ?>',
			shopOrigin : '<?php echo 'https://'  . $this->shopDomain; ?>',
		   
		});
    </script>
    
    <script type="text/javascript">
		ShopifyApp.ready(function(){
			ShopifyApp.Bar.initialize({
		
			});
		});
    </script>
    
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PPLM98S');</script>
    <!-- End Google Tag Manager -->

    <?php
    if(!isset($curr_uri)){
		$curr_uri = '';
	} ?>
	
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PPLM98S"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="page-loader">    
    <img src="<?php echo $this->config->item('img_url') ?>loader.svg" width="160" alt="Bundle" />
</div> 
<?php include_once('menu.php'); ?>
