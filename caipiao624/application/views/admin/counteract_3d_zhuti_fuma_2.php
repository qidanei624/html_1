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

                <?php $this->load->view('admin/search_bar');?>
            
                <form method="post" name="manage_threed" class="operate-form" action="">
                <table class="typecho-list-table">
                    <colgroup>
						<col width="30"/>
						<col width="15"/>
						<col width="22"/>
                        <col width="18"/>
						<col width="15"/>
                        <col width="12"/>
						<col width="12"/>
						<col width="25"/>
						<col width="25"/>
						<col width="25"/>
						<col width="25"/>
						<col width="25"/>
						<col width="25"/>

						<col width="50"/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th colspan="13">主题副码2</th>
                        </tr>
						<tr>
							<th>开奖日期</th>
							<th>总期号</th>
							<th>年度期号</th>
							<th>农历</th>
							<th>新历</th>
							<th>相岁</th>
							<th>中奖</br>号码</th>
                            <th>主题副码</th>
							<th>总期号</th>
							<th>年度</br>期号</th>
							<th>农历</th>
							<th>新历</th>
							<th>相岁</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php if($threed->num_rows() > 0):?>
						<?php foreach($threed->result() as $rs):?>
							<?php if (!isset($rs->pid)) continue;  ?>
                        <tr<?php echo ($rs->pid % 2 == 0)?'':' class="even"'; ?> id="<?php echo 'threed-'.$rs->pid; ?>">
							<td><?php echo (isset($rs->created)) ? $rs->created : '';?></td>
							<td><?php echo $rs->pid;?></td>
							<td><?php echo (isset($rs->lottery_qh)) ? $rs->lottery_qh : '' ;?></td>
							<td><?php echo (isset($rs->lunar)) ? $rs->lunar : '';?></td>
							<td><?php echo (isset($rs->solar)) ? $rs->solar : '';?></td>
							<td><?php echo (isset($rs->xc_suishu)) ? $rs->xc_suishu : '';?></td>
							<td><?php echo (isset($rs->lottery_number)) ? $rs->lottery_number : '';?></td>
							<td><?php echo $rs->zhuti_fuma;?></td>
							<td style="text-align:center;"><?php echo get_zf_check2($rs->lottery_number, substr($rs->pid,-1,1));?></td>
							<td style="text-align:center;"><?php echo get_zf_check2($rs->lottery_number, substr($rs->lottery_qh,-1,1));?></td>
							<td style="text-align:center;"><?php echo get_zf_check2($rs->lottery_number, array_search(cut_str($rs->lunar, -1, 1), $lunars));?></td>
							<td style="text-align:center;"><?php echo get_zf_check2($rs->lottery_number, substr($rs->solar,-1,1));?></td>
							<td style="text-align:center;"><?php echo get_zf_check2($rs->lottery_number, substr($rs->xc_suishu,-1,1));?></td>
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