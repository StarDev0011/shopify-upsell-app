<div id="trigger-<?=$bundle->id?>" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<!-- Modal content-->
		<div class="modal-content trigger-modal">			
			<div class="modal-body">				
				<?php
					if(!empty($bundle->triggers)){
						$products = explode('#', $bundle->triggers);
						$count = 0;
						foreach($prodList as $product){
							if(in_array($product->product_id, $products)){
								$count++;
							
				?>				
				<div class="search-results bundle-products-list">
					<div class="product-search-item product-item">
						<div class="search-result-img product-item-img">
							<img src="<?=$product->image?>" alt="Product 2" class="img-search">
						</div>
						<div class="search-result-options">
							<h5 class="product-item-name mod3-head"><?=$product->title?></h5>
						</div>
					</div>					
				</div>
				<?php
							}
						}/*8foreach*/
				}else{
				?>
				<div class="search-results bundle-products-list">
					<div class="product-search-item product-item">
						<h2> No product items found ..!!</h2>
					</div>					
				</div>				
				<?php
				}
				?>
				<button type="button" class="close" data-dismiss="modal" style="color:#000000;">Close</button>
			</div><!--modal-body-->
		</div>
	</div>
</div>
	