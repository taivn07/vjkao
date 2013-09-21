<div class="block_cart">
	<div class="block_title">
		<div class="title"><?php echo $view->language['productGioHang'];?></div>
	</div>
	<div class="block_content">
		<div class="thongbao">
		<?php
			$linkCart = $view->baseUrl('/shopping/public/view-cart');
			if($countItem > 0){
				printf('<a href="'. $linkCart .'" rel="nofollow">' . $view->language['productCoSanPham'] . '</a>',$countItem);
			}else{
				printf($view->language['productCoSanPham'],'0');
			}
		?>
		</div>
	</div>
</div>