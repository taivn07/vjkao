<div class="block_ProductPrice2">
	<span class="label"><?php echo $view->language['productTimTheoGia'];?>: </span> 
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
	<select onchange="window.open(this.options[this.selectedIndex].value,'_self');this.options[0].selected=true" id="select">
        <option value="<?php echo $manu . '&min=0&max=1000000000';?>">Tất cả</option>
        <option value="<?php echo $manu . '&min=0&max=500000';?>" <?php if($arrParam['min'] == 0 && $arrParam['max'] == 500000) echo 'selected';?>>Dưới 500.000</option>
		<option value="<?php echo $manu . '&min=500000&max=1000000';?>" <?php if($arrParam['min'] == 500000 && $arrParam['max'] == 1000000) echo 'selected';?>>500.000 - 1.000.000</option>
		<option value="<?php echo $manu . '&min=1000000&max=1500000';?>" <?php if($arrParam['min'] == 1000000 && $arrParam['max'] == 1500000) echo 'selected';?>>1.000.000 - 1.500.000</option>
		<option value="<?php echo $manu . '&min=1500000&max=2000000';?>" <?php if($arrParam['min'] == 1500000 && $arrParam['max'] == 2000000) echo 'selected';?>>1.500.000 - 2.000.000</option>
		<option value="<?php echo $manu . '&min=2000000&max=2500000';?>" <?php if($arrParam['min'] == 2000000 && $arrParam['max'] == 2500000) echo 'selected';?>>2.000.000 - 2.500.000</option>
		<option value="<?php echo $manu . '&min=2500000&max=3000000';?>" <?php if($arrParam['min'] == 2500000 && $arrParam['max'] == 3000000) echo 'selected';?>>2.500.000 - 3.000.000</option>
		<option value="<?php echo $manu . '&min=3000000&max=5000000';?>" <?php if($arrParam['min'] == 3000000 && $arrParam['max'] == 5000000) echo 'selected';?>>3.000.000 - 5.000.000</option>
		<option value="<?php echo $manu . '&min=5000000&max=1000000000';?>" <?php if($arrParam['min'] == 5000000 && $arrParam['max'] == 1000000000) echo 'selected';?>>Trên 5.000.000</option>
	</select>
</div>