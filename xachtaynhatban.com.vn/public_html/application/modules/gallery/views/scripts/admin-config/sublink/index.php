<?php
	$linkCategory =  $this->baseUrl('/gallery/admin-category/index');
	$linkAlbum =  $this->baseUrl('/gallery/admin-album/index');
?>
<div class="block_subMenu goc10">
	<ul>
		<li><a href="<?php echo $linkCategory;?>">Danh mục</a></li>
		<li><a href="<?php echo $linkAlbum;?>">Album</a></li>
		<li><a href="#" class="active">Cấu hình module</a></li>
	</ul>
	<div class="clr"></div>
</div>