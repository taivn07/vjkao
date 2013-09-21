<?php
foreach ( $row as $key => $val ) {
	$name = $val ['name'];
	$urlOptions = array('module'=>'article','controller'=>'index','action'=>'category',
			'cid'=>$val['id'],
			'alias'=>$val['alias'],
	);
	$link = $view->url($urlOptions,'article-category');
?>
<div class="block_articleLeft">
	<div class="block_title">
		<h3 class="title"><a href="<?php echo $link;?>" title="<?php echo $name;?>"><?php echo $name;?></a></h3>
	</div>
	<div class="block_content">
		<?php
		foreach ( $val ['items'] as $key_item => $val_item ) {
			
			$name_item = $val_item ['name'];
			$picture_item = '<img src="' . $val_item ['thumb'] . '" alt="' . $val_item ['name'] . '"/>';
			$synopsis = $val_item ['synopsis'];
			$urlOptions = array ('module' => 'article', 'controller' => 'index', 'action' => 'detail', 'cid' => $val_item ['cat_id'], 'tcat' => $val_item ['category_alias'], 'title' => $val_item ['alias'], 'id' => $val_item ['id'] );
			
			$linkDetial = $view->url ( $urlOptions, 'article-detail' );
			
			$start = '';
			if ($key_item == 0) {
				$start = 'start';
			}
			?>
		<div class="block_items clearfix <?php echo $start;?>">
			<div class="article_image">
				<a href="<?php echo $linkDetial;?>" title="<?php echo $name_item;?>"><?php echo $picture_item;?></a>
			</div>
			<div class="article_title">
				<h4 class="title">
					<a href="<?php echo $linkDetial;?>"	title="<?php echo $name_item;?>"><?php echo $name_item;?></a>
				</h4>
			</div>
		</div>
		<?php
		}
		?>
	</div>
	<div class="block_bottom"></div>
</div>
<?php
}
?>
