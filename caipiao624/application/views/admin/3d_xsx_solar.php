<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
$this->load->view('admin/menu');
?>
<div class="main">
    <div class="body body-1300">
	<?php $this->load->view('admin/notify'); ?>
        <div class="container typecho-page-title">
			<div class="column-24 start-01">
				<h2><?php echo $page_title;?></h2>
				<p><?php echo anchor(site_url(),'查看我的站点');?></p>
			</div>
		</div>
        <div class="container typecho-page-main">
            <div class="column-24 start-01 typecho-list">
                <?php $this->load->view('admin/menu_tab_inc');?>

                <?php $this->load->view('admin/search_bar3');?>
            
                <form method="post" name="manage_threed" class="operate-form" action="">
                <table class="typecho-list-table">
                    <colgroup>
						<col width="60"/>
                        <col width="45"/>
						<col width="20"/>
						<col width="30"/>
                        <col width="30"/>
                        <col width="20"/>
                        <col width="40"/>

						<col width="35"/>
						<col width="20"/>
						<col width="35"/>
						<col width="20"/>
						<col width="35"/>
						<col width="20"/>
						<col width="35"/>
						<col width="20"/>
						<col width="35"/>
						<col width="20"/>

						<col width="150"/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th rowspan="2">开奖日期</th>
							<th rowspan="2">年度期号</th>
							<th rowspan="2">纳音</th>
							<th rowspan="2">中奖<br>号码</th>
							<th rowspan="2">数型</th>
							<th rowspan="2">配伍</th>
							<th rowspan="2">新历</th>
                            <th colspan="10">虚数型</th>
                        </tr>
						<tr>
							<th colspan="2">0区</th>
                            <th colspan="2">1区</th>
							<th colspan="2">2区</th>
							<th colspan="2">3区</th>
							<th colspan="2">4区</th>
                        </tr>
                    </thead>
                    <tbody>

						<?php if($threed->num_rows() > 0):?>
						<?php foreach($threed->result() as $rs):?>
                        <tr<?php echo ($rs->pid % 2==0)?'':' class="even"'; ?> id="<?php echo 'threed-'.$rs->pid; ?>">
							<td><?php echo $rs->created;?></td>
							<td><?php echo $rs->lottery_qh;?></td>
							<td><?php echo $rs->kgny;?></td>
							<td><?php echo $rs->lottery_number;?></td>
							<?php $shuxing = get_shuxing($rs->lottery_number);?>
							<?php $xu_shuxing = (isset($rs->xu_shuxing)) ? $rs->xu_shuxing : get_xuShuXing($rs->lottery_number);?>
							<td><?php echo $shuxing['sx'];?></td>
							<td><?php echo $shuxing['sum'];?></td>
							<td><?php echo $rs->solar;?></td>
							<td><?php echo (isset($rs->solar)) ? get_prefix_nums($rs->solar, (in_array(0, $xu_shuxing)) ? 0 : '') : '';?></td>
							<td><?php echo (in_array(0, $xu_shuxing)) ? 'ⓧ0' : '';?></td>
							<td><?php echo (isset($rs->solar)) ? get_prefix_nums($rs->solar, (in_array(1, $xu_shuxing)) ? 1 : '') : '';?></td>
							<td><?php echo (in_array(1, $xu_shuxing)) ? 'ⓧ1' : '';?></td>
							<td><?php echo (isset($rs->solar)) ? get_prefix_nums($rs->solar, (in_array(2, $xu_shuxing)) ? 2 : '') : '';?></td>
							<td><?php echo (in_array(2, $xu_shuxing)) ? 'ⓧ2' : '';?></td>
							<td><?php echo (isset($rs->solar)) ? get_prefix_nums($rs->solar, (in_array(3, $xu_shuxing)) ? 3 : '') : '';?></td>
							<td><?php echo (in_array(3, $xu_shuxing)) ? 'ⓧ3' : '';?></td>
							<td><?php echo (isset($rs->solar)) ? get_prefix_nums($rs->solar, (in_array(4, $xu_shuxing)) ? 4 : '') : '';?></td>
							<td><?php echo (in_array(4, $xu_shuxing)) ? 'ⓧ4' : '';?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr class="even">
                        	<td colspan="17"><h6 class="typecho-list-table-title">没有任何文章</h6></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <input type="hidden" id="do" name="do" value="delete" />
                </form>
            
				<?php echo isset($pagination)?$pagination:''; ?>
                   
                
            

            
            </div>
        </div>
    </div>
</div>
<?php 
$this->load->view('admin/common-js');
$this->load->view('admin/copyright');
$this->load->view('admin/footer');
?>