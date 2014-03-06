<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="<?php echo $response->get('keywords'); ?>" />
	<meta name="description" content="<?php echo $response->get('description'); ?>" />
	<title><?php echo $response->get('title'); ?></title>
	<?php use_helper('asset'); use_helper('template'); include_scripts(); include_stylesheets(); include_slot('head'); ?>
</head>
<body>

<?php echo $response->get('content'); ?>

</body>
</html>
