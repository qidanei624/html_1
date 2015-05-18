<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->view('admin/header');
$this->load->view('admin/menu');
?>
<div class="main">
    <div class="body body-950">
        <div class="container typecho-page-title">
			<div class="column-24 start-01">
				<h2><?php echo $page_title;?></h2>
				<p><?php echo anchor(site_url(),'查看我的站点');?></p>
			</div>
		</div>        
    </div>
</div>
<?php 
$this->load->view('admin/common-js');
$this->load->view('admin/copyright');
$this->load->view('admin/footer');
?>