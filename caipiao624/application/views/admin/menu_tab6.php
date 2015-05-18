<ul class="typecho-option-tabs">
	<li><?php echo anchor('admin/counteract/manage/abs_lunar','等距绝对值系统 : ');?></li>
	<li<?php if('abs_lunar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/abs_lunar','农历');?></li>
	<li<?php if('abs_jgx' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/abs_jgx','九宫星');?></li>
	<li<?php if('abs_week' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/abs_week','中式星期');?></li>
	<li<?php if('abs_solar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/abs_solar','新历');?></li>
	<li<?php if('abs_xc_suishu' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/abs_xc_suishu','相岁');?></li>
	<li<?php if('abs_pid' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/abs_pid','总期号');?></li>
	<li<?php if('abs_lottery_qh' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/abs_lottery_qh','年度期号');?></li>
	<li<?php if('abs_pi' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/abs_pi','圆周率');?></li>
	<li<?php if('abs_weihao_vertical' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/abs_weihao_vertical','位号垂直');?></li>
	<li<?php if('abs_weihao_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/abs_weihao_zy','位号左右斜');?></li>
	<li<?php if('abs_weihao_diagonal_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/abs_weihao_diagonal_zy','位号对角线左右斜');?></li>
</ul>