<?php include_once('templates/header.php'); ?>
  <div class="container">
    <div class="row">
        <div class="col-sm-12 pad0">
          <div class="response animated"></div>
          <div class="table-responsive">
            <table class="table smar7-card">
                <tbody>
                  <tr>
                    <th>Bundle Name</th>
                    <th>Bundle Type</th>
                    <!--<th class="text-center">Stats</th>-->
                    <th class="text-center">Preview</th>
                    <th class="text-center">Code</th>
                    <th class="text-center">Triggers</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Date Created</th>
                    <th width="40" class="td-np text-center">Edit</th>
                    <th width="50" class="td-np text-center">Delete</th>
                </tr>
                <?php
                  if($bundleList){
                    foreach($bundleList as $bundle){
                    //  echo "<pre>"; print_r($bundle); exit;
					$bundle_type = 'Discount Upsell';
					if($bundle->bundle_type=='1'){
						$bundle_type = 'Standard Upsell';
					}else if($bundle->bundle_type=='3'){
						$bundle_type = 'Shipping Upsell';
					}
                    ?>
                    <tr class="bundle_<?=$bundle->id?>">
                      <td><?=$bundle->bundle_label?></td>
                      <td><?=$bundle_type?></td>
                      <!--<td class="text-center"><a class="border-grey" href="javascript:void()0;"><i class="fa fa-bar-chart"></i></a></td>-->
                      <td class="text-center"><a href="#" data-toggle="modal" data-target="#preview-<?=$bundle->id?>"><i class="fa fa-search-plus"></i></a></td>
                      <td class="text-center"><?=$bundle->discount_code?></td>
                      <td class="text-center"><a href="#" data-toggle="modal" data-target="#trigger-<?=$bundle->id?>"><i class="fa fa-eye"></i></a></td>
                      <td class="text-center">
                      	<?php
							$class	= '';
							$checked	=	''; 
							if($bundle->status==1){
								$class	= 'active';
								$checked	=	'checked';
							} 
						 ?>
                        
                        	<div class="smar7-checkbox-container <?=$class?>">
                                <input type="checkbox" value="1" class="smar7-checkbox" id="active_<?=$bundle->id?>"  bundle="<?=$bundle->id?>" <?=$checked?>>
                                <label for="active_<?=$bundle->id?>"></label>
                            </div>
                      </td>
                      <td class="text-center"><?=date( 'M j Y H:i a', strtotime($bundle->date_created) )?></td>
                      <td class="text-center">
                      	<a class="border-grey" href="<?=site_url('bundles/create?id='.$bundle->id)?>"><i class="fa fa-pencil"></i></a>
                      </td>
                      <td class="text-center">
                      	<a class="border-grey delete-bundle" href="javascript:void(0);" bundle="<?=$bundle->id?>">
                        	<i class="fa fa-trash"></i>
                        </a>
                       </td>
                      </tr>
                    <?php	
						include('includes/preview-modal.php'); 
						include('includes/tirggers-modal.php'); 
                    }/*foreach*/
                  }else{
                  ?>
                  	<tr><td colspan="8">Create Your First Bundle..!!</td></tr>
                  <?php 
				  }
				   ?>

              </tbody>
            </table>
          </div>
        </div>
    </div><!--row-->

</div>


<?php include_once('templates/footer.php'); ?>

<script>
	jQuery(document).ready(function(e){
		
		jQuery('.smar7-checkbox').on('click', function(e){
			bundle = jQuery(this).attr('bundle');
			
			if(jQuery(this).attr('checked')){
				jQuery(this).attr('checked', false);
	   			jQuery(this).parent('.smar7-checkbox-container').removeClass('active');
				status = 0;
			}else{
				jQuery(this).attr('checked', true);
	   			jQuery(this).parent('.smar7-checkbox-container').addClass('active');
				status = 1;
			}
			
			changeStatus(bundle, status);
		});
		
		jQuery('.delete-bundle').on('click', function(e){
			bundle = jQuery(this).attr('bundle');
			if(confirm("Are You sure ??")){
				delete_bundle(bundle);
			}
		});

		
	});

	function changeStatus(bundle, status){
			jQuery('.response').html('');
			data = {'bundle_id':bundle, 'status':status};
			jQuery.ajax({
			  type: 'POST',
			  url : '<?=site_url('bundles/update_bundle')?>',
		
			  data: data,
			  success : function(data){
				console.log(data);
				jQuery('.response').html('<span class="text text-success"><i class="fa fa-thumbs-up animated wobble"></i> Updated Successfully..!!</span>');
				jQuery('.response').addClass('fadeInDown');
				jQuery('.response').css('padding','15px');
			  }
			});/*ajax*/	
	}/*changeStatus*/
	
	function delete_bundle(bundle){
		jQuery('.response').html('');
			data = { 'bundle_id':bundle };
			jQuery.ajax({
			  type: 'POST',
			  url : '<?=site_url('bundles/delete_bundle')?>',
		
			  data: data,
			  success : function(data){
				console.log(data);
				jQuery('.response').html('<span class="text text-success"><i class="fa fa-thumbs-up"></i> Deleted Successfully..!!</span>');
			  	jQuery('.bundle_'+bundle).remove();
			  }
			});/*ajax*/	
	}

</script>



<style>

.col-item
{
    border: 1px solid #E1E1E1;
    border-radius: 5px;
    background: #FFF;
}
.col-item .photo img
{
    margin: 0 auto;
    width: 100%;
}

.col-item .info
{
    padding: 10px;
    border-radius: 0 0 5px 5px;
    margin-top: 1px;
}

.col-item:hover .info {
    background-color: #F5F5DC;
}
.col-item .price
{
    /*width: 50%;*/
    float: left;
    margin-top: 5px;
}

.col-item .price h5
{
    line-height: 20px;
    margin: 0;
}

.price-text-color
{
    color: #219FD1;
}

.col-item .info .rating
{
    color: #777;
}

.col-item .rating
{
    /*width: 50%;*/
    float: left;
    font-size: 17px;
    text-align: right;
    line-height: 52px;
    margin-bottom: 10px;
    height: 52px;
}

.col-item .separator
{
    border-top: 1px solid #E1E1E1;
}

.clear-left
{
    clear: left;
}

.col-item .separator p
{
    line-height: 20px;
    margin-bottom: 0;
    margin-top: 10px;
    text-align: center;
}

.col-item .separator p i
{
    margin-right: 5px;
}
.col-item .btn-add
{
    width: 50%;
    float: left;
}



.col-item .btn-details
{
    width: 50%;
    float: left;
    padding-left: 10px;
}
.controls
{
    margin-top: 20px;
}
[data-slide="prev"]
{
    margin-right: 10px;
}
.col-center{
    float: none;
    margin: 0 auto;
}
.carousel-top h3{
    color: #fff;
    font-weight: bold;
    text-align: center;
    margin-bottom: 40px;
}
.modal-header-new{
    padding-right: 15px;
}
</style>
<script>
jQuery(document).ready(function () {
	jQuery('.carousel').carousel({
    	interval: false
	}); 
});
</script>