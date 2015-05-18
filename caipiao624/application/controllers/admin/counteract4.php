<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * STBLOG Posts Controller Class
 *
 * 相生相克表
 *
 */
class Counteract4 extends ST_Auth_Controller
{
	// 传递到对应视图的数据
	private $_data = array();
	// 尾数
	private $weishu_col = array('总期号','年度期号','岁数','农历','新历','九宫星','中式星期');
	// 纳音
	private $nayins = array('火', '水', '木', '金', '土');
	// 主题
	private $zhutis = array(19,28,37,46,50);
	// 隔行提取
	private $gehangs = array(2,3,4,5,6,7,8,9,10,11,12);
	// 环环提取
	private $huans = array(2,3,4,5,6,7,8,9,10,11,12);
	/**  西式星期数组 */
	private $x_week = array('一'=>'月','二'=>'火','三'=>'水','四'=>'木','五'=>'金','六'=>'土','日'=>'日');
	/**  农历数组 */
	private $lunars = array('十','一','二','三','四','五','六','七','八','九');

	/** 百.十.个 **/
	private $position = array('B'=>'百位','S'=>'十位','G'=>'个位');
	/** 选择跨度期号 **/
	private $qh = array(0=>'本期',1=>'上期',2=>'隔一期',3=>'隔二期');

	private $qh_filter = '';
	private $multiply_filter = '';
	private $position_filter = '';

	// 构造函数
	public function __construct()
	{
		parent::__construct();
		
		/** 权限确认 */
		//$this->auth->exceed('contributor');
		
		/** 导航栏和标题 */
		$this->_data['parentPage'] = 'post';
		$this->_data['currentPage'] = 'post';
		//$this->_data['page_title'] = '';
		
		/** 导入杀码补助函数 */
		$this->load->helper('my_shama');
	}

