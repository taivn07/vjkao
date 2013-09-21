<div class="block_adsScroll scroll_l">
<?php
	foreach ($row as $key => $val){
		$link = $val['url'];
		$target = $val['target'];
		$picture = $val['picture'];
		$name = $val['name'];
		$width = 'width="100%"';
		if($val['width'] != '')
			$width = 'width="' . $val['width'] . '"';
		$height = 'height="100%"';
		if($val['height'] != '')
			$height = 'height="' . $val['height'] . '"';
		$start = '';
		if($key == 0){
			$start = 'start';
		}
?>
	<div class="block_items <?php echo $start;?>">
	<?php
		if($val['type'] == 'flash'){ 
	?>
		<a style="display:block; margin:0 auto; cursor:pointer;" href="<?php echo $link;?>" target="<?php echo $target;?>">
       		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" <?php echo $width;?> <?php echo $height;?>>
				<param name="movie" value="<?php echo $picture;?>" />
				<param name="quality" value="high" />
				<param name="wmode" value="transparent">
				<PARAM NAME="SCALE" VALUE="default">
				<embed src="<?php echo $picture;?>" quality="high" type="application/x-shockwave-flash" WMODE="transparent" <?php echo $width;?> <?php echo $height;?> SCALE="default" pluginspage="http://www.macromedia.com/go/getflashplayer" />
			</object>
        </a>
    <?php
		} 
    ?>
    <?php
		if($val['type'] == 'image'){ 
	?>
		<a href="<?php echo $link;?>" target="<?php echo $target;?>">
			<img src="<?php echo $picture;?>"/>
		</a>
	<?php
		} 
	?>
	</div>
<?php
	} 
?>
</div>