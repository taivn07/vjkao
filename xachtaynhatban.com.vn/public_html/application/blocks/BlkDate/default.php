<?php
	$date = new Zend_Date();
	$dateNow = $date->get(Zend_Date::DATE_FULL);
?>
<div class="block_date">
	<?php echo $dateNow;?>
</div>