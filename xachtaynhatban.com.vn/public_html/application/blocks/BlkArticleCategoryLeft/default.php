<?php echo $xhtml;?>
<script type="text/javascript">
	ddsmoothmenu.init({
		mainmenuid: "leftMenu", //Menu DIV id
		orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
		classname: 'leftMenu', //class added to menu's outer DIV
		//customtheme: ["#804000", "#482400"],
		contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
	});
	jQuery('.leftMenu ul li:first a').addClass('start');
</script>