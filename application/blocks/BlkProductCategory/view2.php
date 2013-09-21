<div class="block_leftMenu">
	<div class="block_title">
		<div class="title"><?php echo $view->language['productSanPham'];?></div>
	</div>
	<div class="block_content">
		<div class="leftMenu" id="leftMenu">
			<?php echo $strMenu;?>
		</div>
	</div>
	<script type="text/javascript">
		$('.block_leftMenu ul > li').each(function(){
			$('ul li a:first', this).addClass('start');
		});
	</script>
</div>