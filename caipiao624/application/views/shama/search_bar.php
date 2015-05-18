<div class="typecho-list-operate">
<form method="get">
	<p class="operate">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EXCEL: &nbsp;
		<span class="operate-button"><?php echo anchor("admin/counteract4/manage/$status/excel_write?".$_SERVER['QUERY_STRING'],'导出');?></span>
	</p>

	<p class="search">
	<select name="position">
		<option value="">百.十.个</option>
		<?php if($position):?>
			<?php foreach($position as $k=>$v):?>
				<option value="<?php echo $k;?>"><?php echo $v;?></option>
			<?php endforeach;?>
		<?php endif;?>
	</select>
	<select name="multiply">
		<option value="">乘</option>
		<?php for($i=1; $i<10; $i++):?>
			<option value="<?php echo $i;?>"><?php echo $i;?></option>
		<?php endfor;?>
	</select>
	<select name="qh">
		<option value="">跨度期号</option>
		<?php if($qh):?>
			<?php foreach($qh as $k=>$v):?>
				<option value="<?php echo $k;?>"><?php echo $v;?></option>
			<?php endforeach;?>
		<?php endif;?>
	</select>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<select name="years_s">
		<option value="">开始年份</option>
		<?php if($years):?>
			<?php foreach($years->result() as $year):?>
				<option value="<?php echo $year->created;?>"><?php echo $year->created;?></option>
			<?php endforeach;?>
		<?php endif;?>
	</select>
	<select name="month_s">
		<option value="">开始月份</option>
		<?php for($i=1; $i<13; $i++):?>
			<option value="<?php echo $i;?>"><?php echo $i;?></option>
		<?php endfor;?>
	</select>
	~
	<select name="years_e">
		<option value="">结束年份</option>
		<?php if($years):?>
			<?php foreach($years->result() as $year):?>
				<option value="<?php echo $year->created;?>"><?php echo $year->created;?></option>
			<?php endforeach;?>
		<?php endif;?>
	</select>
	<select name="month_e">
		<option value="">结束月份</option>
		<?php for($i=1; $i<13; $i++):?>
			<option value="<?php echo $i;?>"><?php echo $i;?></option>
		<?php endfor;?>
	</select>
	&nbsp;&nbsp;&nbsp;&nbsp;

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

	<button type="submit">筛选</button>
	
	</p>


	


</form>
</div>