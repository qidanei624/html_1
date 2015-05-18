<ul class="typecho-option-tabs">
	<li><?php echo anchor('','测试 : ');?></li>
	<li<?php if('zhuti_fuma' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/zhuti_fuma','主题副码');?></li>
	<li<?php if('zhuti_fuma_2' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract/manage/zhuti_fuma_2','主题副码2');?></li>
	<li<?php if('zhuti_fuma_3' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/zhuti_fuma_3','主题副码(位号右斜)');?></li>
	
</ul>