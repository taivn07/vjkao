<?php
class Zendvn_View_Helper_CmsEditor extends Zend_View_Helper_Abstract{
	
	public function cmsEditor($name,$value,$options = null,$width = 'auto',$height = 200,$autoHeight = true,$maxHeight = 500){
		// Include the CKEditor class.
		require_once (SCRIPTS_PATH . "/ckeditor/ckeditor_php5.php");
		
		// Khoi tao CKeditor.
		$CKEditor = new CKEditor();
		
		// Duong dan den thu muc CKeditor tinh tu file chay.
		$CKEditor->basePath = SCIPTS_URL . '/ckeditor/';
		
		// Thiet lap chieu rong va chieu cao.
		$CKEditor->config['width'] = $width;
		$CKEditor->config['height'] = $height;
		// Change default textarea attributes.
		//$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
		// Cau hinh ngon ngu
		if(!isset($options['language'])){
			$CKEditor->config['language'] = 'vi';
		}else{
			$CKEditor->config['language'] = $options['language'];
		}
		//Cau hinh mau nen
		$CKEditor->config['uiColor'] = '#C8E0E4';
		//Khi nhan Enter
		$CKEditor->config['enterMode'] = 'CKEDITOR.ENTER_DIV';
		$CKEditor->config['shiftEnterMode'] = 'CKEDITOR.ENTER_BR';
		
		//Cau hinh tu dong dan chieu cao CKediter den khi dat muc maxHeight se co thanh cuon
		$plugins = 'tableresize,gmap,jwplayer';
		if($autoHeight == true){
			$CKEditor->config['extraPlugins'] = $plugins . ',autogrow,syntaxhighlight';
			$CKEditor->config['autoGrow_maxHeight'] = $maxHeight;
		}
		$CKEditor->config['removePlugins'] = 'resize';
		
		//Xoa dinh dang css khi cop noi dung tu word
		$CKEditor->config['pasteFromWordRemoveFontStyles'] = true;
		//Dinh dang font chu Unicode
		$CKEditor->config['entities'] = false;
		$CKEditor->config['tabSpaces'] = 10;
		
		//Goi Toolbar nao muon su dung
		if(!isset($options['toolbar'])){
			$CKEditor->config['toolbar'] = 'Full';
		}else{
			$CKEditor->config['toolbar'] = $options['toolbar'];
		}

		//Cau hinh upload bang KCFinder
		$CKEditor->config['filebrowserBrowseUrl'] 		= SCIPTS_URL . '/media_files/browse.php?type=files';
		$CKEditor->config['filebrowserImageBrowseUrl'] 	= SCIPTS_URL . '/media_files/browse.php?type=images';
		$CKEditor->config['filebrowserFlashBrowseUrl']	= SCIPTS_URL . '/media_files/browse.php?type=flash';
		$CKEditor->config['filebrowserUploadUrl'] 		= SCIPTS_URL . '/media_files/upload.php?type=files';
		$CKEditor->config['filebrowserImageUploadUrl'] 	= SCIPTS_URL . '/media_files/upload.php?type=images';
		$CKEditor->config['filebrowserFlashUploadUrl'] 	= SCIPTS_URL . '/media_files/upload.php?type=flash';
		
		// Gia tri Value cua CKeditor
		$initialValue = $value;
		// Khoi tao 1 textarea va chen gia tri vao cho no.
		return $CKEditor->editor($name, $initialValue);
	}
}