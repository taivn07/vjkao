<?php
	$linkGroupManager =  $this->baseUrl('/default/admin-group/index');
	$linkMemberManager =  $this->baseUrl('/default/admin-user/index');
	$linkPermission =  $this->baseUrl('/default/admin-permission/index');
?>
<div class="block_subMenu goc10">
	<ul>
		<li><a href="<?php echo $linkGroupManager;?>">Nhóm thành viên</a></li>
		<li><a href="<?php echo $linkMemberManager;?>">Thành viên</a></li>
		<li><a href="#" class="active">Quyền thành viên</a></li>
	</ul>
	<div class="clr"></div>
</div>