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
                <ul class="typecho-option-tabs">
					<li<?php if('3d_one' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/threed/manage','主题和副码');?></li>
					<li<?php if('3d_two' == $status): ?> class="current"<?php endif; ?>><a href="<?php echo site_url('admin/threed/manage/3d_two'); ?>">数型与配伍</a></li>
					<li<?php if('add' == $status): ?> class="current"<?php endif; ?>><?php echo anchor('admin/threed/add','添加');?></li>
				</ul>

                <div class="typecho-list-operate">
                <form method="get">
                    <p class="operate">操作: 
                        <span onclick="checkAll('all','pid[]')" class="operate-button">全选</span>, 
                        <span onclick="checkAll('none','pid[]')" class="operate-button">不选</span>&nbsp;&nbsp;&nbsp;
                        选中项: 
                        <span onclick="doAction('delete')" class="operate-button operate-delete">删除</span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						EXCEL:
						<span class="operate-button"><?php echo anchor('admin/threed/excel_write?'.$_SERVER['QUERY_STRING'],'导出');?></span>
                    </p>
                    <p class="search">
                    <input type="text" value="请输入关键字" onclick="value='';name='keywords';" />

					<select name="years">
                    	<option value="0">所有年份</option>
                    	<?php if($years):?>
							<?php foreach($years->result() as $year):?>
								<option value="<?php echo $year->created;?>"><?php echo $year->created;?></option>
							<?php endforeach;?>
						<?php endif;?>
                    </select>

                    <button type="submit">筛选</button>
                    
                    </p>
                </form>
                </div>
            
                <form method="post" name="manage_threed" class="operate-form" action="<?php echo site_url('admin/threed/operate')?>">
                <table class="typecho-list-table">
                    <colgroup>
                        <col width="25"/>
                        <col width="40"/>
						<col width="50"/>
                        <col width="70"/>
						<col width="40"/>
						<col width="60"/>
                        <col width="50"/>
                        <col width="30"/>
                        <col width="30"/>
                        <col width="40"/>
                        <col width="30"/>
						<col width="60"/>
						<col width="50"/>
						<col width="50"/>
						<col width="50"/>
						<col width="50"/>
						<col width="50"/>

						<col width="30"/>
						<col width="50"/>
						<col width="80"/>
						<col width="40"/>
						<col width="40"/>
                    </colgroup>
                    <thead>
                        <tr>
							<th rowspan="2"></th>
                            <th rowspan="2">总期号</th>
                            <th rowspan="2">开奖日期</th>
							<th rowspan="2">年度期号</th>
							<th rowspan="2">开奖日</br>干支 </th>
							<th rowspan="2">农历</th>
							<th rowspan="2">新历</th>
							<th rowspan="2">中式<br>星期</th>
							<th rowspan="2">西式<br>星期</th>
							<th rowspan="2">中奖号码</th>
							<th rowspan="2">开干</br>纳音</th>
                            <th colspan="6">中奖号码结构缩写</th>
							<th colspan="3">相冲资料</th>
							<th rowspan="2">开奖日</br>九宫星</th>
							<th rowspan="2">生肖名</br>年份</th>
                        </tr>
						<tr>
							<th>主题和副码</th>
                            <th>虚主题<br>1*9</th>
							<th>虚主题<br>2*8</th>
							<th>虚主题<br>3*7</th>
							<th>虚主题<br>4*6</th>
							<th>虚主题<br>5*0</th>

							<th>岁数</th>
							<th>纳音</th>
							<th>相冲干支</th>
                        </tr>
                    </thead>
                    <tbody>

						<?php if($threed->num_rows() > 0):?>
						<?php foreach($threed->result() as $three):?>
                        <tr<?php echo ($three->pid % 2==0)?'':' class="even"'; ?> id="<?php echo 'threed-'.$three->pid; ?>">
                            <td><input type="checkbox" value="<?php echo $three->pid; ?>" name="pid[]"/></td>
							<td><?php echo $three->pid;?></td>
							<td><?php echo $three->created;?></td>
							<td><?php echo anchor('admin/threed/add/'.$three->pid,$three->lottery_qh);?></td>
							<td><?php echo $three->lottery_ganzhi;?></td>
							<td><?php echo $three->lunar;?></td>
							<td><?php echo $three->solar;?></td>
							<td><?php echo $three->week;?></td>
							<td><?php echo $x_week[$three->week];?></td>
							<td><?php echo $three->lottery_number;?></td>
							<td><?php echo $three->kgny;?></td>
							<td><?php echo $three->zhuti_fuma;?></td>
							<td><?php if(strpos($three->xuzhuti, '[1.9]') !== FALSE) echo 'ⓧ[1.9]';?></td>
							<td><?php if(strpos($three->xuzhuti, '[2.8]') !== FALSE) echo 'ⓧ[2.8]';?></td>
							<td><?php if(strpos($three->xuzhuti, '[3.7]') !== FALSE) echo 'ⓧ[3.7]';?></td>
							<td><?php if(strpos($three->xuzhuti, '[4.6]') !== FALSE) echo 'ⓧ[4.6]';?></td>
							<td><?php if(strpos($three->xuzhuti, '[5.0]') !== FALSE) echo 'ⓧ[5.0]';?></td>
							<td><?php echo $three->xc_suishu;?></td>
							<td><?php echo $three->xc_nayin;?></td>
							<td><?php echo $three->xc_ganzhi;?></td>
							<td><?php echo $three->jgx;?></td>
							<td><?php echo $three->sx_year;?></td>
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