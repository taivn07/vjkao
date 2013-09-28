<div class="block_ProductPriceCol">
	<div class="block_title">
		<span class="icon"></span>
		<h4><?php echo $view->language['productTimTheoGia'];?></h4>
	</div>
	<div class="block_content">
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
		
		$manu = $linkCategory . '?manu=0';
		if(!empty($arrParam['manu'])){
			$manu = '?manu=' . $arrParam['manu'];
		}
	?>
		<ul>
			<li><a href="<?php echo $manu . '&min=0&max=1000000000';?>" <?php if(($arrParam['min'] == 0 && $arrParam['max'] == 1000000000)) echo 'class="selected"';?>><?php echo $view->language['tatCa'];?></a></li>
			<li><a href="<?php echo $manu . '&min=0&max=500000';?>" <?php if($arrParam['min'] == 0 && $arrParam['max'] == 500000) echo 'class="selected"';?>>Dưới 500.000</a></li>
			<li><a href="<?php echo $manu . '&min=500000&max=1000000';?>" <?php if($arrParam['min'] == 500000 && $arrParam['max'] == 1000000) echo 'class="selected"';?>>500.000 - 1.000.000</a></li>
			<li><a href="<?php echo $manu . '&min=1000000&max=1500000';?>" <?php if($arrParam['min'] == 1000000 && $arrParam['max'] == 1500000) echo 'class="selected"';?>>1.000.000 - 1.500.000</a></li>
			<li><a href="<?php echo $manu . '&min=1500000&max=2000000';?>" <?php if($arrParam['min'] == 1500000 && $arrParam['max'] == 2000000) echo 'class="selected"';?>>1.500.000 - 2.000.000</a></li>
			<li><a href="<?php echo $manu . '&min=2000000&max=2500000';?>" <?php if($arrParam['min'] == 2000000 && $arrParam['max'] == 2500000) echo 'class="selected"';?>>2.000.000 - 2.500.000</a></li>
			<li><a href="<?php echo $manu . '&min=2500000&max=3000000';?>" <?php if($arrParam['min'] == 2500000 && $arrParam['max'] == 3000000) echo 'class="selected"';?>>2.500.000 - 3.000.000</a></li>
			<li><a href="<?php echo $manu . '&min=3000000&max=5000000';?>" <?php if($arrParam['min'] == 3000000 && $arrParam['max'] == 5000000) echo 'class="selected"';?>>3.000.000 - 5.000.000</a></li>
			<li><a href="<?php echo $manu . '&min=5000000&max=1000000000';?>" <?php if($arrParam['min'] == 5000000 && $arrParam['max'] == 1000000000) echo 'class="selected"';?>>Trên 5.000.000</a></li>
		</ul>
	</div>
	<div class="block_bottom"></div>
</div>