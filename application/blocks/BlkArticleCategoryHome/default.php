<?php
foreach ($row as $key => $val){
	$name = $val['name'];
	$urlOptions = array('module'=>'article','controller'=>'index','action'=>'category',
			'cid'=>$val['id'],
			'alias'=>$val['alias'],
	);
	$link = $view->url($urlOptions,'article-category');
?>
<div class="block_articleHome">
	<div class="block_title clearfix">
		<span class="icon"></span>
		<h3 class="title"><a href="<?php echo $link;?>" title="<?php echo $name;?>"><?php echo $name;?></a></h3>
		<p class="viewall"><a href="<?php echo $link;?>" title="<?php echo $view->language['xemTatCa'];?>"><?php echo $view->language['xemTatCa'];?></a></p>
	</div>
	<div class="block_content articles">
    	<?php
		if(count($val['items'])>0){ 
			foreach ($val['items'] as $key_item => $val_item){
						
				$name_item = $val_item['name'];
				$picture = '<img class="img" src="' . $val_item['thumb'] .'" alt="'.$val_item['name'].'"/>';
				$synopsis = nl2br($val_item['synopsis']);
				
				$urlOptions_item = array ('module' => 'article', 
									'controller' => 'index', 
									'action' => 'detail', 
									'cid' => $val_item['cat_id'], 
									'tcat' => $val_item['category_alias'], 
									'title' => $val_item['alias'], 
									'id' => $val_item['id'] );
				$linkDetial_item = $view->url( $urlOptions_item, 'article-detail' );
				if($key_item == 0){
		?>
		<div class="item">
			<a href="<?php echo $linkDetial_item;?>" title="<?php echo $name_item;?>">
				<?php echo $picture;?>
			</a>
			<div class="desc">
				<h3 class="title">
					<a href="<?php echo $linkDetial_item;?>" title="<?php echo $name_item;?>"><?php echo $name_item;?></a>
				</h3>
				<div><?php echo $synopsis;?></div>
			</div>
			<div class="clr"></div>
		</div>
        <?php
				}else{
		?>
		<div class="item_sub">
			<h3 class="title">
				<a href="<?php echo $linkDetial_item;?>" title="<?php echo $name_item;?>"><?php echo $name_item;?></a>
			</h3>
		</div>
		<?php
				}
			} 
		} 
      	?>
      	<div class="clr"></div>
 	</div>
 	<div class="block_bottom"></div>
</div>
<?php 
	}
?>