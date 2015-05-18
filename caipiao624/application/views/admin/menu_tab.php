<ul class="typecho-option-tabs">
	<li<?php if('tiangan' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/tiangan','天干');?></li>
	<li<?php if('dizhi' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/dizhi','地支');?></li>
	<li<?php if('nayin' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/nayin','纳音');?></li>
	<li<?php if('sx_dizhi' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/sx_dizhi','生肖地支');?></li>
	<li<?php if('sx_tiangan' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/sx_tiangan','生肖天干');?></li>
	<li<?php if('sx_nayin' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/sx_nayin','生肖纳音');?></li>
	<li<?php if('x_week' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/x_week','西式星期');?></li>
	<li<?php if('jgx' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/jgx','九宫星');?></li>
	<li<?php if('week' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/week','中式星期');?></li>
	<li<?php if('lunar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/lunar','农历');?></li>
	<li<?php if('solar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/solar','新历');?></li>
	<li<?php if('suishu' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/suishu','相岁');?></li>
	<li<?php if('rownum' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage','总期号');?></li>
	<li<?php if('lottery_qh' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/lottery_qh','年度期号');?></li>
	<li<?php if('pi' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/pi','圆周率');?></li>
</ul>