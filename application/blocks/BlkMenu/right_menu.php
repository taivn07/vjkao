<?php
if(count($result) > 0){ 
?>
<div class="block_rightMenu">
	<div class="block_title">
		<span class="icon"><img src="<?php echo $view->imgUrl;?>/danhmuc.png" alt="icon"></span>
		<?php echo $view->language['danhMuc'];?>
	</div>
	<div class="block_content">
		<div class="rightMenu" id="rightMenu">
			<?php echo $strMenu;?>
			<div class="clr"></div>
		</div>
	</div>
</div>
<?php
} 
?>