<div class="block_ProductManuCol">
	<div class="block_title">
		<span class="icon"></span>
		<h4><?php echo $view->language['productTimTheoHangSanXuat'];?></h4>
	</div>
	<div class="block_content">
		<ul>
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
		
		if(count($listManu) > 0){
			if($arrParam['manu'] == 0){
				$class='class="selected"';
			}else{
				$class='';
			}
			echo '<li><a href="'.$linkCategory . '?manu=0' . $min . $max.'" title="'.$view->language['tatCa'].'" '.$class.'>'.$view->language['tatCa'].'</a></li>';
		foreach ($listManu AS $key => $val){
			if($arrParam['manu'] == $val['id']){
				$class = 'class="selected"';
			}else{
				$class = '';
			}
		?>
			<li><a href="<?php echo $linkCategory . '?manu=' . $val['id'] . $min . $max;?>" title="<?php echo $val['name'];?>" <?php echo $class;?>><?php echo $val['name'];?></a></li>
		<?php
		} 
		}
		?>
		</ul>
	</div>
	<div class="block_bottom"></div>
</div>