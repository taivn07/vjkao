<div class="block_link">
	<div class="block_title">
		<span class="icon"></span>
		<?php echo $view->language['doiTac'];?>
	</div>
	<div class="block_content">
		<?php
			foreach ($row AS $key => $val){
		?>
		<p class="item"><a href="<?php echo $val['url'];?>" title="<?php echo $val['name'];?>"><?php echo $val['name'];?></a></p>
		<?php
		} 
		?>
	</div>
	<div class="block_bottom"></div>
</div>