<div class="timkiem">
			<script language="javascript">
			function doSubmit(){	
				if (document.frmSearch.keywords.value == "Tìm kiếm") {
					document.frmSearch.keywords.focus();
					return false;
				}
				document.frmSearch.submit();
				return;
			}
			</script>
			<form name="frmSearch" action="<?php echo $view->baseUrl('/shopping/index/filter/type/search') ?>" method="post">
				<table cellspacing="0" cellpadding="0" border="0" id="table1" style="border-collapse: collapse">
			 		<tr>
			     		<td width="107" class="searchText">
			     			<input type="text" id="keywords" autocomplete="off" name="keywords" value="Tìm kiếm" onblur="javascript:if(this.value==''){this.value='Tìm kiếm';};" onfocus="javascript:if(this.value=='Tìm kiếm'){this.value='';};">
			           	</td>
			           	<td width="36" class="searchBtn" onclick="javascript:return doSubmit(0);" onmouseover="this.className='searchBtnOn'" onmouseout="this.className='searchBtn'">&nbsp;</td>
			      	</tr>
			   	</table>
			</form>
			<script type="text/javascript">
				$('#keywords').keyup(function(event) {
					//alert($('#keywords').val());
					var key = $('#keywords').val();
					if($('#keywords').val() != ''){
						$('.searchProduct').load('<?php echo $view->baseUrl('shopping/block/ajax/keywords/');?>' + key);
						$('.searchProduct').show();
					}else{
						$('.searchProduct').hide();
					}
				});
				$('body').click(function(){
					$('.searchProduct').hide();
				});
			</script>
			<div class="searchProduct">
				
			</div>
		</div>