
<div class="news_headline clearfix">
    <a title="Tin tức máy tính, điện máy" class="nh_label" href="<?php $view->baseUrl('');?>"></a>
    <div class="nh_content">
        <ul id="news" class="nh_news innerfade">
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
        	<li><a href="<?php echo $linkDetial;?>" title="<?php echo $name;?>"><?php echo $name;?></a></li>
        <?php
		} 
		?>
        </ul>
    </div>
    <div class="nh_edge"></div>
</div>
<script type="text/javascript">
$(function() {
	$(".nh_content").jCarouselLite({
		vertical: true,
		hoverPause:true,
		visible: 1,
		auto:2000,
		speed:1000
	});
});
</script>