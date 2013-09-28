<?php
if (count ( $row ) > 0) {
	?>
<div class="block_topArticle">
	<div class="block_title">
		<span class="icon"></span> <?php echo $view->language['articleNoiBat'];?>
	</div>
	<div class="block_content">
		<?php
		
		foreach ( $row as $key => $val ) {
			
			$name = $val ['name'];
			$imgName = explode ( '/editor-upload/images/', $val ['picture'] );
			$picture = '<img src="' . APPLICATION_URL . '/default/public/view-image/width/100/height/100/images/' . $imgName [1] . '" alt="' . $val ['name'] . '"/>';
			
			$urlOptions = array ('module' => 'article', 'controller' => 'index', 'action' => 'detail', 'cid' => $val ['cat_id'], 'tcat' => $val ['category_alias'], 'title' => $val ['alias'], 'id' => $val ['id'] );
			
			$linkDetial = $view->url ( $urlOptions, 'article-detail' );

			$start = '';
			if($key == 0){
				$start = 'start';
			}
		?>
		<div class="block_items clearfix <?php echo $start;?>">
			<div class="article_image">
				<a href="<?php echo $linkDetial;?>" title="<?php echo $name;?>"><?php echo $picture;?></a>
			</div>
			<div class="article_title">
				<a href="<?php echo $linkDetial;?>" title="<?php echo $name;?>"><?php echo $name;?></a>
			</div>
		</div>
		<?php
		} 
		?>
	</div>
	<div class="block_bottom"></div>
</div>
<?php }?>
