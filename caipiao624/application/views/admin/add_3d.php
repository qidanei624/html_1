<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
$this->load->view('admin/menu');
?>
<div class="main">
    <div class="body body-950">
        <?php $this->load->view('admin/notify'); ?>
		<div class="container typecho-page-title">
				<div class="column-24 start-01">
					<h2><?php echo $page_title;?></h2>
					<p><?php echo anchor(site_url(),'查看我的站点');?></p>
				</div>
		</div>
		<div class="container typecho-page-main">
            <div class="column-24 start-01">

				<ul class="typecho-option-tabs">
					<li<?php if('3d_one' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/threed/manage','主题和副码');?></li>
					<li<?php if('3d_two' == $status): ?> class="current"<?php endif; ?>><a href="<?php echo site_url('admin/threed/manage/3d_two'); ?>">数型与配伍</a></li>
					<li<?php if('add' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/threed/add','添加');?></li>
				</ul>

                <form name="manage_threed" action="" method="post" enctype="application/x-www-form-urlencoded">
				<ul class="typecho-option">
				<li>
					<label for="date" class="typecho-label">开奖日期*</label>
					<input maxlength="10" type="text" name="created" id="created" value="<?php echo set_value('created', (isset($created))?$created:date('Y-m-d')) ;?>" />	
					<?php echo form_error('created', '<p class="message error">', '</p>'); ?>
				</li>
				</ul>

				<ul class="typecho-option">
				<li>
				<label class="typecho-label">
				年度期号*</label>
				<input id="lottery_qh" name="lottery_qh" maxlength="7" type="text" value="<?php echo set_value('lottery_qh',(isset($lottery_qh))?$lottery_qh:''); ?>" />
				<?php echo form_error('lottery_qh', '<p class="message error">', '</p>'); ?>
				</li>
				</ul>

				<ul class="typecho-option">
				<li>
					<label class="typecho-label">新历*</label>
					<input maxlength="10" type="text" name="solar" id="solar" value="<?php echo set_value('solar', (!empty($solar))?$solar:date('Y-m-d')) ;?>" />
					<?php echo form_error('solar', '<p class="message error">', '</p>'); ?>
				</li>
				</ul>
				
				<ul class="typecho-option">
				<li>
				<label class="typecho-label">
				中奖号码*</label>
				<input id="lottery_number" name="lottery_number" maxlength="16" type="text" value="<?php echo set_value('lottery_number',(isset($lottery_number))?$lottery_number:'');?>"/>
				<button onclick="doAction('auto', 'do')">自动生成</button>
				<?php echo form_error('lottery_number', '<p class="message error">', '</p>'); ?>
				</li>
				</ul>

				<ul class="typecho-option">
				<li>
				<label class="typecho-label">
				干支*</label>
				<select name="lottery_ganzhi">
					<option value="0">请选择...</option>
					<?php if($ganzhi_arrays):?>
						<?php foreach($ganzhi_arrays as $z):?>
							<option value="<?php echo $z;?>" <?php if(isset($lottery_ganzhi) && $lottery_ganzhi == $z):?>selected="true"<?php endif;?>><?php echo $z;?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('lottery_ganzhi', '<p class="message error">', '</p>'); ?>
				</li>
				</ul>			

				<ul class="typecho-option">
				<li>
				<label class="typecho-label">
				农历*</label>
				<input id="lunar" name="lunar" type="text" value="<?php echo set_value('lunar',(isset($lunar))?$lunar:'');?>" />
				<?php echo form_error('lunar', '<p class="message error">', '</p>'); ?>

				<label class="typecho-label2">
				星&nbsp;&nbsp;&nbsp;期*</label>
				<input id="week" name="week" type="text" size="2" maxlength="1" value="<?php echo set_value('week',(isset($week))?$week:'');?>" />
				<?php echo form_error('week', '<p class="message error">', '</p>'); ?>

				<label class="typecho-label2">
				开干纳音*</label>
				<input id="kgny" name="kgny" type="text" size="2" maxlength="1" value="<?php echo set_value('kgny', (isset($kgny))?$kgny:'');?>" />
				<?php echo form_error('kgny', '<p class="message error">', '</p>'); ?>
				</li>
				</ul>

				<ul class="typecho-option">
				<li>
				<label class="typecho-label">主题和副码*</label>
				<input id="zhuti_fuma" name="zhuti_fuma" type="text"  value="<?php if(isset($zhuti_fuma)) echo $zhuti_fuma;?>" />
				<?php echo form_error('zhuti_fuma', '<p class="message error">', '</p>'); ?>
				<label class="typecho-label2">虚主题*</label>
				<input id="xuzhuti" name="xuzhuti" type="text"  value="<?php if(isset($xuzhuti)) echo $xuzhuti;?>" />
				<?php echo form_error('xuzhuti', '<p class="message error">', '</p>'); ?>
				</li>
				</ul>

				<ul class="typecho-option">
				<li>
				<label class="typecho-label">
				岁数*</label>
				<input id="xc_suishu" name="xc_suishu" type="text" maxlength="2" value="<?php echo set_value('xc_suishu', (isset($xc_suishu))?$xc_suishu:'');?>" />
				<?php echo form_error('xc_suishu', '<p class="message error">', '</p>'); ?>

				<label class="typecho-label2">纳&nbsp;&nbsp;&nbsp;音*</label>
				<input id="xc_nayin" name="xc_nayin" type="text" value="<?php echo set_value('xc_nayin', (isset($xc_nayin))?$xc_nayin:'');?>" />
				<?php echo form_error('xc_nayin', '<p class="message error">', '</p>'); ?>

				<label class="typecho-label2">
				相冲干支*</label>
				<input id="xc_ganzhi" name="xc_ganzhi" type="text" value="<?php echo set_value('xc_ganzhi',(isset($xc_ganzhi))?$xc_ganzhi:'');?>" />
				<?php echo form_error('xc_ganzhi', '<p class="message error">', '</p>'); ?>
				</li>
				</ul>

				<ul class="typecho-option">
				<li>
				<label class="typecho-label">
				九宫星*</label>
				<input id="jgx" name="jgx" type="text" maxlength="2" value="<?php echo set_value('jgx',(isset($jgx))?$jgx:'');?>" />
				<?php echo form_error('jgx', '<p class="message error">', '</p>'); ?>
				</li>
				</ul>

				<ul class="typecho-option">
				<li>
				<label class="typecho-label">
				生肖名年份*</label>
				<input id="sx_year" name="sx_year" type="text" maxlength="4" value="<?php echo set_value('sx_year',(isset($sx_year))?$sx_year:'');?>" />
				<?php echo form_error('sx_year', '<p class="message error">', '</p>'); ?>
				</li>
				</ul>


				

				<ul class="typecho-option typecho-option-submit">
				<li>
				<button onclick="<?php echo (isset($pid) && is_numeric($pid))?"doAction('edit', 'do')":"doAction('add', 'do')";?>">
				<?php echo (isset($pid) && is_numeric($pid))?'编辑信息':'添加信息';?>
				</button>
				</li>
				</ul>

				<input type="hidden" id="do" name="do" value="" />
				<input type="hidden" id="pid" name="pid" value="<?php echo (isset($pid) && is_numeric($pid))?$pid:'';?>" />
				</form>
			</div>
		</div>
	</div>

</div>


<?php 
$this->load->view('admin/common-js');
$this->load->view('admin/copyright');
$this->load->view('admin/footer');
?>