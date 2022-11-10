<?php include_once('templates/header.php'); ?>

  <div class="container">
    <div class="row">
        <div class="col-md-12">
        	<h3 class="text-center">Dashboard</h3>
		</div>
       	<div class="col-md-12 text-right">
			<a class="btn btn-info btn-sm" href="<?php echo site_url('bundles/index')?>">
			  <i class="fa fa-shop"></i> Create Bundle
			</a>
        </div>
        <hr/>

    </div><!--row-->

    <div class="row">
        <div class="col-md-12">
            <div class="tabbable-panel">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs ">
                        <li class="<?php echo ($defaultTab=='awaiting')? 'active':'';?>">
                            <a href="<?php echo site_url('auth/access/awaiting')?>" >
                            Bundles Listing</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
          </div>
        </div>
    </div>
</div>
