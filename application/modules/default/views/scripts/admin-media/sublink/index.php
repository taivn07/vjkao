<?php
	if(!isset($this->arrParam['type']) || $this->arrParam['type'] == 'images'){
		$linkUrlImages 	=  '#';
		$linkImages 	= '<a href="' . $linkUrlImages . '" class="active">Hình ảnh</a>';
	}else{
		$linkUrlImages 	=  $this->baseUrl('/default/admin-media/index/type/images');
		$linkImages 	= '<a href="' . $linkUrlImages . '">Hình ảnh</a>';
	}
	
	if($this->arrParam['type'] == 'flash'){
		$linkUrlFlash 	=  '#';
		$linkFlash	 	= '<a href="' . $linkUrlFlash . '" class="active">Flash</a>';
	}else{
		$linkUrlFlash 	=  $this->baseUrl('/default/admin-media/index/type/flash');
		$linkFlash	 	= '<a href="' . $linkUrlFlash . '">Flash</a>';
	}
	
	if($this->arrParam['type'] == 'files'){
		$linkUrlFiles 	=  '#';
		$linkFiles	 	= '<a href="' . $linkUrlFiles . '" class="active">Files</a>';
	}else{
		$linkUrlFiles 	=  $this->baseUrl('/default/admin-media/index/type/files');
		$linkFiles	 	= '<a href="' . $linkUrlFiles . '">Files</a>';
	}
?>
<div class="block_subMenu goc10">
	<ul>
		<li><?php echo $linkImages;?></li>
		<li><?php echo $linkFlash;?></li>
		<li><?php echo $linkFiles;?></li>
	</ul>
	<div class="clr"></div>
</div>