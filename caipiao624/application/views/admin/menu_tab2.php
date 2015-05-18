<ul class="typecho-option-tabs">
	<li<?php if('weihao_vertical' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/weihao_vertical','位号垂直');?></li>
	<li<?php if('weihao_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/weihao_zy','位号左右斜');?></li>
	<li<?php if('weihao_diagonal_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/weihao_diagonal_zy','位号对角线左右斜');?></li>
	<li<?php if('weihao_order' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/weihao_order','位号同顺序');?></li>
	<li<?php if('weihao_order_diagonal' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/weihao_order_diagonal','位号同顺序对角线');?></li>
	<li<?php if('sx_vertical' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/sx_vertical','生肖垂直');?></li>
	<li<?php if('sx_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/sx_zy','生肖左右斜');?></li>
	<li<?php if('sx_diagonal_zy' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/sx_diagonal_zy','生肖左右斜对角线');?></li>
	<li<?php if('sx_order' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/sx_order','生肖同顺序');?></li>
	<li<?php if('sx_order_diagonal' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/counteract2/manage/sx_order_diagonal','生肖同顺序对角线');?></li>
</ul>