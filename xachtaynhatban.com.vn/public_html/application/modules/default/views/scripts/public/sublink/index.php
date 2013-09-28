<?php
	$linkGroupManager =  $this->baseUrl('/default/admin-group/index/');
	$linkPermission =  $this->baseUrl('/default/admin-permission/index/');
?>
<div class="block_subMenu goc10">
	<ul>
		<li><a href="<?php echo $linkGroupManager;?>">Nhóm thành viên</a></li>
		<li><a href="#" class="active">Thành viên</a></li>
		<li><a href="<?php echo $linkPermission;?>">Permission</a></li>
	</ul>
	<div class="clr"></div>
</div>