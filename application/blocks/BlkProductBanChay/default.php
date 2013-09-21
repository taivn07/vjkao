<div class="block_banChay">
	<div class="block_title clearfix">
		<span class="icon"></span> 
		<h3 class="title"><a href="<?php echo $view->baseUrl('shopping/block/ban-chay');?>" title="<?php echo $view->language['productSanPhamBanChay'];?>"><?php echo $view->language['productSanPhamBanChay'];?></a></h3>
	</div>
	<div class="block_content">
		<div class="p_banChay">
			<ul>
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
				$price_old = '';
				$selloff = '';
				if($val['price'] != 0){
					if($val['selloff'] > 0){
						$price = '<p class="price"><span class="value">'.Zend_Locale_Format::toNumber($val['price'] - ($val['selloff']/100*$val['price']),array('precision' => 0)) . ' ' . $units_money.'</span></p>';
						$selloff = '<p class="p_selloff"><span>-' . $val['selloff'] . '%</span></p>';
						$price_old = '<p class="price_old"><span class="value">'.Zend_Locale_Format::toNumber($val['price'],array('precision' => 0)) . ' ' . $units_money.'</span></p>';
					} else {
						$price = '<p class="price"><span class="value">'.Zend_Locale_Format::toNumber($val['price'],array('precision' => 0)) . ' ' . $units_money.'</span></p>';
					}
				}else{
					$price = '<p class="price"><span class="value">'.$view->language['lienHe'].'</span></p>';
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
				
				$tooltip = '<div class=tooltip_title>'.$name.'</div><div class=tooltip_content><img class=img src=' . $val['picture'] . ' alt='.$val['name'].'/></div>';
			?>
				<li class="item">
					<div class="p_content">
						<div class="p_images">
							<a href="<?php echo $linkDetial;?>" title="<?php echo $name;?>" onmouseover="tooltip.show('<?php echo $tooltip;?>')" onmouseout="tooltip.hide()">
								<?php echo $picture;?>
							</a>
						</div>
						<h4 class="p_title"><a href="<?php echo $linkDetial;?>" title="<?php echo $name;?>"><?php echo $name;?></a></h4>
						<div class="p_price">
							<?php echo $price_old;?>
							<?php echo $price;?>
						</div>
					</div>
				</li>
			<?php
			} 
			?>
			</ul>
		</div>
	</div>
</div>