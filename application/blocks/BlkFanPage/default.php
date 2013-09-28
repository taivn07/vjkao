<?php
	$siteConfig = Zend_Registry::get('siteConfig');
?>
<div class="block_fanPage">
	<div class="block_title">
		<div class="title">Facebook</div>
	</div>
	<div class="block_content">
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1&appId=614473748567264";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		<div class="fb-like-box" data-href="<?php echo $siteConfig['config_company']['facebook'];?>" data-width="203" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
	</div>
</div>
<div id="fb-root"></div>