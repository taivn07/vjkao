<?php

	// 6. Luu = submit
	$linkSaveItem = $this->baseUrl ( $this->currentController . '/index/' . $this->arrParam['id']);
	$btnSaveItem = $this->cmsButton ( 'Lưu', $linkSaveItem, $this->imgUrl . '/toolbar/icon-32-save.png', 'submit' );
	
	// 7. Cancel - Link
	$linkCancel = $this->baseUrl ('/default/admin/index/');
	$btnCancel = $this->cmsButton ( 'Hủy', $linkCancel, $this->imgUrl . '/toolbar/icon-32-cancel.png', 'link' );

	switch ($this->arrParam['action']){
		
		case 'index': $strBtn = $btnSaveItem . ' ' . $btnCancel;
					 			break;

		default: $strBtn = '';
	}
	
	//Icon toolbar
	$IconToolbar = 'icon-48-config';
?>
<div class="block_toolbar goc10">
	<div class="toolbar">
		<?php echo $strBtn;?>
		<div class="clr"></div>
	</div>
	<div class="header <?php echo $IconToolbar;?>"><?php echo $this->Title;?></div>
	<div class="clr"></div>
</div>