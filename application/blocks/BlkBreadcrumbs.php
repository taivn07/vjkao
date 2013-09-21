<?php
class Block_BlkBreadcrumbs extends Zend_View_Helper_Abstract{

	public function blkBreadcrumbs($template = 'default'){
		$view  = $this->view;
		$arrParam = $view->arrParam;
		//$language = new Zend_Session_Namespace('language');
		$flagShow = true;
		if($flagShow == true){
			$db = Zend_Registry::get('connectDb');
			//$db = Zend_Db::factory($adapter, $config);
			$linkBreadcrumbs = '<li><a href="'.$view->baseUrl('').'" title="'.$view->language['trangChu'].'">'.$view->language['trangChu'].'</a></li><li class="brc_arrow"></li>';
			if($arrParam['module'] == 'article'){
				if(!empty($arrParam['cid'])){
					$select = $db->select()
					->from('article_category AS ac',array('id','name','alias','parents'))
					->where('ac.id = ?', $arrParam['cid'], INTERGER);
					$row = $db->fetchRow($select);
					if($row['parents'] > 0){
						$select2 = $db->select()
						->from('article_category AS ac',array('id','name','alias','parents'))
						->where('ac.id = ?', $row['parents'], INTERGER);
						$row2 = $db->fetchRow($select2);
						$urlOptions2 = array('module'=>'article','controller'=>'index','action'=>'category',
								'cid'=>$row2['id'],
								'alias'=>$row2['alias'],
						);
						$link2 = $view->url($urlOptions2,'article-category');
						$linkBreadcrumbs .= '<li><a href="'.$link2.'" title="'.$row2['name'].'">'.$row2['name'].'</a></li><li class="brc_arrow"></li>';
					}
					$urlOptions = array('module'=>'article','controller'=>'index','action'=>'category',
							'cid'=>$row['id'],
							'alias'=>$row['alias'],
					);
					$link = $view->url($urlOptions,'article-category');
					$linkBreadcrumbs .= '<li><a href="'.$link.'" title="'.$row['name'].'">'.$row['name'].'</a></li>';
				}
				if(!empty($arrParam['id'])){
					$select = $db->select()
					->from('articles AS a',array('id','name'))
					->where('a.id = ?', $arrParam['id'], INTERGER);
					$row = $db->fetchRow($select);
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$row['name'].'</li>';
				}
				
				if($arrParam['controller'] == 'public' && $arrParam['action'] == 'tags'){
					$linkBreadcrumbs .= '<li>'.$arrParam['key'].'</li>';
				}
			}
			
			if($arrParam['module'] == 'shopping'){
				$linkBreadcrumbs .= '<li><a href="'.$view->baseUrl('products.html').'" title="'.$view->language['productSanPham'].'">'.$view->language['productSanPham'].'</a></li>';
				if(!empty($arrParam['cid'])){
					$select = $db->select()
					->from('product_category AS pc',array('id','name','alias','parents'))
					->where('pc.id = ?', $arrParam['cid'], INTERGER);
					$row = $db->fetchRow($select);
					if($row['parents'] > 0){
						$select2 = $db->select()
						->from('product_category AS pc',array('id','name','alias','parents'))
						->where('pc.id = ?', $row['parents'], INTERGER);
						$row2 = $db->fetchRow($select2);
						$urlOptions2 = array('module'=>'shopping','controller'=>'index','action'=>'category',
								'cid'=>$row2['id'],
								'alias'=>$row2['alias'],
						);
						$link2 = $view->url($urlOptions2,'shop-category');
						$linkBreadcrumbs .= '<li class="brc_arrow"></li><li><a href="'.$link2.'" title="'.$row2['name'].'">'.$row2['name'].'</a></li>';
					}
					$urlOptions = array('module'=>'shopping','controller'=>'index','action'=>'category',
							'cid'=>$row['id'],
							'alias'=>$row['alias'],
					);
					$link = $view->url($urlOptions,'shop-category');
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li><a href="'.$link.'" title="'.$row['name'].'">'.$row['name'].'</a></li>';
				}
				if(!empty($arrParam['id'])){
					$select = $db->select()
					->from('products AS p',array('id','name'))
					->where('p.id = ?', $arrParam['id'], INTERGER);
					$row = $db->fetchRow($select);
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$row['name'].'</li>';
				}
				
				if($arrParam['action'] == 'view-cart'){
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$view->language['productGioHang'].'</li>';
				}
				
				if($arrParam['action'] == 'search'){
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$view->language['ketQuaTimKiem'].'</li>';
				}
				
				if($arrParam['controller'] == 'block' && $arrParam['action'] == 'khuyen-mai'){
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$view->language['productSanPhamKhuyenMai'].'</li>';
				}
				
				if($arrParam['controller'] == 'block' && $arrParam['action'] == 'moi'){
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$view->language['productSanPhamMoi'].'</li>';
				}
				
				if($arrParam['controller'] == 'block' && $arrParam['action'] == 'hot'){
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$view->language['productSanPhamHot'].'</li>';
				}
				
				if($arrParam['controller'] == 'block' && $arrParam['action'] == 'ban-chay'){
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$view->language['productSanPhamBanChay'].'</li>';
				}
				
				if($arrParam['controller'] == 'block' && $arrParam['action'] == 'noi-bat'){
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$view->language['productSanPhamNoiBat'].'</li>';
				}
				
				if($arrParam['controller'] == 'public' && $arrParam['action'] == 'guide'){
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$view->language['productHuongDanMuaHang'].'</li>';
				}
			}
			
			if($arrParam['module'] == 'faqs'){
				$linkBreadcrumbs .= '<li><a href="'.$view->baseUrl('faqs.html').'" title="'.$view->language['faqsHoiDap'].'">'.$view->language['faqsHoiDap'].'</a></li>';
				if(!empty($arrParam['cid'])){
					$select = $db->select()
					->from('faqs_category AS fc',array('id','name','alias','parents'))
					->where('fc.id = ?', $arrParam['cid'], INTERGER);
					$row = $db->fetchRow($select);
					if($row['parents'] > 0){
						$select2 = $db->select()
						->from('faqs_category AS fc',array('id','name','alias','parents'))
						->where('fc.id = ?', $row['parents'], INTERGER);
						$row2 = $db->fetchRow($select2);
						$urlOptions2 = array('module'=>'faqs','controller'=>'index','action'=>'category',
								'cid'=>$row2['id'],
								'alias'=>$row2['alias'],
						);
						$link2 = $view->url($urlOptions2,'faqs-category');
						$linkBreadcrumbs .= '<li class="brc_arrow"></li><li><a href="'.$link2.'" title="'.$row2['name'].'">'.$row2['name'].'</a></li>';
					}
					$urlOptions = array('module'=>'faqs','controller'=>'index','action'=>'category',
							'cid'=>$row['id'],
							'alias'=>$row['alias'],
					);
					$link = $view->url($urlOptions,'faqs-category');
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li><a href="'.$link.'" title="'.$row['name'].'">'.$row['name'].'</a></li>';
				}
				if(!empty($arrParam['id'])){
					$select = $db->select()
					->from('faqs AS f',array('id','title'))
					->where('f.id = ?', $arrParam['id'], INTERGER);
					$row = $db->fetchRow($select);
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$row['title'].'</li>';
				}
			}
			
			if($arrParam['module'] == 'gallery'){
				$linkBreadcrumbs .= '<li><a href="'.$view->baseUrl('gallery.html').'" title="'.$view->language['galleryThuVienAnh'].'">'.$view->language['galleryThuVienAnh'].'</a></li>';
				if(!empty($arrParam['cid'])){
					$select = $db->select()
					->from('gallery_category AS gc',array('id','name','alias','parents'))
					->where('gc.id = ?', $arrParam['cid'], INTERGER);
					$row = $db->fetchRow($select);
					if($row['parents'] > 0){
						$select2 = $db->select()
						->from('gallery_category AS gc',array('id','name','alias','parents'))
						->where('gc.id = ?', $row['parents'], INTERGER);
						$row2 = $db->fetchRow($select2);
						$urlOptions2 = array('module'=>'gallery','controller'=>'index','action'=>'category',
								'cid'=>$row2['id'],
								'alias'=>$row2['alias'],
						);
						$link2 = $view->url($urlOptions2,'gallery-category');
						$linkBreadcrumbs .= '<li class="brc_arrow"></li><li><a href="'.$link2.'" title="'.$row2['name'].'">'.$row2['name'].'</a></li>';
					}
					$urlOptions = array('module'=>'gallery','controller'=>'index','action'=>'category',
							'cid'=>$row['id'],
							'alias'=>$row['alias'],
					);
					$link = $view->url($urlOptions,'gallery-category');
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li><a href="'.$link.'" title="'.$row['name'].'">'.$row['name'].'</a></li>';
				}
				if(!empty($arrParam['id'])){
					$select = $db->select()
					->from('gallery_album AS ga',array('id','name'))
					->where('ga.id = ?', $arrParam['id'], INTERGER);
					$row = $db->fetchRow($select);
					$linkBreadcrumbs .= '<li class="brc_arrow"></li><li>'.$row['name'].'</li>';
				}
			}
			
			if($arrParam['module'] == 'default'){
				if($arrParam['controller'] == 'contact'){
					$linkBreadcrumbs .= '<li><a href="'.$view->baseUrl('contact.html').'" title="'.$view->language['lienHe'].'">'.$view->language['lienHe'].'</a></li>';
				}
			}
			require_once (BLOCK_PATH . '/BlkBreadcrumbs/'.$template.'.php');
		}
	}
}