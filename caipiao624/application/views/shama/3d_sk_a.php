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
                <?php $this->load->view('shama/menu_tab_inc');?>

                <?php $this->load->view('shama/search_bar');?>
            
                <form method="post" name="manage_threed" class="operate-form" action="">
                <table class="typecho-list-table">
                    <colgroup>
						<col width="50"/>
                        <col width="30"/>
						<col width="20"/>

						<col width="20"/>
                        <col width="11"/>
                        <col width="11"/>
                        <col width="20"/>
                        <col width="11"/>
                        <col width="11"/>
						<col width="20"/>
                        <col width="11"/>
                        <col width="11"/>
						<col width="20"/>
                        <col width="11"/>
                        <col width="11"/>
						<col width="20"/>
                        <col width="11"/>
                        <col width="11"/>
						

						<col width="140"/>


                    </colgroup>
                    <thead>
                        <tr>
                            <th rowspan="2">开奖日期</th>
							<th rowspan="2">年度期号</th>
							<th rowspan="2">中奖<br>号码</th>
                            <th colspan="10">【<?php echo $qh[$qh_filter];?>】【<?php echo $position[$position_filter];?>】*【<?php echo $multiply_filter;?>】+ plus 与【<?php echo $position[$position_filter];?>】生克</th>
                        </tr>
						<tr>
							<th colspan="3">*<?php echo $multiply_filter;?> + 0</th>
                            <th colspan="3">*<?php echo $multiply_filter;?> + 1</th>
							<th colspan="3">*<?php echo $multiply_filter;?> + 2</th>
							<th colspan="3">*<?php echo $multiply_filter;?> + 3</th>
							<th colspan="3">*<?php echo $multiply_filter;?> + 4</th>
                        </tr>
                    </thead>
                    <tbody>

						<?php if($threed->num_rows() > 0):?>
						<?php
						$cnt0 = 0;$cnt1 = 0;$cnt2 = 0;$cnt3 = 0;$cnt4 = 0;
						$cnt5 = 0;$cnt6 = 0;$cnt7 = 0;$cnt8 = 0;$cnt9 = 0;
						$created_s = '';$created_e = '';
						?>
						<?php foreach($threed->result() as $k=>$rs):?>
                        <tr<?php echo ($rs->pid % 2==0)?'':' class="even"'; ?> id="<?php echo 'threed-'.$rs->pid; ?>">
							<?php
							if ($k == 0) $created_s = $rs->created; // 开始时间
							$created_e = $rs->created; // 结束时间
							?>
							<td><?php echo $rs->created;?></td>
							<td><?php echo $rs->lottery_qh_old;?></td>
							<td	style="text-align:center"><?php echo $rs->lottery_number_old;?></td>
							<?php $sm = get_sk_a($rs->lottery_number_old, $rs->lottery_number_new, $multiply_filter,0,$position_filter);?>
							<td style="text-align:center"><?php echo (isset($sm['sk'])) ? $sm['sk'] : ''?></td>
							<td style="text-align:center"><?php echo $sm['num']?></td>
							<td style="text-align:center"><?php echo $sm['chk']?></td>
							<?php if ($sm['error'])  $cnt0 = $cnt0 + 1 ;?>

							<?php $sm = get_sk_a($rs->lottery_number_old, $rs->lottery_number_new, $multiply_filter,1,$position_filter);?>
							<td style="text-align:center"><?php echo (isset($sm['sk'])) ? $sm['sk'] : ''?></td>
							<td style="text-align:center"><?php echo $sm['num']?></td>
							<td style="text-align:center"><?php echo $sm['chk']?></td>
							<?php if ($sm['error'])  $cnt1 = $cnt1 + 1 ;?>

							<?php $sm = get_sk_a($rs->lottery_number_old, $rs->lottery_number_new, $multiply_filter,2,$position_filter);?>
							<td style="text-align:center"><?php echo (isset($sm['sk'])) ? $sm['sk'] : ''?></td>
							<td style="text-align:center"><?php echo $sm['num']?></td>
							<td style="text-align:center"><?php echo $sm['chk']?></td>
							<?php if ($sm['error'])  $cnt2 = $cnt2 + 1 ;?>

							<?php $sm = get_sk_a($rs->lottery_number_old, $rs->lottery_number_new, $multiply_filter,3,$position_filter);?>
							<td style="text-align:center"><?php echo (isset($sm['sk'])) ? $sm['sk'] : ''?></td>
							<td style="text-align:center"><?php echo $sm['num']?></td>
							<td style="text-align:center"><?php echo $sm['chk']?></td>
							<?php if ($sm['error'])  $cnt3 = $cnt3 + 1 ;?>

							<?php $sm = get_sk_a($rs->lottery_number_old, $rs->lottery_number_new, $multiply_filter,4,$position_filter);?>
							<td style="text-align:center"><?php echo (isset($sm['sk'])) ? $sm['sk'] : ''?></td>
							<td style="text-align:center"><?php echo $sm['num']?></td>
							<td style="text-align:center"><?php echo $sm['chk']?></td>
							<?php if ($sm['error'])  $cnt4 = $cnt4 + 1 ;?>

                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr class="even">
                        	<td colspan="17"><h6 class="typecho-list-table-title">没有任何文章</h6></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                

				<table class="typecho-list-table">
					<colgroup>
						<col width="80"/>
						<col width="20"/>
						<col width="42"/>
                        <col width="42"/>
                        <col width="42"/>
                        <col width="42"/>
						<col width="42"/>

						<col width="140"/>
                    </colgroup>
					<thead>
                        <tr>
							<th rowspan="2">期 限</th>
							<th rowspan="2">天</th>
                            <th colspan="5">【<?php echo $qh[$qh_filter];?>】【<?php echo $position[$position_filter];?>】 * 【<?php echo $multiply_filter;?>】 + plus ==【<?php echo $position[$position_filter];?>】的统计次数</th>
                        </tr>
						<tr>
							<th>*<?php echo $multiply_filter;?> + 0</th>
                            <th>*<?php echo $multiply_filter;?> + 1</th>
							<th>*<?php echo $multiply_filter;?> + 2</th>
							<th>*<?php echo $multiply_filter;?> + 3</th>
							<th>*<?php echo $multiply_filter;?> + 4</th>
                        </tr>
                    </thead>
					<tbody>
						<tr>
							<td style="text-align:center"><?php echo $created_s.' ~ '.$created_e;?></td>
							<td style="text-align:center"><?php echo $threed->num_rows();?></td>
                        	<td style="text-align:center"><?php echo $cnt0;?></td>
							<td style="text-align:center"><?php echo $cnt1;?></td>
							<td style="text-align:center"><?php echo $cnt2;?></td>
							<td style="text-align:center"><?php echo $cnt3;?></td>
							<td style="text-align:center"><?php echo $cnt4;?></td>
                        </tr>
					</tbody>
				</table>
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