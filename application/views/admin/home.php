<!DOCTYPE html>
<html lang="en">
<head>
    <!--
        ===
        This comment should NOT be removed.

        Charisma v2.0.0

        Copyright 2012-2014 Muhammad Usman
        Licensed under the Apache License v2.0
        http://www.apache.org/licenses/LICENSE-2.0

        http://usman.it
        http://twitter.com/halalit_usman
        ===
    -->
    <meta charset="utf-8">
    <title>Influncer House</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

    <!-- The styles -->
    <link id="bs-css" href="<?php echo site_url('assets/admin/css/bootstrap-cerulean.min.css')?>" rel="stylesheet">

    <link href="<?php echo site_url('assets/admin/css/charisma-app.css')?>" rel="stylesheet">
    <link href="<?php echo site_url('assets/admin/bower_components/fullcalendar/dist/fullcalendar.css')?>" rel='stylesheet'>
    <link href="<?php echo site_url('assets/admin/bower_components/fullcalendar/dist/fullcalendar.print.css')?>" rel='stylesheet' media='print'>
    <link href="<?php echo site_url('assets/admin/bower_components/chosen/chosen.min.css')?>" rel='stylesheet'>
    <link href="<?php echo site_url('assets/admin/bower_components/colorbox/example3/colorbox.css')?>" rel='stylesheet'>
    <link href="<?php echo site_url('assets/admin/bower_components/responsive-tables/responsive-tables.css')?>" rel='stylesheet'>
    <link href='bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css' rel='stylesheet'>
    <link href="<?php echo site_url('assets/admin/css/jquery.noty.css')?>" rel='stylesheet'>
    <link href="<?php echo site_url('assets/admin/css/noty_theme_default.css')?>" rel='stylesheet'>
    <link href="<?php echo site_url('assets/admin/css/elfinder.min.css')?>" rel='stylesheet'>
    <link href="<?php echo site_url('assets/admin/css/elfinder.theme.css')?>" rel='stylesheet'>
    <link href="<?php echo site_url('assets/admin/css/jquery.iphone.toggle.css')?>" rel='stylesheet'>
    <link href="<?php echo site_url('assets/admin/css/uploadify.css')?>" rel='stylesheet'>
    <link href="<?php echo site_url('assets/admin/css/animate.min.css')?>" rel='stylesheet'>

    <!-- jQuery -->
    <script src="<?php echo site_url('assets/admin/bower_components/jquery/jquery.min.js')?>"></script>

    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The fav icon -->
    <link rel="shortcut icon" href="<?php echo site_url('assets/admin/img/favicon.ico')?>">

</head>

<body>
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html"> <img alt="INFLUENCER HOUSE" src="<?php echo site_url('assets/admin/img/logo.png')?>" class="hidden-xs"/></a>

            <!-- user dropdown starts -->
            <div class="btn-group pull-right">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> Member</span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url('logout');?>">Logout</a></li>
                </ul>
            </div>
            <!-- user dropdown ends -->

        </div>
    </div>
    <!-- topbar ends -->
<div class="ch-container">
    <div class="row">
        
        <!-- left menu starts -->
        <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu">
                        <li class="nav-header">Main</li>
                        <li><a class="ajax-link" href="#"><i class="glyphicon glyphicon-home"></i><span> Dashboard</span></a>
                        </li>
                        <li><a class="ajax-link" href="#"><i class="glyphicon glyphicon-eye-open"></i><span> Brands</span></a>
                        </li>

                        
                </div>
            </div>
        </div>
        <!--/span-->
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>

        <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
            <div>
    <ul class="breadcrumb">
        <li>
            <a href="#">Home</a>
        </li>
        <li>
            <a href="#">Dashboard</a>
        </li>
    </ul>
