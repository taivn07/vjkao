<?php
	$slbNganhnghe	= $view->formSelect('nganhnghe_id',$arrParam['nganhnghe_id'],array(),$slbNganhnghe);
	$slbCity		= $view->formSelect('city_id',$arrParam['city_id'],array(),$slbCity);
	$slbMucluong	= $view->formSelect('mucluong_id',$arrParam['mucluong_id'],array(),$slbMucluong);
	$slbCapbac		= $view->formSelect('capbac_id',$arrParam['capbac_id'],array(),$slbCapbac);
	$slbBangcap		= $view->formSelect('bangcap_id',$arrParam['bangcap_id'],array(),$slbBangcap);
	$slbKinhnghiem	= $view->formSelect('kinhnghiem_id',$arrParam['kinhnghiem_id'],array(),$slbKinhnghiem);
	$slbHinhthuclamviec	= $view->formSelect('hinhthuclamviec_id',$arrParam['hinhthuclamviec_id'],array(),$slbHinhthuclamviec);
?>
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
<div class="block_search">
	<div class="block_title">
		<span class="icon"></span>
		<div class="title">Tìm kiếm</div>
	</div>
	<div class="block_content">
		<div class="item clearfix">
			<div class="label">Ngành nghề</div>
			<div class="value">
				<?php echo $slbNganhnghe;?>
			</div>
			<div class="clr"></div>
		</div>
		<div class="item clearfix">
			<div class="label">Địa điểm</div>
			<div class="value">
				<?php echo $slbCity;?>
			</div>
			<div class="clr"></div>
		</div>
		<div class="item clearfix">
			<div class="label">Mức lương</div>
			<div class="value">
				<?php echo $slbMucluong;?>
			</div>
			<div class="clr"></div>
		</div>
		<div class="item clearfix">
			<div class="label">H.T làm việc</div>
			<div class="value">
				<?php echo $slbHinhthuclamviec;?>
			</div>
			<div class="clr"></div>
		</div>
		<div class="item clearfix">
			<div class="label">Cấp bặc</div>
			<div class="value">
				<?php echo $slbCapbac;?>
			</div>
			<div class="clr"></div>
		</div>
		<div class="item clearfix">
			<div class="label">Bằng cấp</div>
			<div class="value">
				<?php echo $slbBangcap;?>
			</div>
			<div class="clr"></div>
		</div>
		<div class="item clearfix">
			<div class="label">Kinh nghiệm</div>
			<div class="value">
				<?php echo $slbKinhnghiem;?>
			</div>
			<div class="clr"></div>
		</div>
		<div class="button">
			<input type="button" value="Tìm kiếm" class="btn_1 goc10"> <input
				type="reset" value="Hủy tìm kiếm" class="btn_2 goc10">
		</div>
	</div>
	<div class="block_bottom"></div>
</div>