	/**
     * 入口
     *
     * @access public
     * @return void
     */
	public function manage($status = 'shama_a')
	{
		/** 默认标题 */
		$this->_data['page_title'] = '管理文章';
		/** 已发布，待审核 状态表示变量 **/
		$this->_data['status'] = $status;
		/** 分页的query string */
		$query = array();


		/** check status */
		if(!in_array($status, array('shama_a','shama_b','shama_c','sk_a')))
		{
			redirect('admin/counteract4/manage');
		}
		/** 百.十.个 **/
		$this->position_filter = $this->input->get('position', TRUE);
		$this->position_filter = (!empty($this->position_filter)) ? $this->position_filter : 'B';
		if(!empty($this->position_filter))
		{
			$query[] = 'position='.$this->position_filter;
		}
		/** 乘 **/
		$this->multiply_filter = $this->input->get('multiply', TRUE);
		$this->multiply_filter = (!empty($this->multiply_filter)) ? intval($this->multiply_filter) : 1;
		if(!empty($this->multiply_filter))
		{
			$query[] = 'multiply='.$this->multiply_filter;
		}
		/** 跨度期号 **/
		$this->qh_filter = $this->input->get('qh', TRUE);
		if(!empty($this->qh_filter)) {
			$this->qh_filter = intval($this->qh_filter);
		}else{
			if($status == 'shama_b') {
				$this->qh_filter = 0; // 用期号杀号时，默认值设为本期
			}else{
				$this->qh_filter = 1;
			}
		}
		if(!empty($this->qh_filter))
		{
			$query[] = 'qh='.$this->qh_filter;
		}

		/** 开始年份 filter */
		$years_s_filter = $this->input->get('years_s', TRUE);
		$years_s_filter = (!empty($years_s_filter)) ? intval($years_s_filter) : 0;
		if(!empty($years_s_filter))
		{
			$query[] = 'years_s='.$years_s_filter;
		}
		/** 结束年份 filter */
		$years_e_filter = $this->input->get('years_e', TRUE);
		$years_e_filter = (!empty($years_e_filter)) ? intval($years_e_filter) : 0;
		if(!empty($years_e_filter))
		{
			$query[] = 'years_e='.$years_e_filter;
		}
		/** 开始月份 filter */
		$month_s_filter = $this->input->get('month_s', TRUE);
		$month_s_filter = (!empty($month_s_filter)) ? intval($month_s_filter) : 0;
		if(!empty($month_s_filter))
		{
			$query[] = 'month_s='.$month_s_filter;
		}
		/** 结束月份 filter */
		$month_e_filter = $this->input->get('month_e', TRUE);
		$month_e_filter = (!empty($month_e_filter)) ? intval($month_e_filter) : 0;
		if(!empty($month_e_filter))
		{
			$query[] = 'month_e='.$month_e_filter;
		}




		/** 选择纳音 */
		$nayins_filter = $this->input->get('nayins', TRUE);
		$nayins_filter = (!empty($nayins_filter)) ? $nayins_filter : 0;
		if(!empty($nayins_filter)) 
		{
			$query[] = 'nayins='.$nayins_filter;
		}

		/** 隔行提取 */
		$gehangs_filter = $this->input->get('gehangs', TRUE);
		$gehangs_filter = intval($gehangs_filter);
		if(!empty($gehangs_filter)) 
		{
			$query[] = 'gehangs='.$gehangs_filter;
		}

		/** pagination stff */
		$page = $this->input->get('p',TRUE);
		$page = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
		$limit = 1400000;
		$offset = ($page - 1) * $limit;
		//$cnt = 1002 + $offset; // 总期号

		if($offset < 0)
		{
			redirect('admin/counteract4/manage');
		}
		
		// 隔行查询 (隔行提取和环环提取 不要同时进行！)
		if(!empty($gehangs_filter)) 
		{
			$pids = $this->shama_mdl->get_pids();
			$pids = $pids->result_array();
			//$pids = array_reverse($pids);
			$pid_str = '';

			foreach($pids as $idx => $pid)
			{
				if($idx%$gehangs_filter == 0) 
				{
					//echo $idx.'---'.$pid['pid'].'<br>';
					$pid_str .= $pid['pid'].',';
				}
			}
			$pid_str = rtrim($pid_str, ',');

			$result = $this->shama_mdl->get_gehang_result($pid_str,$limit, $offset, '',$years_s_filter,$years_e_filter,$month_s_filter,$month_e_filter,$nayins_filter);
			$result_count = $this->shama_mdl->get_gehang_result($pid_str,'', '', '',$years_s_filter,$years_e_filter,$month_s_filter,$month_e_filter,$nayins_filter)->num_rows();
		}
		else // 默认
		{
			$result = $this->shama_mdl->get_threed($limit, $offset, '',$years_s_filter,$years_e_filter,$month_s_filter,$month_e_filter,$nayins_filter);

			if($status == "shama_a") {
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{
					$values[$i]->pid					= $rs[$i]->pid;
					$values[$i]->created				= $rs[$i]->created;
					$values[$i]->lottery_qh_old			= $rs[$i]->lottery_qh;
					$values[$i]->lottery_number_old		= $rs[$i]->lottery_number;
					$values[$i]->lottery_number_new		= (isset($rs[$i-(int)$this->qh_filter]->lottery_number)) ? $rs[$i-(int)$this->qh_filter]->lottery_number : '';
				}
				$result->result_object = $values;
				unset($values);
			}
			elseif($status == "shama_b" ) {
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{
					$values[$i]->pid					= $rs[$i]->pid;
					$values[$i]->created				= $rs[$i]->created;
					$values[$i]->lottery_qh_old			= $rs[$i]->lottery_qh;
					$values[$i]->lottery_qh_new			= (isset($rs[$i-(int)$this->qh_filter]->lottery_qh)) ? $rs[$i-(int)$this->qh_filter]->lottery_qh : '';
					$values[$i]->lottery_number_old		= $rs[$i]->lottery_number;
				}
				$result->result_object = $values;
				unset($values);
			}
			elseif($status == "shama_c" ) {
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{
					$values[$i]->pid					= $rs[$i]->pid;
					$values[$i]->created				= $rs[$i]->created;
					$values[$i]->lottery_qh_old			= $rs[$i]->lottery_qh;
					$values[$i]->lottery_qh_new			= (isset($rs[$i-(int)$this->qh_filter]->lottery_qh)) ? $rs[$i-(int)$this->qh_filter]->lottery_qh : '';
					$values[$i]->lottery_number_old		= $rs[$i]->lottery_number;
					$lottery_number_new					= (isset($rs[$i-(int)$this->qh_filter]->lottery_number)) ? $rs[$i-(int)$this->qh_filter]->lottery_number : '';

					$num_b = (int)substr($lottery_number_new, 0, 1);
					$num_s = (int)substr($lottery_number_new, 1, 1);
					$num_g = (int)substr($lottery_number_new, -1);

					

					
					$values[$i]->out_num = substr(($num_b + $num_s + $num_g),-1);
					
				}
				$result->result_object = $values;
				unset($values);
			}
			elseif($status == "sk_a") {
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{
					$values[$i]->pid					= $rs[$i]->pid;
					$values[$i]->created				= $rs[$i]->created;
					$values[$i]->lottery_qh_old			= $rs[$i]->lottery_qh;
					$values[$i]->lottery_number_old		= $rs[$i]->lottery_number;
					$values[$i]->lottery_number_new		= (isset($rs[$i+(int)$this->qh_filter]->lottery_number)) ? $rs[$i+(int)$this->qh_filter]->lottery_number : '';
				}
				$result->result_object = $values;
				unset($values);
			}

			$result_count = $this->shama_mdl->get_threed(20000,0,'',$years_s_filter,$years_e_filter,$month_s_filter,$month_e_filter,$nayins_filter)->num_rows();
		}
		
		if($result)
		{
			$pagination = '';
			// 选择年份时，为了便于统计，所以取消了分页效果
			if($result_count > $limit AND $years_s_filter == 0)
			{	
				if ($status == 'shama_a') $page_status = 'shama_a';
				elseif ($status == 'shama_b') $page_status = 'shama_b';
				elseif ($status == 'shama_c') $page_status = 'shama_c';
				elseif ($status == 'sk_a') $page_status = 'sk_a';
				

				$this->dpagination->currentPage($page);
				$this->dpagination->items($result_count);
				$this->dpagination->limit($limit);
				$this->dpagination->adjacents(5);
				$this->dpagination->target(site_url("admin/counteract4/manage/$page_status?".implode('&',$query)));
				$this->dpagination->parameterName('p');
				$this->dpagination->nextLabel('下一页');
				$this->dpagination->PrevLabel('上一页');
				
				$pagination = $this->dpagination->getOutput();
			}
			
			$this->_data['pagination'] = $pagination;
		}
		
		
		// Excel 导出操作
		if($this->uri->segment(5) == 'excel_write') 
		{
			$this->excel_write($status, $result, $result_count);
		}

		/** 查询所有3D列表 **/
		$this->_data['threed'] = $result;
		/** 选择年份 **/
		$this->_data['years'] = $this->shama_mdl->get_years();
		/** 选择纳音 */
		$this->_data['nayins'] = $this->nayins;
		/** 隔行提取 */
		$this->_data['gehangs'] = $this->gehangs;
		/** 百.十.个 **/
		$this->_data['position'] = $this->position;
		$this->_data['position_filter'] = $this->position_filter;
		$this->_data['multiply_filter'] = $this->multiply_filter;
		/** 跨度期号 **/
		$this->_data['qh'] = $this->qh;
		$this->_data['qh_filter'] = $this->qh_filter;

		if($status == 'shama_a') {
			$this->_data['page_title'] = '杀码系统 - 【上期 || 隔一期 || 隔二期】【百位 || 十位 || 个位】 * multiply + plus 取个位杀【百位 || 十位 || 个位】';
			$this->load->view('shama/3d_shama_a', $this->_data);
		}elseif($status == 'shama_b') {
			$this->_data['page_title'] = '杀码系统 - 【本期 || 上期 || 隔一期】期尾数 * multiply + plus 取个位杀【百位 || 十位 || 个位】';
			$this->load->view('shama/3d_shama_b', $this->_data);
		}elseif($status == 'shama_c') {
			$this->_data['page_title'] = '杀码系统 - C1~C5';
			$this->load->view('shama/3d_shama_c', $this->_data);
		}elseif($status == 'sk_a') {
			$this->_data['page_title'] = '生克系统 - A';
			$this->load->view('shama/3d_sk_a', $this->_data);
		}
		
	}

