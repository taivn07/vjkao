<?php
	$linkAdminInfo =  $this->baseUrl('/default/admin-info/edit');
	$linkAdminPassword =  $this->baseUrl('/default/admin-info/password');
?>
<div class="block_subMenu goc10">
	<ul>
		<li><a href="<?php echo $linkAdminInfo;?>" <?php if($this->arrParam['action'] == 'edit') echo 'class="active"';?>>Thông tin tài khoản</a></li>
		<li><a href="<?php echo $linkAdminPassword;?>" <?php if($this->arrParam['action'] == 'password') echo 'class="active"';?>>Đổi mật khẩu</a></li>
	</ul>
	<div class="clr"></div>
</div>