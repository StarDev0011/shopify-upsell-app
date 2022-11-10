<?php include_once('templates/header.php'); ?>
<link rel="stylesheet" href="<?php echo site_url();?>/assets//css/jquery.fancybox.min.css" type="text/css" media="screen" />
<style type="text/css">
	
.wrap {
	margin-top: 30px;
    box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
    border-radius: 4px;
}

a:focus,
a:hover,
a:active {
    outline: 0;
    text-decoration: none;
}

.panel {
    border-width: 0 0 1px 0;
    border-style: solid;
    border-color: #fff;
    background: none;
    box-shadow: none;
}

.panel:last-child {
    border-bottom: none;
}

.panel-group > .panel:first-child .panel-heading {
    border-radius: 4px 4px 0 0;
}

.panel-group .panel {
    border-radius: 0;
}

.panel-group .panel + .panel {
    margin-top: 0;
}

.panel-heading {
    background-color: #00ccff;
    border-radius: 0;
    border: none;
    color: #fff;
    padding: 0;
}

.panel-title a {
    display: block;
    color: rgba(33, 43, 53, 0.87);
    padding: 10px;
    position: relative;
    font-size: 18px;
    font-weight: 400;
}

.panel-body {
    background: #fff;
}

.panel:last-child .panel-body {
    border-radius: 0 0 4px 4px;
}

.panel:last-child .panel-heading {
    border-radius: 0 0 4px 4px;
    transition: border-radius 0.3s linear 0.2s;
}

.panel:last-child .panel-heading.active {
    border-radius: 0;
    transition: border-radius linear 0s;
}
/* #bs-collapse icon scale option */

.panel-heading a:before {
    content: '\f106';
    position: absolute;
    font-family: 'fontAwesome';
    right: 5px;
    top: 10px;
    font-size: 24px;
    transition: all 0.5s;
    transform: scale(1);
}

.panel-heading.active a:before {
    content: ' ';
    transition: all 0.5s;
    transform: scale(0);
}
a.collapsed:before{
	content: '\f107';
    position: absolute;
    font-family: 'fontAwesome';
}

#bs-collapse .panel-heading a:after {
    content: '\f107';
    font-size: 24px;
    position: absolute;
    font-family: 'fontAwesome';
    right: 5px;
    top: 10px;
    transform: scale(0);
    transition: all 0.5s;
}

#bs-collapse .panel-heading.active a:after {
    content: '\e909';
    transform: scale(1);
    transition: all 0.5s;
}
/* #accordion rotate icon option */

#accordion .panel-heading a:before {
    content: '\e316';
    font-size: 24px;
    position: absolute;
    font-family: 'fontAwesome';
    right: 5px;
    top: 10px;
    transform: rotate(180deg);
    transition: all 0.5s;
}

