<div class="block_leftMenu">
	<div class="block_title">
		<div class="title"><?php echo $view->language['productSanPham'];?></div>
	</div>
	<div class="block_content">
		<div class="leftMenu" id="leftMenu">
			<?php echo $strMenu;?>
		</div>
	</div>
</div>

<script type="text/javascript">
	ddsmoothmenu.init({
	mainmenuid: "leftMenu",
	orientation: 'v',
	classname: 'leftMenu',
	contentsource: "markup"
	})
	jQuery('#leftMenu li li:first a').addClass('start');
</script>