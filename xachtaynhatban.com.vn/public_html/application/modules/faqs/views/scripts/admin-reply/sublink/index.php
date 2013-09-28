<?php
	$linkCategory =  $this->baseUrl('/faqs/admin-category/index');
	$linkFaqs =  $this->baseUrl('/faqs/admin-faqs/index');
?>
<div class="block_subMenu goc10">
	<ul>
		<li><a href="<?php echo $linkCategory;?>">Danh mục</a></li>
		<li><a href="<?php echo $linkFaqs;?>">Câu hỏi</a></li>
		<li><a href="#" class="active">Trả lời câu hỏi</a></li>
	</ul>
	<div class="clr"></div>
</div>