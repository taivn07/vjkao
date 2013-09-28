<div class="block_adsRight">
	<div class="block_title">
		<div class="title">Thông tin tỷ giá</div>
	</div>
	<div class="block_content">
		<div style="padding-bottom: 5px;">
			<img src="<?php echo $view->imgUrl . '/circle-chart.png'?>" style="display: inline-block; float: left;"> <span style="font-size: 14px; display: inline-block; margin: 4px 0 0 5px; font-weight: bold;">Tỷ giá</span>
			<div class="clr"></div>
		</div>
		<div>
			<?php
			$linkGet = 'http://muasamcangay.com/tool/weather/?size=193&height=300&fsize=12&bg=images/bg2.png&repeat=repeat-x&r=1&w=0&g=0&col=1&d=0';
			$subject = file_get_contents($linkGet);
				
			$pattern = '#bgcolor="\#ffffff">\&nbsp\;\&nbsp\;(.*)</td>.*bgcolor="\#ffffff">\&nbsp\;(.*)</td>#imsU';
			preg_match_all($pattern,$subject,$matches);
			
			?>
			<table style="width: 100%; color: #333; border: 1px solid #CCC;">
				<?php
				
				foreach ($matches[1] AS $key => $val) {
					if($key == 0) {
						$css = 'font-weight: bold;';
					} else {
						$css = '';
					}
				?>
				<tr>
					<td style="padding: 3px; border: 1px solid #CCC;<?php echo $css;?>"><?php echo $val;?></td>
					<td style="padding: 3px; border: 1px solid #CCC;<?php echo $css;?>"><?php echo $matches[2][$key];?></td>
				</tr>
				<?php
				} 
				?>
			</table>
		</div>
	</div>
</div>