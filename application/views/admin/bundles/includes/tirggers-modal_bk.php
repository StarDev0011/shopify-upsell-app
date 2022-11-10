<div id="trigger-<?=$bundle->id?>" class="modal fade" role="dialog">
	<div class="modal-dialog trigger-modal">
		<!-- Modal content-->
		<div class="modal-content">			
			<div class="modal-body">				
				<?php
					if(!empty($bundle->triggers)){
						$products = explode('#', $bundle->triggers);
						$count = 0;
						foreach($prodList as $product){
							if(in_array($product->product_id, $products)){
								$count++;
							
				?>				
				<div class="product-container">
					<figure class="product-img">
						<img src="<?=$product->image?>" alt="Product 2" class="img-search">
					</figure>
					<div class="product-name">
						<h5 class="product-item-name mod3-head"><?=$product->title?></h5>
					</div>
				</div>
							
						
							
						
				<?php
							}
						}/*8foreach*/
				}else{
				?>
				<div class="search-results bundle-products-list">
					<div class="product-search-item">
						<h2 class="norecord"> No product found ..!!</h2>
					</div>					
				</div>				
				<?php
				}
				?>
				<button type="button" class="close-modal" data-dismiss="modal">Close</button>
			</div><!--modal-body-->
		</div>
	</div>
</div>
	