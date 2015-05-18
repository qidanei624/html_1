<ul class="typecho-option-tabs">
	<li><?php echo anchor('','虚数型 : ');?></li>
	<li<?php if('xsx_tiangan' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract3/manage/xsx_tiangan','天干');?></li>
	<li<?php if('xsx_dizhi' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract3/manage/xsx_dizhi','地支');?></li>
	<li<?php if('xsx_nayin' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract3/manage/xsx_nayin','纳音');?></li>
	<li<?php if('xsx_jgx' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract3/manage/xsx_jgx','九宫星');?></li>
	<li<?php if('xsx_x_week' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract3/manage/xsx_x_week','西式星期');?></li>
	<li<?php if('xsx_lunar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract3/manage/xsx_lunar','农历');?></li>
	<li<?php if('xsx_solar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract3/manage/xsx_solar','新历');?></li>
	<li<?php if('xsx_xc_suishu' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract3/manage/xsx_xc_suishu','相岁');?></li>
	<li<?php if('xsx_pid' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract3/manage/xsx_pid','总期号');?></li>
	
</ul>