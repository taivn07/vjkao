<div class="block_mainMenu">
	<div id="smoothmenu1" class="ddsmoothmenu">
        <?php echo $strMenu;?>
        <div class="clr"></div>
	</div>
	<script type="text/javascript">
		ddsmoothmenu.init({
			mainmenuid: "smoothmenu1",
			orientation: 'h',
			classname: 'ddsmoothmenu',
			//customtheme: ["#1c5a80", "#18374a"],
			contentsource: "container_id"
		})
	</script>
	<?php echo $view->blkSearch();?>
</div>