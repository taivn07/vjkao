<div class="block_banChay">
	<div class="block_title clearfix">
		<span class="icon"></span> 
		<h3 class="title"><a href="<?php echo $view->baseUrl('shopping/block/khuyen-mai');?>" title="<?php echo $view->language['productSanPhamKhuyenMai'];?>"><?php echo $view->language['productSanPhamKhuyenMai'];?></a></h3>
	</div>
	<div class="block_content">
		<div class="p_banChay">
			<ul class="products clearfix">
			<?php
			foreach ($row as $key => $val){
				$name = $val['name'];
				$code = '';
				if($val['code'] != ''){
					$code = '<p class="code">'.$view->language['productMaSanPham'].': ' . $val['code'] . '</p>';
				}
				$picture = '<img class="img" src="' . $val['picture'] . '" alt="'.$val['name'].'"/>';
					
				$synopsis = $val['synopsis'];
				$units_money = $val['units_money'];
				$price = '';
				
				if($val['price'] != 0){
					$price = '<p class="price">'.$view->language['productGiaBan'].': <span class="value">'.Zend_Locale_Format::toNumber($val['price'],array('precision' => 0)) . ' ' . $units_money.'</span></p>';
				}else{
					$price = '<p class="price">'.$view->language['productGiaBan'].': <span class="value">'.$view->language['lienHe'].'</span></p>';
				}
				
				$selloff = '';
				if($val['selloff'] > 0){
					$selloff = '<p class="p_selloff"><span>' . $val['selloff'] . '%</span></p>';
				}
				
				$addCart = '';
				if($moduleConfig['showAddCart'] == 1){
					$linkCart = $view->baseUrl('shopping/public/add-item/id/' . $val['id']);
					$addCart = '<a href="'.$linkCart.'" title="'.$view->language['productDatHang'].' '.$name.'" class="addCart" rel="nofollow">'.$view->language['productDatHang'].'</a>';
				}
					
				$urlOptions = array (
						'module' => 'shopping',
						'controller' => 'index',
						'action' => 'detail',
						'tcat' => $val ['category_alias'],
						'title' => $val ['alias'],
						'cid' => $val ['cat_id'],
						'id' => $val ['id'] );
				$linkDetial = $view->url( $urlOptions, 'shop-detail' );
			?>
				<li class="item">
					<div class="p_content">
						<div class="p_images">
							<a href="<?php echo $linkDetial;?>" title="<?php echo $name;?>">
								<?php echo $picture;?>
							</a>
						</div>
						<h4 class="p_title"><a href="<?php echo $linkDetial;?>" title="<?php echo $name;?>"><?php echo $name;?></a></h4>
						<div class="p_price">
							<?php echo $price;?>
						</div>
						<p class="p_detail">
							<?php echo $addCart;?>
							<a href="<?php echo $linkDetial;?>" title="<?php echo $name;?>" class="detail"><?php echo $view->language['chiTiet'];?></a>
						</p>
						<?php echo $selloff;?>
					</div>
				</li>
			<?php
			} 
			?>
			</ul>
		</div>
	</div>
	<script src="<?php echo $view->jsUrl . '/jcarousellite.js';?>" type="text/javascript"></script>
	<script type="text/javascript">
	$(function() {
		$(".p_banChay").jCarouselLite({
			vertical: true,
			hoverPause:true,
			visible: 1,
			auto:3000,
			speed:2000
		});
	});
	</script>
	<div class="block_bottom"></div>
</div>