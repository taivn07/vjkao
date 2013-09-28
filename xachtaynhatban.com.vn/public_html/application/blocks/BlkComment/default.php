<div class="block_comment" id="block_comment"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.block_comment').load('<?php echo $view->baseUrl('default/comment/index' . $c_module . $c_id);?>');
	})
</script>