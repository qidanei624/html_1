<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/** 触发一个初始化插件admin/header */
//$this->plugin->trigger('admin/header');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo $page_title.' - '. setting_item('blog_title');?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/reset.source.css" /> 
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/grid.source.css" /> 
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/typecho.source.css" />   
		<script src="<?php echo base_url();?>javascript/lottery.js" type="text/javascript"></script>
</head>
<body<?php if (isset($body_class)) {echo ' class="' . $body_class . '"';} ?>>

