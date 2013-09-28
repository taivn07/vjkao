<?php
	$linkCategoryManager =  $this->baseUrl('/shopping/admin-category/index/');
	$linkProduct =  $this->baseUrl('/shopping/admin-item/index');
	$linkBill =  $this->baseUrl('/shopping/admin-bill/index');
	$linkComment =  $this->baseUrl('/shopping/admin-comment/index');
?>
<div class="block_subMenu goc10">
	<ul>
		<li><a href="<?php echo $linkCategoryManager;?>">Danh mục</a></li>
		<li><a href="<?php echo $linkProduct;?>">Sản phẩm</a></li>
		<li><a href="<?php echo $linkBill;?>">Đơn đặt hàng</a></li>
		<li><a href="<?php echo $linkComment;?>">Quản lý bình luận</a></li>
		<li><a href="#" class="active">Đơn vị sản phẩm</a></li>
	</ul>
	<div class="clr"></div>
</div>