<div class="block_slide">
	<link href="<?php echo $view->cssUrl . '/default.css';?>" rel="stylesheet" type="text/css" media="screen" />
	<link href="<?php echo $view->cssUrl . '/nivo-slider.css';?>" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo $view->jsUrl . '/jquery.nivo.slider.js';?>"></script>
	<div class="slider-wrapper theme-default">
		<div id="slider" class="nivoSlider">
		<?php
			foreach ($row as $key => $val){
			$link = $val['url'];
			$target = $val['target'];
			$picture = $val['picture'];
			$name = $val['name'];
		?>
       		<a href="<?php echo $link;?>"><img src="<?php echo $picture;?>" data-thumb="<?php echo $picture;?>" alt="<?php echo $name;?>"/></a>
       	<?php
			} 
       	?>
		</div>
	</div>
        
	<script type="text/javascript">
        $(window).load(function() {
            $('#slider').nivoSlider();
        });
	</script>

</div>