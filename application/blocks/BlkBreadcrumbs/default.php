<ul id="breakcrumb" class="breakcrumb clearfix">
<?php
    if($arrParam['module'] == 'default' && $arrParam['controller'] == 'index' && $arrParam['action'] == 'index'){
		echo '<li>' . $view->blkDate() . '</li>';
    }else{
    	echo $linkBreadcrumbs;
    }
?>
</ul>
<div class="clr"></div>