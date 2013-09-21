<?php
class Block_BlkArticleCategory extends Zend_View_Helper_Abstract{
	
	public function blkArticleCategory($template = 'default'){
		$view = $this->view;
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$select = $db->select()
				->from('article_category AS ac',array('id','name','alias','parents'))
				->where('status = ?',1,INTERGER)
				->order('order ASC')
				->order('id ASC');
		
		$language = new Zend_Session_Namespace('language');
		$select->where("ac.lang_code = '" . $language->lang . "'");
		
		$result = $db->fetchAll($select);
		$strMenu = $this->createMenu($result,0,$view);
		require_once BLOCK_PATH . '/BlkArticleCategory/'.$template.'.php';
	}
	
	public function createMenu($sourceArr,$parents = 0, $viewObj){
		$this->recursiveMenu($sourceArr,$parents = 0,&$newMenu,$viewObj);
		return str_replace('<ul></ul>', '', $newMenu);
	}
	
	public function recursiveMenu($sourceArr,$parents = 0,&$newMenu, $viewObj){
		//$filter = new Zendvn_Filter_RewriteUrl();
		if(count($sourceArr)>0){
			$newMenu .= '<ul>';
			$i=0;
			foreach ($sourceArr as $key => $value){
				$i++;
				if($value['parents'] == $parents){
					$class = '';
					if($i == 1){
						$class = 'class="start"';
					}
					
					$urlOptions = array('module'=>'article','controller'=>'index','action'=>'category',
							'cid'=>$value['id'],
							'alias'=>$value['alias'],
					);
					$link = $viewObj->url($urlOptions,'article-category');
					//$link = $viewObj->baseUrl('/shopping/index/category/cid/'.$value['id'].'/name/'.$filter->filter($value['name']));
					$newMenu .= '<li><a href="'.$link.'" '.$class.'>' . $value['name'] . '</a>';
					
					$newParents = $value['id'];
					unset($sourceArr[$key]);
					$this->recursiveMenu($sourceArr,$newParents,$newMenu, $viewObj);
					$newMenu .= '</li>';
				}
			}
			$newMenu .= '</ul>';
		}
	}
}