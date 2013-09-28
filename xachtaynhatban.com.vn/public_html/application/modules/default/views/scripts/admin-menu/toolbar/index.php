<?php

	$type_menu = '/type_menu/main_menu';
	if(isset($this->arrParam['type_menu'])){
		$type_menu = '/type_menu/' . $this->arrParam['type_menu'];
	}
	
	// 1. Active Items - submit
	$linkActiveItems = $this->baseUrl ( $this->currentController . '/status/type/1' . $type_menu);
	$btnActiveItems = $this->cmsButton ( 'Bật', $linkActiveItems, $this->imgUrl . '/toolbar/icon-32-publish.png', 'submit', array('type' => 2));
	
	// 2. Inactive Items - submit
	$linkInactiveItems = $this->baseUrl ( $this->currentController . '/status/type/0' . $type_menu );
	$btnInactiveItems = $this->cmsButton ( 'Tắt', $linkInactiveItems, $this->imgUrl . '/toolbar/icon-32-unpublish.png', 'submit', array('type' => 2));
	
	// 3. Thêm mới - link
	$linkAddNew = $this->baseUrl ( $this->currentController . '/add' . $type_menu );
	$btnAddNew = $this->cmsButton ( 'Thêm mới', $linkAddNew, $this->imgUrl . '/toolbar/icon-32-new.png', 'link' );
	
	// 4. Sắp xếp - submit
	$linkSortItem = $this->baseUrl ( $this->currentController . '/sort' . $type_menu);
	$btnSortItem = $this->cmsButton ( 'Sắp xếp', $linkSortItem, $this->imgUrl . '/toolbar/icon-32-sort.png', 'submit', array('type' => 2));
	
	// 5. Xóa nhiều - submit
	$linkDeleteItems = $this->baseUrl ( $this->currentController . '/delete/type/multi-delete' . $type_menu);
	$btnDeleteItems = $this->cmsButton ( 'Xóa nhiều', $linkDeleteItems, $this->imgUrl . '/toolbar/icon-32-delete.png', 'submit', array('type' => 2));
	
	// 6. Luu = submit
	if($this->arrParam['action'] == 'add'){
		$linkSaveItem = $this->baseUrl ( $this->currentController . '/add' . $type_menu);
	}else{
		$linkSaveItem = $this->baseUrl ( $this->currentController . '/edit/id/' . $this->arrParam['id'] . $type_menu );
	}
	$btnSaveItem = $this->cmsButton ( 'Lưu', $linkSaveItem, $this->imgUrl . '/toolbar/icon-32-save.png', 'submit' );
	
	// 7. Cancel - Link
	$linkCancel = $this->baseUrl ( $this->currentController . '/index' . $type_menu );
	$btnCancel = $this->cmsButton ( 'Hủy', $linkCancel, $this->imgUrl . '/toolbar/icon-32-cancel.png', 'link' );
	
	// 8. Edit Item - Link
	$linkEditItem = $this->baseUrl ( $this->currentController . '/edit/id/' . $this->Item['id'] . $type_menu );
	$btnEditItem = $this->cmsButton ( 'Sửa', $linkEditItem, $this->imgUrl . '/toolbar/icon-32-edit.png', 'link' );
	
	// 9. Back - Link
	$linkBack = $this->baseUrl ( $this->currentController . '/index' . $type_menu );
	$btnBack = $this->cmsButton ( 'Back', $linkBack, $this->imgUrl . '/toolbar/icon-32-back.png', 'link' );
	
	// 10.  Accept - Submit
	$linkAccept = $this->baseUrl ( $this->currentController . '/delete/id/' . $this->arrParam['id'] . $type_menu );
	if(isset($this->arrParam['type']) == 'multi-delete')
		$linkAccept = $this->baseUrl ( $this->currentController . '/delete/type/multi-delete/task/ok' . $type_menu);
	$btnAccept = $this->cmsButton ( 'Đồng ý', $linkAccept, $this->imgUrl . '/toolbar/icon-32-apply.png', 'submit' );
	
	$divider = '<div class="divider"> </div>';
	
	switch ($this->arrParam['action']){
		
		case 'index': $strBtn = $btnAddNew . $divider . $btnActiveItems . ' ' . $btnInactiveItems . ' ' . $btnSortItem . $divider . $btnDeleteItems;
					 			break;
					 		
		case 'edit': $strBtn = $btnSaveItem . ' ' . $btnCancel;
				 				break;
			
		case 'add': $strBtn = $btnSaveItem . ' ' . $btnCancel;
				 				break;
				 				
		case 'delete': $strBtn = $btnAccept . ' ' . $btnCancel;
								break;
								
		case 'info': $strBtn = $btnEditItem . ' ' . $btnBack;
								break;
		
		default: $strBtn = '';
	}
	
	//Icon toolbar
	$IconToolbar = 'icon-48-menu';
?>
<div class="block_toolbar goc10">
	<div class="toolbar">
		<?php echo $strBtn;?>
		<div class="clr"></div>
	</div>
	<div class="header <?php echo $IconToolbar;?>"><?php echo $this->Title;?></div>
	<div class="clr"></div>
</div>