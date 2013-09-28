<div class="block_video">
	<div class="block_content">
		<script type="text/javascript" src="<?php echo $view->jsUrl;?>/video/jwplayer.js"></script>
		<div id="container">Loading the player ...</div>
		<script type="text/javascript">
		jwplayer("container").setup({
			flashplayer:"<?php echo $view->jsUrl;?>/video/jwplayer.flash.swf",
			height:192,
			width:220,
			playlist:[ // Danh sách video
			<?php
			foreach ($row AS $key => $val){ 
			?>
			{ file:"<?php echo $val['file'];?>",image:""},
			<?php
			} 
			?>
			]
		});
		
		</script>
	</div>
	<div class="block_bottom"></div>
</div>