<?php
class Block_BlkArticleCategoryLeft extends Zend_View_Helper_Abstract{
	
	public function blkArticleCategoryLeft($template = 'default'){
		$view = $this->view;
		$db = Zend_Registry::get('connectDb');
		//$db = Zend_Db::factory($adapter, $config);
		$select = $db->select()
				->from('article_category AS ac',array('id','name','alias','parents'))
				->where('status = ?',1,INTERGER)
				->where('block_left = ?',1,INTERGER)
				->order('order ASC')
				->order('id ASC');
		
		$language = new Zend_Session_Namespace('language');
		$select->where("ac.lang_code = '" . $language->lang . "'");
		
		$result = $db->fetchAll($select);
		$xhtml = '';
		foreach ($result AS $key => $val){
			$select = $db->select()
			->from('article_category AS ac',array('id','name','alias','parents'))
			->where('status = ?',1,INTERGER)
			->order('order ASC')
			->order('id ASC');
			$language = new Zend_Session_Namespace('language');
			$select->where("ac.lang_code = '" . $language->lang . "'");
			$result = $db->fetchAll($select);
			$strMenu = $this->createMenu($result,$val['id'],$view);
			
			$urlOptions = array('module'=>'article','controller'=>'index','action'=>'category',
				'cid'=>$val['id'],
				'alias'=>$val['alias'],
			);
			$link = $view->url($urlOptions,'article-category');
			$xhtml .= '<div class="block_leftMenu"><div class="block_title">
			<span class="icon"></span>
			<h4><a href="'.$link.'" title="'.$val['name'].'">'.$val['name'].'</a></h4>
			</div>
			<div class="block_content">
			<div class="leftMenu" id="leftMenu">';
			$xhtml .= $strMenu;
			$xhtml .='</div>
				</div>
			</div>';
		}
		if($xhtml != ''){
			require_once BLOCK_PATH . '/BlkArticleCategoryLeft/'.$template.'.php';
		}
	}
	
	public function createMenu($sourceArr,$parents = 0, $viewObj){
		$this->recursiveMenu($sourceArr,$parents,&$newMenu,$viewObj);
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
					
					$urlOptions = array('module'=>'article','controller'=>'index','action'=>'category',
							'cid'=>$value['id'],
							'alias'=>$value['alias'],
					);
					$link = $viewObj->url($urlOptions,'article-category');
					//$link = $viewObj->baseUrl('/shopping/index/category/cid/'.$value['id'].'/name/'.$filter->filter($value['name']));
					$newMenu .= '<li><a href="'.$link.'" title="' . $value['name'] . '">' . $value['name'] . '</a>';
					
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