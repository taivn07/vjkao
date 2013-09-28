<?php
	foreach ($row as $key => $val){
		echo htmlspecialchars_decode($val['content']);
	}
?>