<?php
class Zendvn_View_Helper_CmsUserFiles extends Zend_View_Helper_Abstract{
	
	public function cmsUserFiles($name, $value = null, $nameBtn, $typeMedia = 'images', $options = array(), $formName = 'appForm'){
		
		$strOptions = '';
		if(count($options)>0){
			foreach ($options as $keyOptions => $valueOptions){
				$strOptions .= $keyOptions . '="' . $valueOptions . '" ';
			}
		}
		
		$linkMedia = SCIPTS_URL . '/media_files/browse.php?type=' . $typeMedia . '&lng=vi';
		
		$xhtml = '<script type="text/javascript">

			function openKCFinder'.$name.'(field) {
				$(\'#formLoading\').removeClass().addClass(\'loading\').fadeIn();
				window.KCFinder = {
					callBack: function(url) {
						field.value = url;
						window.KCFinder = null;
						document.getElementById(\'image_'.$name.'\').src = url;
					}
				};
				window.open(\'' . $linkMedia . '\', \'kcfinder_textbox\',\'status=0, toolbar=0, location=0, menubar=0, directories=0,resizable=1, scrollbars=0, width=950, height=450\' );
				$(\'#formLoading\').removeClass().fadeOut();
			}</script>';
		
		$xhtml .= '<input value="'.$value.'" type="text" name="'.$name.'" id="'.$name.'"' . $strOptions . ' >';
		$xhtml .= '<a href="javascript:void(0);" onclick="openKCFinder'.$name.'('.$formName.'.'.$name.')" class="btnMedia">'. $nameBtn .'</a>';
		
		return $xhtml;
	}
}