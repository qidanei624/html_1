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

                <?php $this->load->view('admin/search_bar');?>
            
                <form method="post" name="manage_threed" class="operate-form" action="">
                <table class="typecho-list-table">
                    <colgroup>
						<col width="22"/>
						<col width="45"/>
                        <col width="35"/>
						<col width="18"/>
						<col width="30"/>
						<col width="10"/>
						<col width="30"/>
						<col width="10"/>
						<col width="30"/>
						<col width="10"/>
						<col width="30"/>
						<col width="30"/>
						<col width="10"/>
						<col width="30"/>
						<col width="10"/>
						<col width="30"/>
						<col width="10"/>

						<col width="30"/>
						<col width="30"/>
						<col width="10"/>
						<col width="30"/>
						<col width="10"/>
						<col width="30"/>
						<col width="10"/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th colspan="3"></th>
							<th colspan="7">开干纳音</th>
							<th colspan="7">正相冲纳音</th>
							<th colspan="7">次相冲纳音</th>
                        </tr>
						<tr>
							<th>总期号</th>
							<th>开奖日期</th>
							<th>年度期号</th>
							<th>纳音</th>
                            <th colspan="6">生-克-刑 轨迹对应</th>
							<th>正冲纳音</th>
							<th colspan="6">生-克-刑 轨迹对应</th>
							<th>次冲纳音</th>
							<th colspan="6">生-克-刑 轨迹对应</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php if($threed->num_rows() > 0):?>
						<?php foreach($threed->result() as $rs):?>
							<?php if (!isset($rs->pid)) continue;  ?>
                        <tr<?php echo ($rs->pid % 2 == 0)?'':' class="even"'; ?> id="<?php echo 'threed-'.$rs->pid; ?>">
							<td><?php echo $rs->pid;?></td>
							<td><?php echo $rs->created;?></td>
							<td><?php echo $rs->lottery_qh;?></td>
							<td><?php echo $rs->kgny;?></td>
							<td><?php echo (isset($rs->kgny) && isset($rs->num1)) ? get_nayin_counteract($rs->kgny, $rs->num1) : '';?></td>
							<td><?php echo (isset($rs->num1)) ? $rs->num1 : '';?></td>
							<td><?php echo (isset($rs->kgny) && isset($rs->num2)) ? get_nayin_counteract($rs->kgny, $rs->num2) : '';?></td>
							<td><?php echo (isset($rs->num2)) ? $rs->num2 : '';?></td>
							<td><?php echo (isset($rs->kgny) && isset($rs->num3)) ? get_nayin_counteract($rs->kgny, $rs->num3) : '';?></td>
							<td><?php echo (isset($rs->num3)) ? $rs->num3 : '';?></td>

							<td><?php echo (isset($rs->xc_nayin_z)) ? $rs->xc_nayin_z : '';?></td>
							<td><?php echo (isset($rs->num1) && isset($rs->xc_nayin_z)) ? get_nayin_counteract($rs->xc_nayin_z, $rs->num1) : '';?></td>
							<td><?php echo (isset($rs->num1)) ? $rs->num1 : '';?></td>
							<td><?php echo (isset($rs->num2) && isset($rs->xc_nayin_z)) ? get_nayin_counteract($rs->xc_nayin_z, $rs->num2) : '';?></td>
							<td><?php echo (isset($rs->num2)) ? $rs->num2 : '';?></td>
							<td><?php echo (isset($rs->num3) && isset($rs->xc_nayin_z)) ? get_nayin_counteract($rs->xc_nayin_z, $rs->num3) : '';?></td>
							<td><?php echo (isset($rs->num3)) ? $rs->num3 : '';?></td>

							<td><?php echo (isset($rs->xc_nayin_c)) ? $rs->xc_nayin_c : '';?></td>
							<td><?php echo (isset($rs->num1) && isset($rs->xc_nayin_c)) ? get_nayin_counteract($rs->xc_nayin_c, $rs->num1) : '';?></td>
							<td><?php echo (isset($rs->num1)) ? $rs->num1 : '';?></td>
							<td><?php echo (isset($rs->num2) && isset($rs->xc_nayin_c)) ? get_nayin_counteract($rs->xc_nayin_c, $rs->num2) : '';?></td>
							<td><?php echo (isset($rs->num2)) ? $rs->num2 : '';?></td>
							<td><?php echo (isset($rs->num3) && isset($rs->xc_nayin_c)) ? get_nayin_counteract($rs->xc_nayin_c, $rs->num3) : '';?></td>
							<td><?php echo (isset($rs->num3)) ? $rs->num3 : '';?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr class="even">
                        	<td colspan="10"><h6 class="typecho-list-table-title">没有任何文章</h6></td>
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
		//$this->benchmark->mark('code_end');
		//echo $this->benchmark->elapsed_time('code_start', 'code_end');
$this->load->view('admin/common-js');
$this->load->view('admin/copyright');
$this->load->view('admin/footer');
?>