#accordion .panel-heading.active a:before {
    transform: rotate(0deg);
    transition: all 0.5s;
}
.panel .panel-heading{
	padding: 0 10px;

}
.panel .panel-heading .panel-title{
 	background-color: #eee;
 	text-align: left;
 	position: relative;
}
h4.panel-title:before {
    /*content: '\f128';*/
    position: absolute;
    /*font-family: 'fontAwesome';*/
    top: 20px;
    color: #fff;
}
.panel .panel-body{
	text-align: left;
	font-size: 16px;
	color: rgba(33, 43, 53, 0.87);
}
.bg-white-faq {
    background-color: #ffffff;
    padding-bottom: 20px;
    border-radius: 7px;
}
.panel-body{
	background-color: #f5f5f5;
}
ul.panel-body{
	list-style: none;
}
ul.panel-body li {
    padding-left: 30px;
    position: relative;
}
/*ul.panel-body li:before{
	content: '\f14a';
    position: absolute;
    font-family: 'fontAwesome';
    left: 0px;
}*/
.panel-title a{
	font-family: 'Lato', sans-serif !important;
}
</style>
<style type="text/css">
.white-bg-guide{
        background-color: #fff;
        padding: 15px 30px;
        border-radius: 7px;
}
ul.start-guid-items {
    padding: 0;
    list-style: none;
}
ul.start-guid-items li {
    font-size: 16px;
}
ul.start-guid-items li i {
    color: #00ccff;
    padding-right: 10px;
}
.white-bg-guide h2 {
    font-size: 21px;
}
.white-bg-guide p {
    font-size: 16px;
    letter-spacing: 1px;
    margin-top: 20px;
    margin-bottom: 20px;
}
.white-bg-guide p b{
    font-size: 16px;
}
</style>
<div class="container">
      <div class="row">
    <div class="white-bg-guide">
          <h2>Let’s Get Started ..!</h2>
          <p>With Customizable Upsell offers you can increase the amount of every single checkout.
        Based simply off of what the customer adds to their cart, they will be given relevant options to ADD-ON to their purchase given by rules that YOU set!</p>
          <p><b>6 Different Types of Upsells Built Right In Ready To Go!</b></p>
          <ul class="start-guid-items">
        <li><i class="fa fa-thumbs-up"></i> Standard Upsells </li>
        <li><i class="fa fa-thumbs-up"></i> Discount Upsells </li>
        <li><i class="fa fa-thumbs-up"></i> Free Shipping </li>
        <li><i class="fa fa-thumbs-up"></i> Buy One Get One </li>
        <li><i class="fa fa-thumbs-up"></i> FREE just Pay Shipping </li>
        <li><i class="fa fa-thumbs-up"></i> Buy 2 Get a 3rd FREE </li>
      </ul>
          
          <!---- FAQS ------->
          
          <div class="panel-group wrap" id="bs-collapse">
        <div class="panel">
              <div class="panel-heading">
            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#bs-collapse" href="#one"> How Standard Bundle Upsell Works? </a> </h4>
          </div>
              <div id="one" class="panel-collapse collapse">
            <ul class="panel-body">
                  <li><i class="fa fa-check text-success"></i> Create a New Bundle with upsell type as standard.</li>
                  <li><i class="fa fa-check text-success"></i> Add ‘trigger’ as well ‘products’ to this bundle</li>
                  <li><i class="fa fa-check text-success"></i> When user click at ‘Checkout’ button at store front this upsell will show products as pop-up. </li>
                </ul>
          </div>
            </div>
        <!-- end of panel -->
        
        <div class="panel">
              <div class="panel-heading">
            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#bs-collapse" href="#two"> How Discount Bundle Upsell Works? </a> </h4>
          </div>
              <div id="two" class="panel-collapse collapse">
            <ul class="panel-body">
                  <li>For creating discount bundle offer </li>
                  <li><i class="fa fa-check text-success"></i> Create discount code first. <a id="discount" href="<?php echo site_url();?>/assets/img/discount.png"><i class="fa fa-eye"></i></a></li>
                  <li><i class="fa fa-check text-success"></i> Select discount type. </li>
                  <li><i class="fa fa-check text-success"></i> Add products to this discount listing</li>
                  <li><i class="fa fa-check text-success"></i> Set range for cart amount.</li>
                  <li><i class="fa fa-check text-success"></i> When user will reach amount limit he will get discount ad-on at checkout.</li>
                </ul>
          </div>
            </div>
        <!-- end of panel -->
        
        <div class="panel">
              <div class="panel-heading">
            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#bs-collapse" href="#two1"> How Free Shipping Upsell Bundle Works? </a> </h4>
          </div>
              <div id="two1" class="panel-collapse collapse">
            <ul class="panel-body">
                  <li>For creating free shipping discount upsell bundle </li>
                  <li><i class="fa fa-check text-success"></i> Create discount code first.</li>
                  <li><i class="fa fa-check text-success"></i> Select discount type as 'free shipping'. <a id="shipping" href="<?php echo site_url();?>/assets/img/shipping.png"><i class="fa fa-eye"></i></a></li>
                  <li><i class="fa fa-check text-success"></i> Add products to this discount listing</li>
                  <li><i class="fa fa-check text-success"></i> Set range for cart amount.</li>
                  <li><i class="fa fa-check text-success"></i> When user will reach amount limit he will get discount ad-on at checkout.</li>
                </ul>
          </div>
            </div>
        <!-- end of panel -->
        
        <div class="panel">
              <div class="panel-heading">
            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#bs-collapse" href="#three"> How Buy One Get One Bundle Type Works? </a> </h4>
          </div>
              <div id="three" class="panel-collapse collapse">
            <ul class="panel-body">
                  <li>For creating buy one get one offer , </li>
                  <li><i class="fa fa-check text-success"></i> Create Free Products with price ‘0.00’</li>
                  <li><i class="fa fa-check text-success"></i> Create a new Collection </li>
                  <li><i class="fa fa-check text-success"></i> Add Free Products to this Collection by setting condition item price “0.00”. <a id="createcol" href="<?php echo site_url();?>/assets/img/createcol.png"><i class="fa fa-eye"></i></a></li>
                  <li><i class="fa fa-check text-success"></i> Make this Collection invisible, so no one can access this collection. <a id="editcol" href="<?php echo site_url();?>/assets/img/editcol.png"><i class="fa fa-eye"></i></a></li>
                  <li><i class="fa fa-check text-success"></i> Then “Create New Bundle” and add free products to bundle products.</li>
                </ul>
          </div>
            </div>
        <!-- end of panel --> 
        
        
        <div class="panel">
              <div class="panel-heading">
            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#bs-collapse" href="#5"> How FREE just Pay Shipping Upsell Bundle Works? </a> </h4>
          </div>
              <div id="5" class="panel-collapse collapse">
            <ul class="panel-body">
                  <li>For creating free just pay shipping upsell bundle </li>
                  <iframe style="margin:5px" width="560" height="315" src="https://www.youtube.com/embed/itj2HPUSgIs" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                </ul>
          </div>
            </div>
        <!-- end of panel -->
        
        <div class="panel">
              <div class="panel-heading">
            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#bs-collapse" href="#6"> Buy 2 Get a 3rd FREE Upsell Bundle Works? </a> </h4>
          </div>
              <div id="6" class="panel-collapse collapse">
            <ul class="panel-body">
                  <li>For creating Buy 2 Get a 3rd FREE upsell bundle </li>
                  <li><i class="fa fa-check text-success"></i> When user will purchase 2 Products then 3rd Product will Free.</li>
                </ul>
          </div>
            </div>
        <!-- end of panel -->
        
        
      </div>
        </div>
  </div>
    </div>
<!--container-->
<?php include_once('templates/footer.php'); ?>
<script type="text/javascript" src="<?php echo site_url();?>/assets/js/jquery.fancybox.min.js"></script>
<script>
	$(document).ready(function() {
		$("a#discount").fancybox();
		$("a#shipping").fancybox();
		$("a#createcol").fancybox();
		$("a#editcol").fancybox();
	});

</script>