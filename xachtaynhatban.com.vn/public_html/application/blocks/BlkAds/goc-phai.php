<script language="javascript" type="text/javascript">
   
    function toggle_bb() {
        if ($(".bb_open").css('display') == 'none') {
            $(".bb_open").show();
            $(".bb_close").hide();
            $(".bottombar").toggle();
        } else if ($(".bb_close").css('display') == 'none') {
            $(".bb_open").hide();
            $(".bb_close").show();
            $(".bottombar").toggle();
        }

    }
</script>
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
<div id="bottombar">
    <div style="display: none" class="bb_open clearfix">
    	<marquee style="width:275px;" behavior="alternate">
        	<a style="color:#fff;text-decoration:none;font-weight:bold;display:inline;margin-top:4px;float:left" href="<?php echo $link;?>"><?php echo $name;?></a>
        </marquee>
        <a onclick="toggle_bb();" href="javascript://">
            <img border="0" alt="" style="float:right" src="<?php echo $view->imgUrl . '/open_popup.gif';?>">
        </a>
    </div>
    <div class="bb_close clearfix">
        <a onclick="toggle_bb();" href="javascript://">x</a>
    </div>
    <div class="bottombar adsGocPhai">
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
				<img src="<?php echo $picture;?>" alt="<?php echo $name;?>"/>
			</a>
		<?php
			} 
		?>
    </div>
</div>
<?php
	} 
?>