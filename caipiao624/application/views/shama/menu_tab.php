<ul class="typecho-option-tabs">
	<li><a href="javascript:void(0)">杀 码：</a></li>
	<li<?php if('shama_a' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract4/manage/shama_a','杀码_A');?></li>
	<li<?php if('shama_b' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract4/manage/shama_b','杀码_B');?></li>
	<li<?php if('shama_c' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract4/manage/shama_c','杀码_C');?></li>
	<li<?php if('sk_a' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract4/manage/sk_a','生克_A');?></li>
	
</ul>