<div class="typecho-list-operate">
<form method="get">
	<p class="operate">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EXCEL: &nbsp;
		<span class="operate-button"><?php echo anchor("admin/counteract2/manage/$status/excel_write?".$_SERVER['QUERY_STRING'],'导出');?></span>
	</p>
	<p class="operate">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;操作: &nbsp;
		<span class="operate-button"><?php echo anchor("admin/counteract2/manage/$status?airt=left_x&".$_SERVER['QUERY_STRING'],'左斜');?></span>&nbsp;
		<span class="operate-button"><?php echo anchor("admin/counteract2/manage/$status?airt=right_x&".$_SERVER['QUERY_STRING'],'右斜');?></span>
	</p>
	<p class="search">
	<input type="text" value="请输入关键字" onclick="value='';name='keywords';" />

	<select name="years">
		<option value="0">全部年份</option>
		<?php if($years):?>
			<?php foreach($years->result() as $year):?>
				<option value="<?php echo $year->created;?>"><?php echo $year->created;?></option>
			<?php endforeach;?>
		<?php endif;?>
	</select>

	<select name="weishu_col">
		<option value="">列</option>
		<?php if($weishu_col):?>
			<?php foreach($weishu_col as $v):?>
				<option value="<?php echo $v;?>"><?php echo $v;?></option>
			<?php endforeach;?>
		<?php endif;?>
	</select>
	<select name="weishu">
		<option value="">列尾数</option>
		<?php if($nayins):?>
			<?php for($i=0; $i<10; $i++):?>
				<option value="<?php echo $i;?>"><?php echo $i;?></option>
			<?php endfor;?>
		<?php endif;?>
	</select>
	<select name="zhutis">
		<option value="0">列主题</option>
		<?php if($zhutis):?>
			<?php foreach($zhutis as $zhuti):?>
				<option value="<?php echo $zhuti;?>"><?php echo $zhuti;?></option>
			<?php endforeach;?>
		<?php endif;?>
	</select>
	<select name="nayins">
		<option value="0">全部纳音</option>
		<?php if($nayins):?>
			<?php foreach($nayins as $nayin):?>
				<option value="<?php echo $nayin;?>"><?php echo $nayin;?></option>
			<?php endforeach;?>
		<?php endif;?>
	</select>
	
	<select name="gehangs">
		<option value="0">隔行提取</option>
		<?php if($gehangs):?>
			<?php foreach($gehangs as $gehang):?>
				<option value="<?php echo $gehang;?>"><?php echo $gehang;?></option>
			<?php endforeach;?>
		<?php endif;?>
	</select>
	<select name="huans">
		<option value="0">环环提取</option>
		<?php if($huans):?>
			<?php foreach($huans as $huan):?>
				<option value="<?php echo $huan;?>"><?php echo $huan;?></option>
			<?php endforeach;?>
		<?php endif;?>
	</select>

	<button type="submit">筛选</button>
	
	</p>
</form>
</div>