	/**
     * 
     * EXCEL 导出
     * 
     */
     public function excel_write($status='sk_a', $result='', $excel_data_count=0)
     {
		if(empty($result) || empty($excel_data_count)) return;
		
		ini_set('memory_limit', '200M'); // 默认内存128

        $this->load->library('Classes/PHPExcel');
		$this->load->library('excel_3');
		$excel_datas = array();
		$excel_define = FALSE;

		if($status == 'sk_a') { 
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh_old)) ? $rs->lottery_qh_old : '';
				// 中奖号码
				$excel_data['lottery_number'] = (isset($rs->lottery_number_old)) ? $rs->lottery_number_old : '';

				$sm = get_sk_a($rs->lottery_number_old, $rs->lottery_number_new, $this->multiply_filter,0,$this->position_filter,TRUE);
				$excel_data['sk'] = (isset($sm['sk'])) ? $sm['sk'] : '';
				$excel_data['num'] = $sm['num'];
				$excel_data['chk'] = $sm['chk'];
				$sm = get_sk_a($rs->lottery_number_old, $rs->lottery_number_new, $this->multiply_filter,1,$this->position_filter,TRUE);
				$excel_data['sk_1'] = (isset($sm['sk'])) ? $sm['sk'] : '';
				$excel_data['num_1'] = $sm['num'];
				$excel_data['chk_1'] = $sm['chk'];
				$sm = get_sk_a($rs->lottery_number_old, $rs->lottery_number_new, $this->multiply_filter,2,$this->position_filter,TRUE);
				$excel_data['sk_2'] = (isset($sm['sk'])) ? $sm['sk'] : '';
				$excel_data['num_2'] = $sm['num'];
				$excel_data['chk_2'] = $sm['chk'];
				$sm = get_sk_a($rs->lottery_number_old, $rs->lottery_number_new, $this->multiply_filter,3,$this->position_filter,TRUE);
				$excel_data['sk_3'] = (isset($sm['sk'])) ? $sm['sk'] : '';
				$excel_data['num_3'] = $sm['num'];
				$excel_data['chk_3'] = $sm['chk'];
				$sm = get_sk_a($rs->lottery_number_old, $rs->lottery_number_new, $this->multiply_filter,4,$this->position_filter,TRUE);
				$excel_data['sk_4'] = (isset($sm['sk'])) ? $sm['sk'] : '';
				$excel_data['num_4'] = $sm['num'];
				$excel_data['chk_4'] = $sm['chk'];

				$excel_datas[] = $excel_data;
			}
		}
		
		

		if( ! $excel_define) $this->excel_3->counteract_writer($status,$excel_datas,$excel_data_count);
		
     }

}

/* End of file counteract.php */
/* Location: ./application/controllers/admin/counteract.php */