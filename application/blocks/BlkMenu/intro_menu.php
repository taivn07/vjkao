<div class="block_introMenu">
	<?php echo $strMenu;?>
	<div class="clr"></div>
	<script type="text/javascript">
		var i = 1;
		$('.block_introMenu li').each(function(){
			if(i % 4 == 0){
				$(this).addClass('last');
			}
			i++;
		})
		$('.block_introMenu li').hover(
			function(){
				$(this).addClass('hover');
			},
			function(){
				$(this).removeClass('hover');
			}
		);
	</script>
</div>