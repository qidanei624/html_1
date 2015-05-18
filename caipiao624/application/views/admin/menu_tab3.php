<ul class="typecho-option-tabs">
	<li><?php echo anchor('admin/counteract/manage/plus_lunar','等距加法系统 : ');?></li>
	<li<?php if('plus_lunar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/plus_lunar','农历');?></li>
	<li<?php if('plus_jgx' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/plus_jgx','九宫星');?></li>
	<li<?php if('plus_week' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/plus_week','中式星期');?></li>
	<li<?php if('plus_solar' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/plus_solar','新历');?></li>
	<li<?php if('plus_xc_suishu' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/plus_xc_suishu','相岁');?></li>
	<li<?php if('plus_pid' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/plus_pid','总期号');?></li>
	<li<?php if('plus_lottery_qh' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/plus_lottery_qh','年度期号');?></li>
	<li<?php if('plus_pi' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/plus_pi','圆周率');?></li>
	<li<?php if('plus_weihao_vertical' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/plus_weihao_vertical','位号垂直');?></li>
	<li<?php if('plus_weihao_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/plus_weihao_zy','位号左右斜');?></li>
	<li<?php if('plus_weihao_diagonal_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/plus_weihao_diagonal_zy','位号对角线左右斜');?></li>
	
</ul>