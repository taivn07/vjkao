<?php
	if(count($row) > 0){ 
		$siteConfig = Zend_Registry::get('siteConfig');
		$companyInfo = $siteConfig['config_company'];
?>
<div class="block_support">
	<div class="block_title">
		<div class="title"><?php echo $view->language['hoTro'];?></div>
	</div>
	<div class="block_content">
		<div class="hotline">
			<p>Hotline</p>
			<?php echo $siteConfig['config_company']['hotline'];?>
		</div>
		<div class="online">Online</div>
			<table class="support_items">
			<?php
				foreach ($row as $key => $val){
					$name = $val['name'];
					$yahoo = '';
					if($val['yahoo'] != '')
						$yahoo = '<a title="'.$view->language['hoTro'].'" href="ymsgr:sendim?'.$val['yahoo'].'" rel="nofollow"> <img src="http://opi.yahoo.com/online?u='.$val['yahoo'].'&m=g&t=1"></a>';
					$skype = '';
					if($val['skype'] != '')
						$skype = '<a href="skype:'.$val['skype'].'?chat"><img src="'.PUBLIC_URL.'/images/skype.png" style="width:65px;"></a>';
					$email = $val['email'];
					$phone = $val['phone'];
			?>
			
				<tr>
					<td class="yahoo"><?php echo $yahoo;?></td>
					<td><?php echo $name;?></td>
				</tr>
			
			<?php
				} 
			?>
			</table>
	</div>
	<div class="block_bottom"></div>
</div>
<?php
	} 
?>