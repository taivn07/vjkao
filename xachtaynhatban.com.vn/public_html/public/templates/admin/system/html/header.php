<?php echo $this->doctype() ?>
<?php
	$info 					= new Zendvn_System_Info();
	$admin_info 			= $info->getMemberInfo();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->headTitle() ?>
        <?php echo $this->headMeta() ?>
        <?php echo $this->headLink() ?>
		<?php echo $this->headScript() ?>
		<link rel="shortcut icon" href="/favicon.ico" />
    </head>
    <body class="body">
        <div class="block_top goct10">
            <span class="version">Version 5.0</span>
            <span class="title">Phần mềm quản trị Website nCMS</span>
        </div>
        <div class="block_mainMenu">
            <div class="block_status">
                <span class="preview">
                    <a target="_blank" href="<?php echo $this->baseUrl('');?>">Xem trang chủ</a>
                </span>
                <a href="#">
                    <span class="no-unread-messages">0</span>
                </a>
                <span class="loggedin-users"><?php echo $admin_info['user_name'];?></span>
                <span class="logout">
                    <a href="<?php echo $this->baseUrl('/default/public/logout');?>">Thoát</a>
                </span>
            </div>
            <div class="block_menu">
            	<div class="topmenu">
                    <div id="ddtopmenubar" class="mattblackmenu">
                        <ul>
                        <li><a href="<?php echo $this->baseUrl('/default/admin/index/');?>" rel="ddsubmenu10">Hệ thống</a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-user/index/');?>" rel="ddsubmenu20">Thành viên</a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-menu/index/type/main_menu');?>" rel="ddsubmenu25">Menu</a></li>
                        <li><a href="<?php echo $this->baseUrl('/article/admin-item/index/');?>" rel="ddsubmenu30">Bài Viết</a></li>
                        <li><a href="<?php echo $this->baseUrl('/shopping/admin-item/index');?>" rel="ddsubmenu40">Sản phẩm</a></li>
                        <li><a href="<?php echo $this->baseUrl('/gallery/admin-album/index');?>" rel="ddsubmenu100">Thư viện ảnh</a></li>
						<li><a href="javascript:void(0)" rel="ddsubmenu50">Thành phần</a></li>
                        <!--
                        <li><a href="javascript:void(0)" rel="ddsubmenu60">Phần mở rộng</a></li>
                         
                        <li><a href="#" rel="ddsubmenu70">Các công cụ</a></li>
                        -->
                        <li><a href="http://onbig.net" rel="ddsubmenu80" target="_blank">Trợ giúp</a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-system/cache');?>" rel="ddsubmenu90">Xóa cache</a></li>
                        
                        </ul>
                    </div>
                    <div class="clr"></div>
        
					<script type="text/javascript">
                    ddlevelsmenu.setup("ddtopmenubar", "topbar"); //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")
                    </script>
        			
                    <!--Top Drop Down Menu 10 HTML-->
                    <ul id="ddsubmenu10" class="ddsubmenustyle">
                    	<li><a href="<?php echo $this->baseUrl('/default/admin/index');?>" class="icon-16-cpanel">Bảng điều khiển</a></li>
                        <li class="separator"></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-info/edit');?>" class="icon-16-profile">Thông tin tài khoản</a></li>
                        <li class="separator"></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-config/index');?>" class="icon-16-config">Cấu hình hệ thống</a></li>
                        <li class="separator"></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-system/index');?>" class="icon-16-info">Thông tin hệ thống</a></li>
                        <li class="separator"></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-media/index');?>" class="icon-16-media">Media Manager</a></li>
                        <li class="separator"></li>
                        <li><a href="javascript:void(0);" onclick="nv_admin_logout();" class="icon-16-logout">Thoát</a></li>
                    </ul>
                    
                    <!--Top Drop Down Menu 20 HTML-->
                    <ul id="ddsubmenu20" class="ddsubmenustyle">
                        <li><a href="<?php echo $this->baseUrl('/default/admin-group/index');?>" class="icon-16-groups">Nhóm thành viên</a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-user/index');?>" class="icon-16-user">Thành viên quản trị</a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-permission/index');?>" class="icon-16-levels">Quyền thành viên</a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-block/edit/id/5');?>" class="icon-16-article">Thỏa thuận sử dụng</a></li>
                    </ul>
                    
                    <!--Top Drop Down Menu 25 HTML-->
                    <ul id="ddsubmenu25" class="ddsubmenustyle">
                        <li><a href="<?php echo $this->baseUrl('/default/admin-menu/index/type_menu/main_menu');?>" class="icon-16-menu">Main menu <span><img src="<?php echo $this->imgUrl . '/menu/icon-16-default.png';?>"></span></a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-menu/index/type_menu/top_menu');?>" class="icon-16-menu">Top menu</a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-menu/index/type_menu/footer_menu');?>" class="icon-16-menu">Footer menu</a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-menu/index/type_menu/left_menu');?>" class="icon-16-menu">Left menu</a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-menu/index/type_menu/right_menu');?>" class="icon-16-menu">Right menu</a></li>
                        <li><a href="<?php echo $this->baseUrl('/default/admin-menu/index/type_menu/intro_menu');?>" class="icon-16-menu">Intro menu</a></li>
                    </ul>
                    
                    <!--Top Drop Down Menu 30 HTML-->
                    <ul id="ddsubmenu30" class="ddsubmenustyle">
                    	<li><a href="<?php echo $this->baseUrl('/article/admin-category/index');?>" class="icon-16-category">Danh mục bài viết</a></li>
	                    <li><a href="<?php echo $this->baseUrl('/article/admin-item/index');?>" class="icon-16-article">Quản lý bài viết</a></li>
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/article/admin-comment/index');?>" class="icon-16-comment">Quản lý bình luận</a></li>
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/article/admin-config/index');?>" class="icon-16-config">Cấu hình module</a></li>
	                    <!-- 
	                    <li class="separator"></li>
	                    <li><a href="#" class="icon-16-trash">Sọt rác</a></li>
	                     -->
	                     
                    </ul>
                    
                    <!--Top Drop Down Menu 40 HTML-->
                    <ul id="ddsubmenu40" class="ddsubmenustyle">
                        <li><a href="<?php echo $this->baseUrl('/shopping/admin-category/index');?>" class="icon-16-category">Danh mục sản phẩm</a></li>
                        <li><a href="<?php echo $this->baseUrl('/shopping/admin-item/index');?>" class="icon-16-article">Sản phẩm</a></li>
                        <li class="separator"></li>
                        <li><a href="<?php echo $this->baseUrl('/shopping/admin-bill/index');?>" class="icon-16-cart">Đơn đặt hàng</a></li>
                        <li><a href="<?php echo $this->baseUrl('/shopping/admin-manu/index');?>" class="icon-16-cpanel">Hãng sản xuất</a></li>
                        <li><a href="<?php echo $this->baseUrl('/shopping/admin-sources/index');?>" class="icon-16-contacts-categories">Nhà cung cấp</a></li>
                        <li><a href="<?php echo $this->baseUrl('shopping/admin-comment/index');?>" class="icon-16-comment">Quản lý bình luận</a></li>
                        <li class="separator"></li>
                        <li><a href="<?php echo $this->baseUrl('/shopping/admin-attribute/index');?>" class="icon-16-stats">Thuộc tính sản phẩm</a>
                        	<ul>
                        		<li><a href="<?php echo $this->baseUrl('/shopping/admin-attribute/index');?>" class="icon-16-stats">Thuộc tính</a></li>
                        		<li><a href="<?php echo $this->baseUrl('/shopping/admin-attribute-category/index');?>" class="icon-16-stats">Nhóm thuộc tính</a></li>
                        	</ul>
                        </li>
                        <li class="separator"></li>
                        <li><a href="<?php echo $this->baseUrl('/shopping/admin-config/index');?>" class="icon-16-config">Cấu hình module</a>
                        	<ul>
                        		<li><a href="<?php echo $this->baseUrl('/shopping/admin-config/index');?>" class="icon-16-config">Cấu hình chung</a></li>
                        		<li><a href="<?php echo $this->baseUrl('/shopping/admin-units/index');?>" class="icon-16-component">Đơn vị sản phẩm</a></li>
		                        <li><a href="<?php echo $this->baseUrl('/default/admin-money/index');?>" class="icon-16-money">Đơn vị tiền tệ</a></li>
		                        <!-- <li><a href="#" class="icon-16-thanhtoan">Phương thức thanh toán</a></li> -->
		                        <li><a href="<?php echo $this->baseUrl('/default/admin-block/edit/id/4');?>" class="icon-16-note">Hướng dẫn thanh toán</a></li>
                        	</ul>
                        </li>
                        <!-- 
                        <li class="separator"></li>
	                    <li><a href="#" class="icon-16-trash">Sọt rác</a></li>
	                    -->
                    </ul>
                    <!--Top Drop Down Menu 100 HTML-->
                    <ul id="ddsubmenu100" class="ddsubmenustyle">
		            	<li><a href="<?php echo $this->baseUrl('/gallery/admin-category/index');?>" class="icon-16-category">Quản lý danh mục</a></li>
		        		<li><a href="<?php echo $this->baseUrl('/gallery/admin-album/index');?>" class="icon-16-media">Quản lý album</a></li>
		        		<li class="separator"></li>
		        		<li><a href="<?php echo $this->baseUrl('/gallery/admin-comment/index');?>" class="icon-16-comment">Quản lý bình luận</a></li>
		        		<li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/gallery/admin-config/index');?>" class="icon-16-config">Cấu hình module</a></li>
	            	</ul>
                    <!--Top Drop Down Menu 50 HTML-->
                    <ul id="ddsubmenu50" class="ddsubmenustyle">
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-ads/index');?>" class="icon-16-banners">Quảng cáo</a>
	                        <ul>
		                        <li><a href="<?php echo $this->baseUrl('/default/admin-ads/index');?>" class="icon-16-banners">Hình ảnh quảng cáo</a></li>
		                        <!-- <li><a href="#" class="icon-16-banners-cat">Nhóm quảng cáo</a></li> -->
	                        </ul>
	                    </li>
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-block/edit/id/1');?>" class="icon-16-article">Nội dung banner</a></li>
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-block/edit/id/2');?>" class="icon-16-article">Nội dung cuối website</a></li>
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-contact/index');?>" class="icon-16-contact">Quản lý liên hệ</a>
	                        <ul>
	                        <li><a href="<?php echo $this->baseUrl('/default/admin-contact/index');?>" class="icon-16-contact">Danh sách liên hệ</a></li>
	                        <li><a href="<?php echo $this->baseUrl('/default/admin-block/edit/id/3');?>" class="icon-16-article">Nội dung liên hệ</a></li>
	                        </ul>
	                    </li>
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-support/index');?>" class="icon-16-back-user">Hỗ trợ trực tuyến</a></li>
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-country/index');?>" class="icon-16-category">Quản lý địa điểm</a>
	                        <ul>
		                        <li><a href="<?php echo $this->baseUrl('/default/admin-country/index');?>" class="icon-16-category">Quốc gia</a></li>
		                        <li><a href="<?php echo $this->baseUrl('/default/admin-city/index');?>" class="icon-16-category">Tỉnh thành</a></li>
		                        <!-- <li><a href="<?php echo $this->baseUrl('/default/admin-district/index');?>" class="icon-16-category">Quận huyện</a></li> -->
	                        </ul>
	                    </li>
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/faqs/admin-faqs/index');?>" class="icon-16-help-forum">Hỏi đáp</a>
	                        <ul>
		                        <li><a href="<?php echo $this->baseUrl('/faqs/admin-category/index');?>" class="icon-16-category">Quản lý danh mục</a></li>
		                        <li><a href="<?php echo $this->baseUrl('/faqs/admin-faqs/index');?>" class="icon-16-help-forum">Danh sách câu hỏi</a></li>
		                        <li class="separator"></li>
	                    		<li><a href="<?php echo $this->baseUrl('/faqs/admin-config/index');?>" class="icon-16-config">Cấu hình module</a></li>
	                        </ul>
	                    </li>
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-video/index');?>" class="icon-16-component">Quản lý Video</a></li>
	                    <!-- <li class="separator"></li>
	                    <li><a href="#" class="icon-16-voting">Thăm dò ý kiến</a></li> -->
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-link/index');?>" class="icon-16-weblinks">Quản lý liên kết</a></li>
                    </ul>
                    
                    <!--Top Drop Down Menu 60 HTML-->
                    <ul id="ddsubmenu60" class="ddsubmenustyle">
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-register/index');?>" class="icon-16-category">Danh sách khách hàng</a></li>
	                    <li class="separator"></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-khoahoc/index');?>" class="icon-16-component">Khóa học</a></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-trungtam/index');?>" class="icon-16-component">Trung tâm</a></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-nguontin/index');?>" class="icon-16-component">Nguồn tin</a></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-thoigian/index');?>" class="icon-16-component">Thời gian</a></li>
	                    <li><a href="<?php echo $this->baseUrl('/default/admin-block/edit/id/6');?>" class="icon-16-article">Nội dung đăng ký</a></li>
                    </ul>
                    
                    <!--Top Drop Down Menu 70 HTML-->
                    <ul id="ddsubmenu70" class="ddsubmenustyle">
	                    <li><a href="#" >Hộp thử đến</a></li>
	                    <li><a href="#" >Hộp thử đi</a></li>
	                    <li><a href="#" >Gửi mail mới</a></li>
	                    <li><a href="#" >Gửi mail hàng loạt</a></li>
	                    <li class="separator"></li>
	                    <li><a href="#" class="icon-16-category">Kiểm tra hệ thống</a></li>
	                    <li class="separator"></li>
	                    <li><a href="#" class="icon-16-comment">Xóa bộ nhớ đệm</a></li>
                    </ul>
                </div>
            </div>
            <div class="clr"></div>
        </div>