</div>

 <div class="row">
    <div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class="glyphicon glyphicon-user"></i> Pending invitations</h2>

        <div class="box-icon">
            <a href="#" class="btn btn-setting btn-round btn-default"><i class="glyphicon glyphicon-cog"></i></a>
            <a href="#" class="btn btn-minimize btn-round btn-default"><i
                    class="glyphicon glyphicon-chevron-up"></i></a>
            <a href="#" class="btn btn-close btn-round btn-default"><i class="glyphicon glyphicon-remove"></i></a>
        </div>
    </div>
    <div class="box-content">
    <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
        <th>Brand</th>
        <th>Contact Email</th>
        <th>COMMISSION %</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php 
    if($brands):
    foreach($brands as $brand):?>
    <tr>
        <td><?php echo $brand->name;?></td>
        <td class="center"><?php echo $brand->email;?></td>
        <td class="center"><?php echo $brand->commission_percent;?></td>
        <td class="center">
            <a class="btn btn-success btn-sm perform-action" id="<?php echo $brand->brand_id;?>" data-modal="accept" data-title="Accept" data-toggle="modal" data-target="#delete" href="#">
                Accept
                
            </a>
             <a class="btn btn-danger btn-sm perform-action" id="<?php echo $brand->brand_id;?>" data-modal="decline" data-title="Decline" data-toggle="modal" data-target="#delete" href="#">
                Decline
                
            </a>
        </td>
    </tr>
    <?php endforeach; endif;?>
    
    </tbody>
    </table>
    </div>
    </div>
    </div>
    <!--/span-->

    </div><!--/row-->

    <div class="row">
    <div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2><i class="glyphicon glyphicon-user"></i> Active Brand Partners</h2>

        <div class="box-icon">
            <a href="#" class="btn btn-setting btn-round btn-default"><i class="glyphicon glyphicon-cog"></i></a>
            <a href="#" class="btn btn-minimize btn-round btn-default"><i
                    class="glyphicon glyphicon-chevron-up"></i></a>
            <a href="#" class="btn btn-close btn-round btn-default"><i class="glyphicon glyphicon-remove"></i></a>
        </div>
    </div>
    <div class="box-content">
    <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
        <th>Brand</th>
        <th>Coupon Code</th>
        <th>Sale Total</th>
        <th>Commission Total</th>
        <th>Unpaid Total</th>
    </tr>
    </thead>
    <tbody>
    <?php 
    if($abrands):
    foreach($abrands as $abrand):?>
    <tr>
        <td><?php echo $abrand->name;?></td>
        <td><?php echo $abrand->coupon_code;?></td>
        <td class="center">0</td>
        <td class="center">0</td>
        <td class="center">0</td>

    </tr>
    <?php endforeach; endif;?>
    
    </tbody>
    </table>
    </div>
    </div>
    </div>
    <!--/span-->

    </div><!--/row-->

    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->



    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>Settings</h3>
                </div>
                <div class="modal-body">
                    <p>Here settings can be configured...</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                    <a href="#" class="btn btn-primary" data-dismiss="modal">Save changes</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="row">
        <p class="col-md-9 col-sm-9 col-xs-12 copyright">&copy; <a href="http://usman.it" target="_blank">Influencer House</a></p>
    </footer>

</div><!--/.fluid-container-->

<!-- external javascript -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align" id="Heading">Brand action</h4>
      </div>
          <div class="modal-body">
       
       <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want perform this action?</div>
       
      </div>
        <div class="modal-footer ">
        <button type="button" class="btn btn-success action-record" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
      </div>
        </div>
    <!-- /.modal-content --> 
  </div>
      <!-- /.modal-dialog --> 
</div>

<script src="<?php echo site_url('assets/admin/bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>

<!-- library for cookie management -->
<script src="<?php echo site_url('assets/admin/js/jquery.cookie.js')?>"></script>
<!-- calender plugin -->
<script src="<?php echo site_url('assets/admin/bower_components/moment/min/moment.min.js')?>"></script>
<script src="<?php echo site_url('assets/admin/bower_components/fullcalendar/dist/fullcalendar.min.js')?>"></script>
<!-- data table plugin -->
<script src="<?php echo site_url('assets/admin/js/jquery.dataTables.min.js')?>"></script>

<!-- select or dropdown enhancer -->
<script src="<?php echo site_url('assets/admin/bower_components/chosen/chosen.jquery.min.js')?>"></script>
<!-- plugin for gallery image view -->
<script src="<?php echo site_url('assets/admin/bower_components/colorbox/jquery.colorbox-min.js')?>"></script>
<!-- notification plugin -->
<script src="<?php echo site_url('assets/admin/js/jquery.noty.js')?>"></script>
<!-- library for making tables responsive -->
<script src="<?php echo site_url('assets/admin/bower_components/responsive-tables/responsive-tables.js')?>"></script>
<!-- tour plugin -->
<script src="<?php echo site_url('assets/admin/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js')?>"></script>
<!-- star rating plugin -->
<script src="<?php echo site_url('assets/admin/js/jquery.raty.min.js')?>"></script>
<!-- for iOS style toggle switch -->
<script src="<?php echo site_url('assets/admin/js/jquery.iphone.toggle.js')?>"></script>
<!-- autogrowing textarea plugin -->
<script src="<?php echo site_url('assets/admin/js/jquery.autogrow-textarea.js')?>"></script>
<!-- multiple file upload plugin -->
<script src="<?php echo site_url('assets/admin/js/jquery.uploadify-3.1.min.js')?>"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="<?php echo site_url('assets/admin/js/jquery.history.js')?>"></script>
<script type="text/javascript">
    jQuery(function(){
        var modal = '';
          var modalId = '';
          jQuery('.perform-action').on('click', function(){
            modal = jQuery(this).data('modal');
            modalId = jQuery(this).attr('id');
          });

          jQuery('.action-record').on('click', function(){
            var button = jQuery(this);
            $.ajax({
              type: 'POST',
              dataType:'json',
              url : "<?php echo site_url('home/action');?>",
              data: {modal:modal,modal_id:modalId},
              beforeSend: function() {
                button.addClass('disabled');
                button.attr('disabled','disabled');
                },
                  success: function(msg) {
                    location.reload();
                  },
                  error: function(data) {
                    button.removeClass('disabled');
                    button.removeAttr('disabled');
                }
            });

          });

    })
</script>

</body>
</html>
