<ul class="typecho-option-tabs">
	<li><?php echo anchor('admin/counteract/manage/minus_lunar','等距减法系统 : ');?></li>
	<li<?php if('minus_lunar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/minus_lunar','农历');?></li>
	<li<?php if('minus_jgx' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/minus_jgx','九宫星');?></li>
	<li<?php if('minus_week' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/minus_week','中式星期');?></li>
	<li<?php if('minus_solar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/minus_solar','新历');?></li>
	<li<?php if('minus_xc_suishu' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/minus_xc_suishu','相岁');?></li>
	<li<?php if('minus_pid' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/minus_pid','总期号');?></li>
	<li<?php if('minus_lottery_qh' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/minus_lottery_qh','年度期号');?></li>
	<li<?php if('minus_pi' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/minus_pi','圆周率');?></li>
	<li<?php if('minus_weihao_vertical' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/minus_weihao_vertical','位号垂直');?></li>
	<li<?php if('minus_weihao_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/minus_weihao_zy','位号左右斜');?></li>
	<li<?php if('minus_weihao_diagonal_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/minus_weihao_diagonal_zy','位号对角线左右斜');?></li>
</ul>