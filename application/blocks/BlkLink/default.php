<div class="block_link">
	<div class="block_title">
		<span class="icon"></span>
		<?php echo $view->language['lienKet'];?>
	</div>
	<div class="block_content pd10">
		<select id="select" onchange="window.open(this.options[this.selectedIndex].value,'_blank');this.options[0].selected=true" style="width:100%;">
        	<option value=""><?php echo $view->language['lienKet'];?></option>
        	<?php
			foreach ($row AS $key => $val){
			?>
			<option value="<?php echo $val['url'];?>"><?php echo $val['name'];?></option>
			<?php
			} 
			?>
        </select>
	</div>
	<div class="block_bottom"></div>
</div>