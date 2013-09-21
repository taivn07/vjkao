<?php

	if(!isset($this->arrParam['type_menu']) || $this->arrParam['type_menu'] == 'main_menu'){
		$linkUrlMainMenu 	= '#';
		$linkMainMenu 		= '<a href="' . $linkUrlMainMenu . '" class="active">Main menu</a>';
	}else{
		$linkUrlMainMenu 	=  $this->baseUrl('/default/admin-menu/index/type_menu/main_menu');
		$linkMainMenu 		= '<a href="' . $linkUrlMainMenu . '">Main menu</a>';
	}
	
	if($this->arrParam['type_menu'] == 'top_menu'){
		$linkUrlTopMenu 	= '#';
		$linkTopMenu 	= '<a href="' . $linkUrlTopMenu . '" class="active">Top menu</a>';
	}else{
		$linkUrlTopMenu 	= $this->baseUrl('/default/admin-menu/index/type_menu/top_menu');
		$linkTopMenu 	= '<a href="' . $linkUrlTopMenu . '">Top menu</a>';
	}
	
	if($this->arrParam['type_menu'] == 'footer_menu'){
		$linkUrlFooterMenu 	= '#';
		$linkFooterMenu 	= '<a href="' . $linkUrlFooterMenu . '" class="active">Footer menu</a>';
	}else{
		$linkUrlFooterMenu 	= $this->baseUrl('/default/admin-menu/index/type_menu/footer_menu');
		$linkFooterMenu 	= '<a href="' . $linkUrlFooterMenu . '">Footer menu</a>';
	}
	
	if($this->arrParam['type_menu'] == 'left_menu'){
		$linkUrlLeftMenu 	= '#';
		$linkLeftMenu 		= '<a href="' . $linkUrlLeftMenu . '" class="active">Left menu</a>';
	}else{
		$linkUrlLeftMenu 	= $this->baseUrl('/default/admin-menu/index/type_menu/left_menu');
		$linkLeftMenu 		= '<a href="' . $linkUrlLeftMenu . '">Left menu</a>';
	}
	
	if($this->arrParam['type_menu'] == 'right_menu'){
		$linkUrlRightMenu 	= '#';
		$linkRightMenu 		= '<a href="' . $linkUrlRightMenu . '" class="active">Right menu</a>';
	}else{
		$linkUrlRightMenu 	= $this->baseUrl('/default/admin-menu/index/type_menu/right_menu');
		$linkRightMenu 		= '<a href="' . $linkUrlRightMenu . '">Right menu</a>';
	}
	
	if($this->arrParam['type_menu'] == 'intro_menu'){
		$linkUrlIntroMenu 	= '#';
		$linkIntroMenu 		= '<a href="' . $linkUrlIntroMenu . '" class="active">Intro menu</a>';
	}else{
		$linkUrlIntroMenu 	= $this->baseUrl('/default/admin-menu/index/type_menu/intro_menu');
		$linkIntroMenu 		= '<a href="' . $linkUrlIntroMenu . '">Intro menu</a>';
	}
	
?>
<div class="block_subMenu goc10">
	<ul>
		<li><?php echo $linkMainMenu;?></li>
		<li><?php echo $linkTopMenu;?></li>
		<li><?php echo $linkFooterMenu;?></li>
		<li><?php echo $linkLeftMenu;?></li>
		<li><?php echo $linkRightMenu;?></li>
		<li><?php echo $linkIntroMenu;?></li>
	</ul>
	<div class="clr"></div>
</div>