<div class="block_ProductManu">
	<div class="block_title">
		<span class="icon"></span>
		<h4>Lọc sản phẩm</h4>
	</div>
	<div class="block_content">
		<div class="item">
			<span class="label">Thương hiệu: </span>
			<?php
			
			if(!empty($arrParam['cid'])){
				$tblCategory = new Shopping_Model_Category();
				$category = $tblCategory->getItem(array('id' => $arrParam['cid']),array('task' => 'public-info'));
				$urlOptions = array('module'=>'shopping','controller'=>'index','action'=>'category',
						'cid'=>$category['id'],
						'alias'=>$category['alias'],
				);
				$linkCategory = $view->url($urlOptions,'shop-category');
			}
			
			$min = '&min=0';
			if(!empty($arrParam['min'])){
				$min = '&min=' . $arrParam['min'];
			}
			$max = '&max=1000000000';
			if(!empty($arrParam['max'])){
				$max = '&max=' . $arrParam['max'];
			}
			if(empty($arrParam['manu'])){
				echo '<a href="'.$linkCategory . '?manu=0' . $min . $max .'" class="action goc5">Tất cả</a>';
			}else{
				echo '<a href="'.$linkCategory . '?manu=0' . $min . $max .'">Tất cả</a>';
			}
			foreach ($listManu AS $key => $val){
				$class='';
				if($arrParam['manu'] == $val['id'])
					$class='class="action goc5"';
			?>
				<a href="<?php echo $linkCategory . '?manu=' . $val['id'] . $min . $max;?>" <?php echo $class;?>><?php echo $val['name'];?></a>
			<?php
			} 
			?>
			<div class="clr"></div>
		</div>
		<div class="item">
			<?php echo $view->blkProductPrice('view2');?>
		</div>
		<div class="clr"></div>
	</div>
	<div class="block_bottom"></div>
</div>