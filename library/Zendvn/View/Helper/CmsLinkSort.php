<?php
class Zendvn_View_Helper_CmsLinkSort extends Zend_View_Helper_Abstract{
	
	public function cmsLinkSort($label,$column,$ssFilter,$imgLink,$action_link,$default_order = 'DESC'){
		
		if($ssFilter['col'] != $column){
			$linkOrder = $action_link . '/col/' . $column . '/by/' . $default_order;
			$iconSort = '';
			$title = 'Nhấn vào để sắp xếp';
		}else{
			if($ssFilter['order'] == 'DESC'){
				$sortOrder = 'ASC';
				$iconSort = '<img src="' . $imgLink . '/sort_desc.png' . '">';
				$title = 'Sắp xếp theo chiều A-Z';
			}else{
				$sortOrder = 'DESC';
				$iconSort = '<img src="' . $imgLink . '/sort_asc.png' . '">';
				$title = 'Sắp xếp theo chiều Z-A';
			}
			$linkOrder = $action_link . '/col/' . $column . '/by/' . $sortOrder;
		}
		
		$xhtml = '<a href="' . $linkOrder . '" title="' . $title . '">
					' . $label . ' ' . $iconSort . '</a>';
		
		return $xhtml;
	}
}