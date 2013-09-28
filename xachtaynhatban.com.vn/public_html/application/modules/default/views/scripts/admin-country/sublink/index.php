<?php
	$linkCountry =  $this->baseUrl('/default/admin-country/index');
	$linkCity =  $this->baseUrl('/default/admin-city/index');
	$linkDistrict =  $this->baseUrl('/default/admin-district/index');
?>
<div class="block_subMenu goc10">
	<ul>
		<li><a href="#" class="active">Quốc gia</a></li>
		<li><a href="<?php echo $linkCity;?>">Tỉnh thành</a></li>
		<!-- <li><a href="<?php echo $linkDistrict;?>">Quận huyện</a></li> -->
	</ul>
	<div class="clr"></div>
</div>