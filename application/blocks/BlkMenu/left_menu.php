<div class="block_shopCategory">
	<div class="block_title">
		<span class="icon"></span>
		<?php echo $view->language['danhMuc'];?>
	</div>
	<div class="block_content pd0">
	   	<script type="text/javascript">
			ddsmoothmenu.init({
			mainmenuid: "smoothmenu2", //Menu DIV id
			orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
			classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
			//customtheme: ["#804000", "#482400"],
			contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
			})
		</script>
		<div id="smoothmenu2" class="ddsmoothmenu-v">
	    	<?php echo $strMenu;?>
		</div>
	</div>
	<div class="block_bottom"></div>
</div>