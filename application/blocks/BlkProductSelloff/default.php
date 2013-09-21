<?php
	if(count($row)>0){ 
?>
<div class="block_productSelloff">
	<div class="block_title">
		<span class="icon"></span> Sản phẩm giảm giá
	</div>
	<div class="block_content">
		<div class="products">
			<ul class="clearfix">
		<?php
		foreach ($row as $key => $val){
			$name = $val['name'];
			$picture = '<img src="' . $val['picture'] . '" alt="'.$val['name'].'"/>';
			$units_money = $val['units_money'];
			$price = '';
			if($val['price'] != 0){
				$price = Zend_Locale_Format::toNumber($val['price'],array('precision' => 0)) . ' ' . $units_money;
			}
			$selloff = ($val['price']) - ($val['price'] * ($val['selloff']/100));
			$selloff = Zend_Locale_Format::toNumber($selloff,array('precision' => 0)) . ' ' . $units_money;
			$urlOptions_item = array('module'=>'shopping','controller'=>'shop','action'=>'detail',
				'shop_id'=>$val['shop_id'],
				'alias'=>$val['alias'],
				'id'=>$val['id'],
			);
			$linkDetial = $view->url($urlOptions_item,'shop-detail');
		?>
			<li>
				<div class="product_image">
					<table cellspacing="0" cellpadding="0"><tbody><tr><td><a href="<?php echo $linkDetial;?>" title="<?php echo $name;?>"><?php echo $picture;?></a></td></tr></tbody></table>
				</div>
				<div class="product_title">
					<a href="<?php echo $linkDetial;?>" title="<?php echo $name;?>"><?php echo $name;?></a>
				</div>
				<div class="product_price">
					<p class="price"><?php echo $price;?></p>
					<p class="selloff"><?php echo $selloff;?></p>
				</div>
			</li>
		<?php
		} 
		?>
			</ul>
		</div>
	</div>
	<div class="block_bottom"></div>
</div>
<?php
	} 
?>