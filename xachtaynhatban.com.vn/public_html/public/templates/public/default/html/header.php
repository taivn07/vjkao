<?php echo $this->doctype() ?>
<?php $siteConfig = Zend_Registry::get('siteConfig');?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->headTitle() ?>
    <?php echo $this->headMeta() ?>
	<?php echo $this->headLink() ?>
	<?php echo $this->headScript() ?>
	<script type="text/javascript">var base_url = "<?php echo $siteConfig['config_site']['site_url'];?>";</script>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
</head>
<body class="body">