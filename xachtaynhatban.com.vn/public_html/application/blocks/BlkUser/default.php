<?php
	$info 		= new Zendvn_System_Info();
	$user_info 	= $info->getMemberInfo();
	
	$yourCart = new Zend_Session_Namespace('cart');
	$ssInfo = $yourCart->getIterator();
	$tmp = $yourCart->cart;
	$countItem = 0;
	if(count($tmp)>0){
		foreach ($tmp as $key => $val){
			$countItem += $val;
		}
	}
	$countItem;
?>
<div class="block_user">
	<a href="<?php echo $view->baseUrl('member.html');?>" rel="nofollow">Tài khoản của tôi</a>
	<a href="<?php echo $view->baseUrl('default/user/order');?>" rel="nofollow">Quản lý đơn hàng</a>
	<a href="<?php echo $view->baseUrl('default/user/wishlist');?>" rel="nofollow">Danh sách ưa thích</a>
	<a href="<?php echo $view->baseUrl('shopping/public/view-cart');?>" rel="nofollow">Giỏ hàng (<span id="total_Cart">0</span>)</a>
	<?php
		if($user_info['phanloai'] == 1 && $user_info['group_id'] == 0){
	?>
		<a href="<?php echo $view->baseUrl('member.html');?>" rel="nofollow"><?php echo $user_info['user_name'];?></a>
		<a href="<?php echo $view->baseUrl('logout.html');?>" rel="nofollow"><?php echo $view->language['thoat'];?></a>
	<?php
		}else{ 
	?>
		<a href="<?php echo $view->baseUrl('login.html');?>" rel="nofollow"><?php echo $view->language['dangNhap'];?></a>
		<a href="<?php echo $view->baseUrl('register.html');?>" rel="nofollow"><?php echo $view->language['dangKy'];?></a>
	<?php
		} 
	?>
</div>