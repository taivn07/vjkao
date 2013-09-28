<div class="block_adsLeft">
	<div class="block_title">
		<div class="title">Quảng cáo</div>
	</div>
	<div class="block_content">
	<?php
		foreach ($row as $key => $val){
			$link = $val['url'];
			$target = $val['target'];
			$picture = $val['picture'];
			$name = $val['name'];
			$width = '';
			if($val['width'] != '')
				$width = 'width="' . $val['width'] . '"';
			$height = '';
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
        		<object <?php echo $width . ' ' . $height;?> border="0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"><param name="movie" value="<?php echo $picture;?>"><param name="AllowScriptAccess" value="always"><param name="quality" value="High"><param name="wmode" value="transparent"><embed <?php echo $width . ' ' . $height;?> allowscriptaccess="always" pluginspage="http://www.macromedia.com/go/getflashplayer" src="<?php echo $picture;?>" type="application/x-shockwave-flash" wmode="transparent"></object>
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
	<div class="block_bottom"></div>
</div>