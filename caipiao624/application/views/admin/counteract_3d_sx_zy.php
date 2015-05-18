<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('admin/header');
$this->load->view('admin/menu');
?>

<div class="main">
    <div class="body body-950">
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

                <?php $this->load->view('admin/search_bar2');?>
            
                <form method="post" name="manage_threed" class="operate-form" action="">
                <table class="typecho-list-table">
                    <colgroup>
						<col width="23"/>
						<col width="45"/>
						<col width="35"/>
                        <col width="18"/>
                        <col width="20"/>
						<col width="25"/>
						<col width="20"/>
						<col width="2"/>
						<col width="20"/>
						<col width="25"/>
						<col width="20"/>
						<col width="2"/>
						<col width="20"/>
						<col width="25"/>
						<col width="20"/>

						<col width="200"/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th colspan="15">生肖名 - 左右斜生克行轨迹对应</th>
                        </tr>
						<tr>
							<th>总期号</th>
							<th>开奖日期</th>
							<th>年度期号</th>
							<th>纳音</th>
                            <th colspan="11">生克刑轨迹对应</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php if($threed->num_rows() > 0):?>
						<?php foreach($threed->result() as $rs):?>
							<?php if (!isset($rs->pid)) continue;  ?>
                        <tr<?php echo ($rs->pid % 2 == 0)?'':' class="even"'; ?> id="<?php echo 'threed-'.$rs->pid; ?>">
							<td><?php echo $rs->pid;?></td>
							<td><?php echo (isset($rs->created)) ? $rs->created : '';?></td>
							<td><?php echo (isset($rs->lottery_qh)) ? $rs->lottery_qh : '' ;?></td>
							<td><?php echo (isset($rs->kgny)) ? $rs->kgny : '';?></td>

							<td><?php echo (isset($rs->num1)) ? $rs->num1.' '.get_shengxiaoma($rs->sx_year,$rs->num1) : '';?></td>
							<td><?php echo (isset($rs->num2)) ? get_sx_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)) ):'';?></td>
							<td><?php echo (isset($rs->num2)) ? $rs->num2.' '.get_shengxiaoma($rs->sx_year,$rs->num2) : '';?></td>
							<td style="background-color:#528B8B;"></td>
							<td><?php echo (isset($rs->num3)) ? $rs->num3.' '.get_shengxiaoma($rs->sx_year,$rs->num3) : '';?></td>
							<td><?php echo (isset($rs->num3) && isset($rs->num4)) ? get_sx_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num4)) ):'';?></td>
							<td><?php echo (isset($rs->num4)) ? $rs->num4.' '.get_shengxiaoma($rs->sx_year,$rs->num4) : '';?></td>
							<td style="background-color:#528B8B;"></td>

							<td><?php echo (isset($rs->num5)) ? $rs->num5.' '.get_shengxiaoma($rs->sx_year,$rs->num5) : '';?></td>
							<td><?php echo (isset($rs->num5) && isset($rs->num6)) ? get_sx_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num5)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num6)) ):'';?></td>
							<td><?php echo (isset($rs->num6)) ? $rs->num6.' '.get_shengxiaoma($rs->sx_year,$rs->num6) : '';?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr class="even">
                        	<td colspan="10"><h6 class="typecho-list-table-title">没有任何数据</h6></td>
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