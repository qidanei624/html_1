<ul class="typecho-option-tabs">
	<li><?php echo anchor('admin/counteract/manage/multiply_lunar','等距乘法系统 : ');?></li>
	<li<?php if('multiply_lunar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/multiply_lunar','农历');?></li>
	<li<?php if('multiply_jgx' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/multiply_jgx','九宫星');?></li>
	<li<?php if('multiply_week' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/multiply_week','中式星期');?></li>
	<li<?php if('multiply_solar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/multiply_solar','新历');?></li>
	<li<?php if('multiply_xc_suishu' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/multiply_xc_suishu','相岁');?></li>
	<li<?php if('multiply_pid' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/multiply_pid','总期号');?></li>
	<li<?php if('multiply_lottery_qh' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/multiply_lottery_qh','年度期号');?></li>
	<li<?php if('multiply_pi' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/multiply_pi','圆周率');?></li>
	<li<?php if('multiply_weihao_vertical' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/multiply_weihao_vertical','位号垂直');?></li>
	<li<?php if('multiply_weihao_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/multiply_weihao_zy','位号左右斜');?></li>
	<li<?php if('multiply_weihao_diagonal_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/multiply_weihao_diagonal_zy','位号对角线左右斜');?></li>
</ul>