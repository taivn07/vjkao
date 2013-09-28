<?php
class Zendvn_View_Helper_CmsButton extends Zend_View_Helper_Abstract{
	
	public function cmsButton($name, $link = '#', $imgLink, $type = 'link', $options = null){
		
		if($type == 'link')
		{
			$aTag = 'href="' . $link . '"';
		}else{
			if($options['type'] == 2){
				$aTag = 'href="#" onclick="OnSubmitForm2(\'' . $link . '\')"';
			}else{
				$aTag = 'href="#" onclick="OnSubmitForm(\'' . $link . '\')"';
			}
		}
		$xhtml = '<div class="toolbar-button" >
             		<a ' . $aTag . '>
        				<img src="' . $imgLink . '"><br>
                   		' . $name . '
                   	</a>
              	  </div>';
		return $xhtml;
	}
}