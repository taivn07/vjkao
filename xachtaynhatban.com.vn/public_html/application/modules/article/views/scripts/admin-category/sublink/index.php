<?php
	$linkArticleCategory =  $this->baseUrl('/article/admin-category/index/');
	$linkArticle =  $this->baseUrl('/article/admin-item/index/');
	$linkComment =  $this->baseUrl('/article/admin-comment/index/');
?>
<div class="block_subMenu goc10">
	<ul>
		<li><a href="#" class="active">Danh mục</a></li>
		<li><a href="<?php echo $linkArticle;?>">Bài viết</a></li>
		<li><a href="<?php echo $linkComment;?>">Quản lý bình luận</a></li>
	</ul>
	<div class="clr"></div>
</div>