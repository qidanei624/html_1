<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * 单独系统：位置号码的相生相克关系处理类(由于处理复杂，所以单独做了处理)
 */
class Counteract2 extends ST_Auth_Controller
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
		
		/** 导入相生相克补助函数 */
		$this->load->helper('my_counteract');
	}

	/**
     * 入口
     *
     * @access public
     * @return void
     */
	public function manage($status = 'lottery')
	{
		/** 默认标题 */
		$this->_data['page_title'] = '管理文章';
		/** 已发布，待审核 状态表示变量 **/
		$this->_data['status'] = $status;
		/** 分页的query string */
		$query = array();


		/** check status */
		if(!in_array($status, array('weihao_vertical','weihao_zy','weihao_diagonal_zy','weihao_order','weihao_order_diagonal','sx_vertical','sx_zy','sx_diagonal_zy','sx_order','sx_order_diagonal','plus_weihao_vertical',
									'plus_weihao_zy','plus_weihao_diagonal_zy','multiply_weihao_vertical','multiply_weihao_zy','multiply_weihao_diagonal_zy',
									'minus_weihao_vertical','minus_weihao_zy','minus_weihao_diagonal_zy','abs_weihao_vertical','abs_weihao_zy','abs_weihao_diagonal_zy',
									'zhuti_fuma_3')))
		{
			redirect('admin/counteract/manage');
		}
		/** 选择年份 filter */
		$years_filter = $this->input->get('years', TRUE);
		$years_filter = (!empty($years_filter)) ? intval($years_filter) : 0;
		if(!empty($years_filter))
		{
			$query[] = 'years='.$years_filter;
		}
		/** 尾数 */
		$col_filter = $this->input->get('weishu_col', TRUE); // 列
		$weishu_filter = $this->input->get('weishu',TRUE);   // 列尾数
		$zhutis_filter = $this->input->get('zhutis', TRUE);  // 列主题
		if(!empty($col_filter)) {
			if(is_numeric($weishu_filter)) {
				$query[] = 'weishu_col='.$col_filter;
				$query[] = 'weishu='.$weishu_filter;
			}elseif(is_numeric($zhutis_filter)) {
				$query[] = 'weishu_col='.$col_filter;
				$query[] = 'zhutis='.$zhutis_filter;
			}
			
		}
		/** 选择纳音 */
		$nayins_filter = $this->input->get('nayins', TRUE);
		$nayins_filter = (!empty($nayins_filter)) ? $nayins_filter : 0;
		if(!empty($nayins_filter)) 
		{
			$query[] = 'nayins='.$nayins_filter;
		}
		/** 选择主题 
		$zhutis_filter = $this->input->get('zhutis', TRUE);
		$zhutis_filter = (!empty($zhutis_filter)) ? $zhutis_filter : 0;
		if(!empty($zhutis_filter)) 
		{
			$query[] = 'zhutis='.$zhutis_filter;
		}*/

		/** 隔行提取 */
		$gehangs_filter = $this->input->get('gehangs', TRUE);
		$gehangs_filter = intval($gehangs_filter);
		if(!empty($gehangs_filter)) 
		{
			$query[] = 'gehangs='.$gehangs_filter;
		}

		/** 环环提取 */
		$huans_filter = $this->input->get('huans', TRUE);
		$huans_filter = intval($huans_filter);
		if(!empty($huans_filter)) 
		{
			$query[] = 'huans='.$huans_filter;
		}
		/** 左斜，右斜 判断参数*/
		$airt_filter = $this->input->get('airt', TRUE);
		$airt_filter = (!empty($airt_filter)) ? $airt_filter : '';
		if(!empty($airt_filter)) 
		{
			//$_SERVER['QUERY_STRING'] = ''; // 避免左斜，右斜轮着点击时，叠加query_string的问题
		}

		/** pagination stff */
		$page = $this->input->get('p',TRUE);
		$page = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
		$limit = 5000;
		$offset = ($page - 1) * $limit;
		//$cnt = 1002 + $offset; // 总期号

		if($offset < 0)
		{
			redirect('admin/counteract/manage');
		}
		
		// 隔行查询 (隔行提取和环环提取 不要同时进行！)
		if(!empty($gehangs_filter)) 
		{
			$pids = $this->counteract_mdl->get_pids();
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

			$result = $this->counteract_mdl->get_gehang_result($pid_str,$limit, $offset, '',$years_filter,$col_filter,$weishu_filter,$nayins_filter,$zhutis_filter);
			
			/**      垂直关系的时候要添加另外处理      */
			
			// 位置号码 - 垂直关系处理
			if($status == 'lottery') { 
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;

					if(!isset($rs[$i+1])) break;
					// 相生相克关系
					$values[$i]->arrow1 = get_vertical_counteract(get_wx($rs[$i]->num1), get_wx($rs[$i+1]->num1));
					$values[$i]->arrow2 = get_vertical_counteract(get_wx($rs[$i]->num2), get_wx($rs[$i+1]->num2));
					$values[$i]->arrow3 = get_vertical_counteract(get_wx($rs[$i]->num3), get_wx($rs[$i+1]->num3));
					// 纯杂关系
					$values[$i]->cz1 = get_lottery_cz($rs[$i]->num1, $rs[$i+1]->num1);
					$values[$i]->cz2 = get_lottery_cz($rs[$i]->num2, $rs[$i+1]->num2);
					$values[$i]->cz3 = get_lottery_cz($rs[$i]->num3, $rs[$i+1]->num3);
				}
				
				$result->result_object = $values;
				unset($values);
			}
			// 生肖名 - 垂直
			elseif($status == 'sx_vertical') {
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;
					$values[$i]->sx_year	= $rs[$i]->sx_year;

					if(!isset($rs[$i+1])) break;
					// 垂直生克行关系
					$values[$i]->arrow1 = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num1)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num1)) );
					$values[$i]->arrow2 = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num2)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num2)) );
					$values[$i]->arrow3 = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num3)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num3)) );
				}
				
				$result->result_object = $values;
				unset($values);
			}
			// 位置号码 - 同顺序垂直
			elseif($status == 'lottery_order') {
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;

					if(!isset($rs[$i+1])) break;
					// 表示顺序的箭头方向
					if($rs[$i]->num1 + 1 == $rs[$i+1]->num1) {
						$values[$i]->arrow1 = get_order_arrow('b');
					}elseif($rs[$i]->num1 - 1 == $rs[$i+1]->num1) {
						$values[$i]->arrow1 = get_order_arrow('t');
					}
					if($rs[$i]->num2 + 1 == $rs[$i+1]->num2) {
						$values[$i]->arrow2 = get_order_arrow('b');
					}elseif($rs[$i]->num2 - 1 == $rs[$i+1]->num2) {
						$values[$i]->arrow2 = get_order_arrow('t');
					}
					if($rs[$i]->num3 + 1 == $rs[$i+1]->num3) {
						$values[$i]->arrow3 = get_order_arrow('b');
					}elseif($rs[$i]->num3 - 1 == $rs[$i+1]->num3) {
						$values[$i]->arrow3 = get_order_arrow('t');
					}
				}
				
				$result->result_object = $values;
				unset($values);
			}

			$result_count = $this->counteract_mdl->get_gehang_result($pid_str,'', '', '',$years_filter,$col_filter,$weishu_filter,$nayins_filter,$zhutis_filter)->num_rows();
		}
		// 选择环环提取 (隔行提取和环环提取 不要同时进行！)
		else if(!empty($huans_filter))
		{
			$this->benchmark->mark('code_start'); //测试类
			
			$object1 = new stdClass; // stdClass 是 基类,可以让这个变量成为Object
			$ars = array();
			$counter = 1;

			$result = $this->counteract_mdl->get_threed('', '', 'DESC',$years_filter,$col_filter,$weishu_filter,$nayins_filter,$zhutis_filter);

			$ars = $result->result();
			$rs_count = count($ars);
				
			for($i = 0; $i < $rs_count; $i++) 
			{
				if(!empty($ars[$i]) && !empty($ars[$i+$huans_filter])) 
				{
					$rs[] = $ars[$i];
					$rs[] = $ars[$i+$huans_filter];
				}
			}

			$result_count = count($rs); // 把总行数递给$result_count

			/* 为了分页效果，重新整合了数据，尚未准确，有待完善。
			$rs = array();
			$offset = ($offset > 0) ? ceil($offset/2) : 0; //环环相比，所以除2, 只有平行时有用！

			for($i = $offset; $i < $result_count; $i++) 
			{
				// 环环相比所以乘2,只有平行时有用！
				if($counter >  ceil($limit/2)) {
					break;
				}
				if(!empty($ars[$i]) && !empty($ars[$i+$huans_filter])) 
				{
					$rs[] = $ars[$i];
					$rs[] = $ars[$i+$huans_filter];
				}

				$counter++;
			}*/
			$result->result_object = array_reverse($rs);
			unset($ars, $rs);

			/**      垂直关系的时候要添加另外处理      */
			
			// 位置号码 - 垂直关系处理
			if($status == 'weihao_vertical') { 
				$rs = $result->result();
				for($i=0; $i < $result_count; $i++)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;

					if(!isset($rs[$i+1])) break;
					// 相生相克关系
					$values[$i]->arrow1 = get_vertical_counteract(get_wx($rs[$i]->num1), get_wx($rs[$i+1]->num1));
					$values[$i]->arrow2 = get_vertical_counteract(get_wx($rs[$i]->num2), get_wx($rs[$i+1]->num2));
					$values[$i]->arrow3 = get_vertical_counteract(get_wx($rs[$i]->num3), get_wx($rs[$i+1]->num3));
					// 纯杂关系
					$values[$i]->cz1 = get_lottery_cz($rs[$i]->num1, $rs[$i+1]->num1);
					$values[$i]->cz2 = get_lottery_cz($rs[$i]->num2, $rs[$i+1]->num2);
					$values[$i]->cz3 = get_lottery_cz($rs[$i]->num3, $rs[$i+1]->num3);
				}
				
				$result->result_object = $values;
				unset($values);
			}
			// 生肖名 - 垂直
			elseif($status == 'sx_vertical') { 
				$rs = $result->result();
				for($i=0; $i < $result_count; $i++)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;
					$values[$i]->sx_year	= $rs[$i]->sx_year;

					if(!isset($rs[$i+1])) break;
					// 垂直生克行关系
					$values[$i]->arrow1 = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num1)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num1)) );
					$values[$i]->arrow2 = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num2)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num2)) );
					$values[$i]->arrow3 = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num3)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num3)) );
				}
				
				$result->result_object = $values;
				unset($values);
			}
			// 位置号码 - 同顺序垂直
			elseif($status == 'lottery_order') {
				$rs = $result->result();
				for($i=0; $i < $result_count; $i++)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;

					if(!isset($rs[$i+1])) break;
					// 表示顺序的箭头方向
					if($rs[$i]->num1 + 1 == $rs[$i+1]->num1) {
						$values[$i]->arrow1 = get_order_arrow('b');
					}elseif($rs[$i]->num1 - 1 == $rs[$i+1]->num1) {
						$values[$i]->arrow1 = get_order_arrow('t');
					}
					if($rs[$i]->num2 + 1 == $rs[$i+1]->num2) {
						$values[$i]->arrow2 = get_order_arrow('b');
					}elseif($rs[$i]->num2 - 1 == $rs[$i+1]->num2) {
						$values[$i]->arrow2 = get_order_arrow('t');
					}
					if($rs[$i]->num3 + 1 == $rs[$i+1]->num3) {
						$values[$i]->arrow3 = get_order_arrow('b');
					}elseif($rs[$i]->num3 - 1 == $rs[$i+1]->num3) {
						$values[$i]->arrow3 = get_order_arrow('t');
					}
				}
				
				$result->result_object = $values;
				unset($values);
			}
		}
		else // 默认
		{
			$result = $this->counteract_mdl->get_threed($limit, $offset, '',$years_filter,$col_filter,$weishu_filter,$nayins_filter,$zhutis_filter);
			if($status == 'weihao_vertical') { // 位号-垂直关系处理, 
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;

					if(!isset($rs[$i+1])) break;
					// 相生相克关系
					$values[$i]->arrow1 = get_vertical_counteract(get_wx($rs[$i]->num1), get_wx($rs[$i+1]->num1));
					$values[$i]->arrow2 = get_vertical_counteract(get_wx($rs[$i]->num2), get_wx($rs[$i+1]->num2));
					$values[$i]->arrow3 = get_vertical_counteract(get_wx($rs[$i]->num3), get_wx($rs[$i+1]->num3));
					// 纯杂关系
					$values[$i]->cz1 = get_lottery_cz($rs[$i]->num1, $rs[$i+1]->num1);
					$values[$i]->cz2 = get_lottery_cz($rs[$i]->num2, $rs[$i+1]->num2);
					$values[$i]->cz3 = get_lottery_cz($rs[$i]->num3, $rs[$i+1]->num3);
				}
				
				$result->result_object = $values;
				unset($values);
			}
			elseif($status == 'weihao_order') { // 位置号码 - 同顺序垂直关系处理
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;

					if(!isset($rs[$i+1])) break;
					// 表示顺序的箭头方向
					if($rs[$i]->num1 + 1 == $rs[$i+1]->num1) {
						$values[$i]->arrow1 = get_order_arrow('b');
					}elseif($rs[$i]->num1 - 1 == $rs[$i+1]->num1) {
						$values[$i]->arrow1 = get_order_arrow('t');
					}
					if($rs[$i]->num2 + 1 == $rs[$i+1]->num2) {
						$values[$i]->arrow2 = get_order_arrow('b');
					}elseif($rs[$i]->num2 - 1 == $rs[$i+1]->num2) {
						$values[$i]->arrow2 = get_order_arrow('t');
					}
					if($rs[$i]->num3 + 1 == $rs[$i+1]->num3) {
						$values[$i]->arrow3 = get_order_arrow('b');
					}elseif($rs[$i]->num3 - 1 == $rs[$i+1]->num3) {
						$values[$i]->arrow3 = get_order_arrow('t');
					}
				}
				
				$result->result_object = $values;
				unset($values);
			}
			elseif($status == 'sx_vertical') { // 生肖名 - 垂直关系处理
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;
					$values[$i]->sx_year	= $rs[$i]->sx_year;

					if(!isset($rs[$i+1])) break;
					// 垂直生克行关系
					$values[$i]->arrow1 = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num1)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num1)) );
					$values[$i]->arrow2 = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num2)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num2)) );
					$values[$i]->arrow3 = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num3)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num3)) );
				}
				
				$result->result_object = $values;
				unset($values);
			}
			elseif($status == 'sx_order') { // 生肖名 - 同顺序垂直关系处理
				$rs = $result->result();
				$result_count = count($rs);
				for($i=0; $i < $result_count; $i++)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;
					$values[$i]->sx_year	= $rs[$i]->sx_year;

					if(!isset($rs[$i+1])) break;
					$values[$i]->arrow1_a = get_sx_order_arrow(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num1), get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num1),'A');
					$values[$i]->arrow2_a = get_sx_order_arrow(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num2), get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num2),'A');
					$values[$i]->arrow3_a = get_sx_order_arrow(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num3), get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num3),'A');

					$values[$i]->arrow1_b = get_sx_order_arrow(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num1), get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num1),'B');
					$values[$i]->arrow2_b = get_sx_order_arrow(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num2), get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num2),'B');
					$values[$i]->arrow3_b = get_sx_order_arrow(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num3), get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num3),'B');

				}
				
				$result->result_object = $values;
				unset($values);
			}
			// 等距加法乘法减法绝对值系统-位号垂直
			elseif($status == 'plus_weihao_vertical' OR $status=='multiply_weihao_vertical' OR $status=='minus_weihao_vertical' OR $status=='abs_weihao_vertical') {
				$rs = $result->result();
				$result_count = count($rs);
				for($i=$result_count-1; $i >= 0; $i--)
				{	
					$values[$i]->num1		= $rs[$i]->num1;
					$values[$i]->num2		= $rs[$i]->num2;
					$values[$i]->num3		= $rs[$i]->num3;
					$values[$i]->pid		= $rs[$i]->pid;
					$values[$i]->created	= $rs[$i]->created;
					$values[$i]->lottery_qh	= $rs[$i]->lottery_qh;
					$values[$i]->kgny		= $rs[$i]->kgny;

					if(!isset($rs[$i-1])) {
						$values[$i]->result_1 = $rs[$i]->num1;
						$values[$i]->result_2 = $rs[$i]->num2;
						$values[$i]->result_3 = $rs[$i]->num3;
						break;	
					} 
					// 加法垂直关系
					$values[$i]->result_1 = get_weishu_pm($rs[$i-1]->num1, $rs[$i]->num1, $status);
					$values[$i]->result_2 = get_weishu_pm($rs[$i-1]->num2, $rs[$i]->num2, $status);
					$values[$i]->result_3 = get_weishu_pm($rs[$i-1]->num3, $rs[$i]->num3, $status);
				}
				
				$result->result_object = array_reverse($values);
				unset($values);
			}

			$result_count = $this->counteract_mdl->get_threed(20000,0,'',$years_filter,$col_filter,$weishu_filter,$nayins_filter,$zhutis_filter)->num_rows();
		}
		
		// 左斜 处理
		if($airt_filter == 'left_x') 
		{
			/*	
			位号-左斜
			生肖-左斜
			等距加法系统-位号左斜
			等距乘法系统-位号左斜
			等距减法系统-位号左斜
			等距绝对值系统-位号左斜
			*/
			if($status == 'weihao_zy' OR $status == 'sx_zy' OR $status == 'plus_weihao_zy' OR $status == 'multiply_weihao_zy' OR $status == 'minus_weihao_zy' OR $status == 'abs_weihao_zy') {
				//if($limit < $result_count) show_error('$limit 要设成最大才可以使用 左斜，右斜！');

				$rs = $result->result();
				$rs = (array)$rs;

				$airs = new stdClass;
				$air_k = array();
				$air_v = array();

				foreach($rs as $key => $value)
				{
					$keys[$key]->pid				= $value->pid;
					$values[$key]->created			= $value->created;
					$values[$key]->lottery_qh		= $value->lottery_qh;
					$values[$key]->kgny				= $value->kgny;
					$values[$key]->sx_year			= $value->sx_year; //生肖-左右斜 使用

					$values[$key]->num1	= '';

					if($key > 0) 
					{
						$values[$key]->num1	= $rs[$key-1]->num2;
						$values[$key]->num2	= $rs[$key]->num1;
						$values[$key]->num3	= $rs[$key-1]->num3;
						$values[$key]->num4	= $rs[$key]->num1;
						$values[$key]->num5	= $rs[$key-1]->num3;
						$values[$key]->num6	= $rs[$key]->num2;
					}

					$air_k[$key] = $keys[$key];
					$air_v[$key] = $values[$key];
					
					$airs->$key = (object)((array)$air_k[$key] + (array)$air_v[$key]);
				}

				$result->result_object = $airs;
				unset($rs, $keys, $values, $air_k, $air_v, $airs);
			}
			/*
			位号-对角线左斜, 
			生肖-对角线左斜
			等距加法系统-位号对角线左斜
			等距乘法系统-位号对角线左斜
			等距减法系统-位号对角线左斜
			等距绝对值系统-位号对角线左斜
			位号-同顺序对角线左斜
			生肖-同顺序对角线左斜
			*/
			elseif($status == 'weihao_diagonal_zy' OR $status == 'sx_diagonal_zy' OR $status == 'plus_weihao_diagonal_zy' OR $status == 'multiply_weihao_diagonal_zy' OR $status == 'minus_weihao_diagonal_zy' OR $status == 'abs_weihao_diagonal_zy' OR 
					$status == 'weihao_order_diagonal' OR $status == 'sx_order_diagonal') {
				//if($limit < $result_count) show_error('$limit 要设成最大才可以使用 左斜，右斜！');

				$rs = $result->result();
				$rs = (array)$rs;

				$airs = new stdClass;
				$air_k = array();
				$air_v = array();

				foreach($rs as $key => $value)
				{
					$keys[$key]->pid				= $value->pid;
					$values[$key]->created			= $value->created;
					$values[$key]->lottery_qh		= $value->lottery_qh;
					$values[$key]->kgny				= $value->kgny;
					$values[$key]->sx_year			= $value->sx_year; //生肖 使用

					if($key > 0) 
					{
						$values[$key]->num1	= (isset($rs[$key-2]->num3)) ? $rs[$key-2]->num3 : '';
						$values[$key]->num2	= $rs[$key-1]->num2;
						$values[$key]->num3	= $rs[$key]->num1;
					}
					if($key == 0 OR $key == 1) { // 第1行和第2行数据清空
						$values[$key]->num1 = '';
						$values[$key]->num2 = '';
						$values[$key]->num3 = '';
					}

					$air_k[$key] = $keys[$key];
					$air_v[$key] = $values[$key];
					
					$airs->$key = (object)((array)$air_k[$key] + (array)$air_v[$key]);
				}

				$result->result_object = $airs;
				unset($rs, $keys, $values, $air_k, $air_v, $airs);
			}
			elseif($status == 'weihao_vertical') {
				die("位号垂直时，只有垂直关系，没有左右斜！");
			}elseif($status == 'weihao_order') {
				die("位号同顺序时，只有垂直关系，没有左右斜！");
			}elseif($status == 'sx_vertical') {
				die("生肖名-垂直时，只有垂直关系，没有左右斜！");
			}elseif($status == 'sx_order') {
				die("生肖名-同顺序时，只有垂直关系，没有左右斜！");
			}elseif($status == 'plus_weihao_vertical') {
				die("等距加法系统-位号垂直时，只有垂直关系，没有左右斜！");
			}elseif($status == 'multiply_weihao_vertical') {
				die("等距乘法系统-位号垂直时，只有垂直关系，没有左右斜！");
			}elseif($status == 'minus_weihao_vertical') {
				die("等距减法系统-位号垂直时，只有垂直关系，没有左右斜！");
			}elseif($status == 'abs_weihao_vertical') {
				die("等距绝对值系统-位号垂直时，只有垂直关系，没有左右斜！");
			}elseif($status == 'zhuti_fuma_3') {
				die("主题副码 - 位号右斜时，只有右斜关系，没有左斜！");
			}
			
		}
		// 右斜 处理
		else if($airt_filter == 'right_x')
		{
			/*	
			位置号码-右斜
			生肖-右斜
			等距离加法系统-位号右斜
			等距离乘法系统-位号右斜
			等距离减法系统-位号右斜
			等距离绝对值系统-位号右斜
			*/
			if($status == 'weihao_zy' OR $status == 'sx_zy' OR $status == 'plus_weihao_zy' OR $status == 'multiply_weihao_zy' OR $status == 'minus_weihao_zy' OR $status == 'abs_weihao_zy') {
				//if($limit < $result_count) show_error('$limit 要设成最大才可以使用 左斜，右斜！');

				$rs = $result->result();

				// 最后一个(预测的数据，就是本日)数据要显示的话必须在最后加上空对象
				$rs = (array)$rs;
				//$rs[] = new stdClass;
				
				$airs = new stdClass;
				$air_k = array();
				$air_v = array();

				//$air_k[0]->pid = NULL;

				foreach($rs as $key => $value)
				{
					$keys[$key]->pid = $value->pid;
					$values[$key]->created			= $value->created;
					$values[$key]->lottery_qh		= $value->lottery_qh;
					$values[$key]->kgny				= $value->kgny;
					$values[$key]->sx_year			= $value->sx_year; //生肖-左右斜 使用

					if($key > 0) 
					{
						$values[$key]->num1	= $rs[$key-1]->num1;
						$values[$key]->num2	= $rs[$key]->num2;
						$values[$key]->num3	= $rs[$key-1]->num1;
						$values[$key]->num4	= $rs[$key]->num3;
						$values[$key]->num5	= $rs[$key-1]->num2;
						$values[$key]->num6	= $rs[$key]->num3;
					}
					
					
					$air_k[$key] = $keys[$key];
					$air_v[$key] = $values[$key];
					
					$airs->$key = (object)((array)$air_k[$key] + (array)$air_v[$key]);				
					//echo $key.'--'.$air_k[$key]->pid.'---'.$air_v[$key]->lottery_number.'<br>';
				}
				
				$result->result_object = $airs;
				unset($rs, $keys, $values, $air_k, $air_v, $airs);
			}
			/*
			位置号码-对角线右斜, 
			生肖-对角线右斜
			等距加法系统-位号对角线右斜
			等距乘法系统-位号对角线右斜
			等距减法系统-位号对角线右斜
			等距绝对值系统-位号对角线右斜
			位号-同顺序对角线右斜
			生肖-同顺序对角线右斜
			*/
			elseif($status == 'weihao_diagonal_zy' OR $status == 'sx_diagonal_zy' OR $status == 'plus_weihao_diagonal_zy' OR $status == 'multiply_weihao_diagonal_zy' OR $status == 'minus_weihao_diagonal_zy' OR $status == 'abs_weihao_diagonal_zy' OR 
					$status == 'weihao_order_diagonal'  OR $status == 'sx_order_diagonal') {

				//if($limit < $result_count) show_error('$limit 要设成最大才可以使用 左斜，右斜！');
				$rs = $result->result();

				// 最后一个(预测的数据，就是本日)数据要显示的话必须在最后加上空对象
				$rs = (array)$rs;
				//$rs[] = new stdClass;
				
				$airs = new stdClass;
				$air_k = array();
				$air_v = array();
				$rs_count = count($rs);

				foreach($rs as $key => $value)
				{
					$keys[$key]->pid = $value->pid;
					$values[$key]->created			= $value->created;
					$values[$key]->lottery_qh		= $value->lottery_qh;
					$values[$key]->kgny				= $value->kgny;
					$values[$key]->sx_year			= $value->sx_year; //生肖-对角线左右斜 使用

					if($key > 0) 
					{
						$values[$key]->num1	= (isset($rs[$key-2]->num1)) ? $rs[$key-2]->num1 : '';
						$values[$key]->num2	= $rs[$key-1]->num2;
						$values[$key]->num3	= $rs[$key]->num3;
					}
					if($key == 0 OR $key == 1) { // 第1行和第2行数据清空
						$values[$key]->num1 = '';
						$values[$key]->num2 = '';
						$values[$key]->num3 = '';
					}
					
					$air_k[$key] = $keys[$key];
					$air_v[$key] = $values[$key];
					
					$airs->$key = (object)((array)$air_k[$key] + (array)$air_v[$key]);				
					//echo $key.'--'.$air_k[$key]->pid.'---'.$air_v[$key]->lottery_number.'<br>';
				}
				
				$result->result_object = $airs;
				unset($rs, $keys, $values, $air_k, $air_v, $airs);
			}
			if($status == 'zhuti_fuma_3') {// 主题副码 - 位号右斜

				$rs = $result->result();
				$rs = (array)$rs;
				
				$airs = new stdClass;
				$air_k = array();
				$air_v = array();

				foreach($rs as $key => $value)
				{
					$keys[$key]->pid = $value->pid;
					$values[$key]->created			= $value->created;
					$values[$key]->lottery_qh		= $value->lottery_qh;
					$values[$key]->kgny				= $value->kgny;
					$values[$key]->lottery_number	= $value->lottery_number;
					$values[$key]->lottery_number_2	= (isset($rs[$key+1]->lottery_number)) ? $rs[$key+1]->lottery_number : '';
					$values[$key]->zhuti_fuma		= (isset($rs[$key+1]->zhuti_fuma)) ? $rs[$key+1]->zhuti_fuma : '';

					$air_k[$key] = $keys[$key];
					$air_v[$key] = $values[$key];
					
					$airs->$key = (object)((array)$air_k[$key] + (array)$air_v[$key]);				
					//echo $key.'--'.$air_k[$key]->pid.'---'.$air_v[$key]->lottery_number.'<br>';
				}
				
				$result->result_object = $airs;
				unset($rs, $keys, $values, $air_k, $air_v, $airs);
			}
			elseif($status == 'weihao_vertical') {
				die("位置号码时，只有垂直关系，没有左右斜！");
			}elseif($status == 'weihao_order') {
				die("位置号码-同顺序时，只有垂直关系，没有左右斜！");
			}elseif($status == 'sx_vertical') {
				die("生肖名-垂直时，只有垂直关系，没有左右斜！");
			}elseif($status == 'sx_order') {
				die("生肖名-同顺序时，只有垂直关系，没有左右斜！");
			}elseif($status == 'plus_weihao_vertical') {
				die("等距加法系统-位号垂直时，只有垂直关系，没有左右斜！");
			}elseif($status == 'multiply_weihao_vertical') {
				die("等距乘法系统-位号垂直时，只有垂直关系，没有左右斜！");
			}elseif($status == 'minus_weihao_vertical') {
				die("等距减法系统-位号垂直时，只有垂直关系，没有左右斜！");
			}elseif($status == 'abs_weihao_vertical') {
				die("等距绝对值系统-位号垂直时，只有垂直关系，没有左右斜！");
			}
			
		}

		if($result)
		{
			$pagination = '';
			
			if($result_count > $limit)
			{	
				if ($status == 'weihao_vertical') $page_status = 'weihao_vertical';
				elseif ($status == 'weihao_zy') $page_status = 'weihao_zy';
				elseif ($status == 'weihao_diagonal_zy') $page_status = 'weihao_diagonal_zy';
				elseif ($status == 'weihao_order') $page_status = 'weihao_order';
				elseif ($status == 'weihao_order_diagonal') $page_status = 'weihao_order_diagonal';
				elseif ($status == 'sx_vertical') $page_status = 'sx_vertical';
				elseif ($status == 'sx_zy') $page_status = 'sx_zy';
				elseif ($status == 'sx_diagonal_zy') $page_status = 'sx_diagonal_zy';
				elseif ($status == 'sx_order') $page_status = 'sx_order';
				elseif ($status == 'sx_order_diagonal') $page_status = 'sx_order_diagonal';
				elseif ($status == 'plus_weihao_vertical') $page_status = 'plus_weihao_vertical';
				elseif ($status == 'plus_weihao_zy') $page_status = 'plus_weihao_zy';
				elseif ($status == 'plus_weihao_diagonal_zy') $page_status = 'plus_weihao_diagonal_zy';
				elseif ($status == 'multiply_weihao_vertical') $page_status = 'multiply_weihao_vertical';
				elseif ($status == 'multiply_weihao_zy') $page_status = 'multiply_weihao_zy';
				elseif ($status == 'multiply_weihao_diagonal_zy') $page_status = 'multiply_weihao_diagonal_zy';
				elseif ($status == 'minus_weihao_vertical') $page_status = 'minus_weihao_vertical';
				elseif ($status == 'minus_weihao_zy') $page_status = 'minus_weihao_zy';
				elseif ($status == 'minus_weihao_diagonal_zy') $page_status = 'minus_weihao_diagonal_zy';
				elseif ($status == 'abs_weihao_vertical') $page_status = 'abs_weihao_vertical';
				elseif ($status == 'abs_weihao_zy') $page_status = 'abs_weihao_zy';
				elseif ($status == 'abs_weihao_diagonal_zy') $page_status = 'abs_weihao_diagonal_zy';


				$this->dpagination->currentPage($page);
				$this->dpagination->items($result_count);
				$this->dpagination->limit($limit);
				$this->dpagination->adjacents(5);
				$this->dpagination->target(site_url("admin/counteract2/manage/$page_status?".implode('&',$query)));
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
		$this->_data['years'] = $this->counteract_mdl->get_years();
		/** 尾数 */
		$this->_data['weishu_col'] = $this->weishu_col;
		/** 选择纳音 */
		$this->_data['nayins'] = $this->nayins;
		/** 选择主题 */
		$this->_data['zhutis'] = $this->zhutis;
		/** 隔行提取 */
		$this->_data['gehangs'] = $this->gehangs;
		/** 环环提取 */
		$this->_data['huans'] = $this->huans;

		if($status == 'weihao_vertical') {
			$this->_data['page_title'] = '位号垂直系统';
			$this->load->view('admin/counteract_3d_weihao_vertical', $this->_data);
		}
		elseif($status == 'weihao_zy') {
			$this->_data['page_title'] = '位号 - 左右斜系统';
			$this->load->view('admin/counteract_3d_weihao_zy', $this->_data);
		}
		elseif($status == 'weihao_diagonal_zy') {
			$this->_data['page_title'] = '位号 - 左右斜对角线系统';
			$this->load->view('admin/counteract_3d_weihao_diagonal_zy', $this->_data);
		}
		elseif($status == 'weihao_order') {
			$this->_data['page_title'] = '位号 - 同顺序垂直系统';
			$this->load->view('admin/counteract_3d_weihao_order', $this->_data);
		}
		elseif($status == 'weihao_order_diagonal') {
			$this->_data['page_title'] = '位号 - 同顺序对角线系统';
			$this->load->view('admin/counteract_3d_weihao_order_diagonal', $this->_data);
		}
		elseif($status == 'sx_vertical') {
			$this->_data['page_title'] = '生肖名 - 垂直系统';
			$this->load->view('admin/counteract_3d_sx_vertical', $this->_data);
		}
		elseif($status == 'sx_zy') {
			$this->_data['page_title'] = '生肖名 - 左右斜系统';
			$this->load->view('admin/counteract_3d_sx_zy', $this->_data);
		}
		elseif($status == 'sx_diagonal_zy') {
			$this->_data['page_title'] = '生肖名 - 左右斜对角线系统';
			$this->load->view('admin/counteract_3d_sx_diagonal_zy', $this->_data);
		}
		elseif($status == 'sx_order') {
			$this->_data['page_title'] = '生肖名 - 同顺序系统';
			$this->load->view('admin/counteract_3d_sx_order', $this->_data);
		}
		elseif($status == 'sx_order_diagonal') {
			$this->_data['page_title'] = '生肖名 - 同顺序对角线系统';
			$this->load->view('admin/counteract_3d_sx_order_diagonal', $this->_data);
		}elseif($status == 'plus_weihao_vertical') {
			$this->_data['page_title'] = '等距加法系统 - 位号垂直';
			$this->load->view('admin/counteract_3d_plus_weihao_vertical', $this->_data);
		}elseif($status == 'plus_weihao_zy') {
			$this->_data['page_title'] = '等距加法系统 - 位号左右斜';
			$this->load->view('admin/counteract_3d_plus_weihao_zy', $this->_data);
		}elseif($status == 'plus_weihao_diagonal_zy') {
			$this->_data['page_title'] = '等距加法系统 - 位号对角线左右斜';
			$this->load->view('admin/counteract_3d_plus_weihao_diagonal_zy', $this->_data);
		}elseif($status == 'multiply_weihao_vertical') {
			$this->_data['page_title'] = '等距乘法系统 - 位号垂直';
			$this->load->view('admin/counteract_3d_multiply_weihao_vertical', $this->_data);
		}elseif($status == 'multiply_weihao_zy') {
			$this->_data['page_title'] = '等距乘法系统 - 位号左右斜';
			$this->load->view('admin/counteract_3d_multiply_weihao_zy', $this->_data);
		}elseif($status == 'multiply_weihao_diagonal_zy') {
			$this->_data['page_title'] = '等距乘法系统 - 位号对角线左右斜';
			$this->load->view('admin/counteract_3d_multiply_weihao_diagonal_zy', $this->_data);
		}elseif($status == 'minus_weihao_vertical') {
			$this->_data['page_title'] = '等距减法系统 - 位号垂直';
			$this->load->view('admin/counteract_3d_minus_weihao_vertical', $this->_data);
		}elseif($status == 'minus_weihao_zy') {
			$this->_data['page_title'] = '等距减法系统 - 位号左右斜';
			$this->load->view('admin/counteract_3d_minus_weihao_zy', $this->_data);
		}elseif($status == 'minus_weihao_diagonal_zy') {
			$this->_data['page_title'] = '等距减法系统 - 位号对角线左右斜';
			$this->load->view('admin/counteract_3d_minus_weihao_diagonal_zy', $this->_data);
		}elseif($status == 'abs_weihao_vertical') {
			$this->_data['page_title'] = '等距绝对值系统 - 位号垂直';
			$this->load->view('admin/counteract_3d_abs_weihao_vertical', $this->_data);
		}elseif($status == 'abs_weihao_zy') {
			$this->_data['page_title'] = '等距绝对值系统 - 位号左右斜';
			$this->load->view('admin/counteract_3d_abs_weihao_zy', $this->_data);
		}elseif($status == 'abs_weihao_diagonal_zy') {
			$this->_data['page_title'] = '等距绝对值系统 - 位号对角线左右斜';
			$this->load->view('admin/counteract_3d_abs_weihao_diagonal_zy', $this->_data);
		}

		elseif($status == 'zhuti_fuma_3') {
			$this->_data['page_title'] = '主题副码 - 位置号码系统';
			$this->load->view('admin/counteract_3d_zhuti_fuma_3', $this->_data);
		}

	}

	/**
     * 
     * EXCEL 导出
     * 
     */
     public function excel_write($status='weihao_vertical', $result='', $excel_data_count=0)
     {
		if(empty($result) || empty($excel_data_count)) return;
		
		ini_set('memory_limit', '200M'); // 默认内存128

        $this->load->library('Classes/PHPExcel');
		$this->load->library('excel');
		$excel_datas = array();
		$excel_define = FALSE;

		if($status == 'weihao_vertical') // 位号垂直
		{
			$rs = $result->result();
			$rs_count = count($rs);
			for($i=0; $i < $rs_count; $i++)
			{
				if(!isset($rs[$i]->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs[$i]->pid)) ? $rs[$i]->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs[$i]->created)) ? $rs[$i]->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs[$i]->lottery_qh)) ? $rs[$i]->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs[$i]->kgny;
				// 中奖号码
				$excel_data['num1'] = (isset($rs[$i]->num1)) ? $rs[$i]->num1 : '';
				$excel_data['num2'] = (isset($rs[$i]->num2)) ? $rs[$i]->num2 : '';
				$excel_data['num3'] = (isset($rs[$i]->num3)) ? $rs[$i]->num3 : '';

				if(!isset($rs[$i+1])) {
					$excel_datas[] = $excel_data;
					break;
				}
				// 位置号码 生克刑轨迹
				$excel_data['counteract_1'] = get_vertical_counteract(get_wx($rs[$i]->num1), get_wx($rs[$i+1]->num1), TRUE);
				$excel_data['counteract_2'] = get_vertical_counteract(get_wx($rs[$i]->num2), get_wx($rs[$i+1]->num2), TRUE);
				$excel_data['counteract_3'] = get_vertical_counteract(get_wx($rs[$i]->num3), get_wx($rs[$i+1]->num3), TRUE);
				// 位置号码 纯杂轨迹
				$excel_data['cz_1'] = get_lottery_cz($rs[$i]->num1, $rs[$i+1]->num1, TRUE);
				$excel_data['cz_2'] = get_lottery_cz($rs[$i]->num2, $rs[$i+1]->num2, TRUE);
				$excel_data['cz_3'] = get_lottery_cz($rs[$i]->num3, $rs[$i+1]->num3, TRUE);
				$excel_datas[] = $excel_data;
			}
		}
		/* 
		位号 - 左右斜
		位号 - 对角线左右斜
		*/
		elseif($status == 'weihao_zy' OR $status == 'weihao_diagonal_zy') 
		{
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs->kgny;

				// 位置号码 生克刑轨迹
				$excel_data['counteract_1'] = (isset($rs->num2)) ? get_prefix_nums($rs->num1, $rs->num2, TRUE) : '';
				$excel_data['counteract_2'] = (isset($rs->num3) && isset($rs->num4)) ? get_prefix_nums($rs->num3, $rs->num4, TRUE) : '';
				if($status == 'weihao_diagonal_zy') {
					$excel_data['counteract_2'] = (isset($rs->num1) && isset($rs->num3)) ? get_prefix_nums($rs->num1, $rs->num3, TRUE) : '';
				}
				$excel_data['counteract_3'] = (isset($rs->num5) && isset($rs->num6)) ? get_prefix_nums($rs->num5, $rs->num6, TRUE) : '';
				// 位置号码 纯杂轨迹
				$excel_data['cz_1'] = (isset($rs->num2)) ? get_numeric_cz($rs->num1, $rs->num2, TRUE) : '';
				$excel_data['cz_2'] = (isset($rs->num3) && isset($rs->num4)) ? get_numeric_cz($rs->num3, $rs->num4, TRUE) : '';
				if($status == 'weihao_diagonal_zy') {
					$excel_data['cz_2'] = (isset($rs->num1) && isset($rs->num3)) ? get_numeric_cz($rs->num1, $rs->num3, TRUE) : '';
				}
				$excel_data['cz_3'] = (isset($rs->num5) && isset($rs->num6)) ? get_numeric_cz($rs->num5, $rs->num6, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_data['num4'] = (isset($rs->num4)) ? $rs->num4 : '';
				$excel_data['num5'] = (isset($rs->num5)) ? $rs->num5 : '';
				$excel_data['num6'] = (isset($rs->num6)) ? $rs->num6 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'weihao_order') // 位置号码 - 同顺序
		{
			$rs = $result->result();
			$rs_count = count($rs);
			for($i=0; $i < $rs_count; $i++)
			{
				if(!isset($rs[$i]->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs[$i]->pid)) ? $rs[$i]->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs[$i]->created)) ? $rs[$i]->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs[$i]->lottery_qh)) ? $rs[$i]->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs[$i]->kgny;
				// 中奖号码
				$excel_data['num1'] = (isset($rs[$i]->num1)) ? $rs[$i]->num1 : '';
				$excel_data['num2'] = (isset($rs[$i]->num2)) ? $rs[$i]->num2 : '';
				$excel_data['num3'] = (isset($rs[$i]->num3)) ? $rs[$i]->num3 : '';

				if(!isset($rs[$i+1])) {
					$excel_datas[] = $excel_data;
					break;
				}

				// 位置号码 - 同顺序箭头轨迹
				$excel_data['arrow1'] = '';
				$excel_data['arrow2'] = '';
				$excel_data['arrow3'] = '';
				if($rs[$i]->num1 + 1 == $rs[$i+1]->num1) {
					$excel_data['arrow1'] = get_order_arrow('b', TRUE);
				}elseif($rs[$i]->num1 - 1 == $rs[$i+1]->num1) {
					$excel_data['arrow1'] = get_order_arrow('t', TRUE);
				}
				if($rs[$i]->num2 + 1 == $rs[$i+1]->num2) {
					$excel_data['arrow2'] = get_order_arrow('b', TRUE);
				}elseif($rs[$i]->num2 - 1 == $rs[$i+1]->num2) {
					$excel_data['arrow2'] = get_order_arrow('t', TRUE);
				}
				if($rs[$i]->num3 + 1 == $rs[$i+1]->num3) {
					$excel_data['arrow3'] = get_order_arrow('b', TRUE);
				}elseif($rs[$i]->num3 - 1 == $rs[$i+1]->num3) {
					$excel_data['arrow3'] = get_order_arrow('t', TRUE);
				}
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'weihao_order_diagonal') // 位号 - 同顺序对角线
		{
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs->kgny;
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				// 位置号码 - 同顺序对角线箭头轨迹对应
				$excel_data['arrow1'] = get_order_diagonal_arrow($excel_data['num1'], $excel_data['num2'], TRUE);
				$excel_data['arrow2'] = get_order_diagonal_arrow($excel_data['num2'], $excel_data['num3'], TRUE);
				
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'sx_vertical') // 生肖名 - 垂直
		{
			$rs = $result->result();
			$rs_count = count($rs);
			for($i=0; $i < $rs_count; $i++)
			{
				if(!isset($rs[$i]->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs[$i]->pid)) ? $rs[$i]->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs[$i]->created)) ? $rs[$i]->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs[$i]->lottery_qh)) ? $rs[$i]->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs[$i]->kgny;
				// 中奖号码
				$excel_data['num1'] = (isset($rs[$i]->num1)) ? $rs[$i]->num1.' '.get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num1) : '';
				$excel_data['num2'] = (isset($rs[$i]->num2)) ? $rs[$i]->num2.' '.get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num2) : '';
				$excel_data['num3'] = (isset($rs[$i]->num3)) ? $rs[$i]->num3.' '.get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num3) : '';

				if(!isset($rs[$i+1])) {
					$excel_datas[] = $excel_data;
					break;
				}
				// 位置号码 生克刑轨迹
				$excel_data['counteract_1'] = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num1)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num1)), TRUE );
				$excel_data['counteract_2'] = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num2)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num2)), TRUE );
				$excel_data['counteract_3'] = get_vertical_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num3)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num3)), TRUE );
				$excel_datas[] = $excel_data;
			}
		}
		/* 
		生肖名 - 左右斜
		生肖名 - 对角线左右斜
		*/
		elseif($status == 'sx_zy' OR $status == 'sx_diagonal_zy') 
		{
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs->kgny;
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1.' '.get_shengxiaoma($rs->sx_year,$rs->num1) : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2.' '.get_shengxiaoma($rs->sx_year,$rs->num2) : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3.' '.get_shengxiaoma($rs->sx_year,$rs->num3) : '';
				$excel_data['num4'] = (isset($rs->num4)) ? $rs->num4.' '.get_shengxiaoma($rs->sx_year,$rs->num4) : '';
				$excel_data['num5'] = (isset($rs->num5)) ? $rs->num5.' '.get_shengxiaoma($rs->sx_year,$rs->num5) : '';
				$excel_data['num6'] = (isset($rs->num6)) ? $rs->num6.' '.get_shengxiaoma($rs->sx_year,$rs->num6) : '';
				// 生肖名 - 左右斜 生克刑轨迹
				$excel_data['counteract_1'] = (isset($rs->num1) && isset($rs->num2)) ? get_sx_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)), TRUE ) : '';
				$excel_data['counteract_2'] = (isset($rs->num3) && isset($rs->num4)) ? get_sx_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num4)), TRUE ) : '';
				if($status == 'sx_diagonal_zy') {
					$excel_data['counteract_2'] = (isset($rs->num1) && isset($rs->num3)) ? get_sx_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), TRUE ) : '';
				}
				$excel_data['counteract_3'] = (isset($rs->num5) && isset($rs->num6)) ? get_sx_counteract( get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num5)), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num6)), TRUE ) : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'sx_order') // 生肖名 - 同顺序垂直关系
		{
			$rs = $result->result();
			$rs_count = count($rs);
			for($i=0; $i < $rs_count; $i++)
			{
				if(!isset($rs[$i]->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs[$i]->pid)) ? $rs[$i]->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs[$i]->created)) ? $rs[$i]->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs[$i]->lottery_qh)) ? $rs[$i]->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs[$i]->kgny;
				// 中奖号码
				$excel_data['num1'] = (isset($rs[$i]->num1)) ? $rs[$i]->num1.' '.get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num1) : '';
				$excel_data['num2'] = (isset($rs[$i]->num2)) ? $rs[$i]->num2.' '.get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num2) : '';
				$excel_data['num3'] = (isset($rs[$i]->num3)) ? $rs[$i]->num3.' '.get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num3) : '';

				if(!isset($rs[$i+1])) {
					$excel_datas[] = $excel_data;
					break;
				}

				// 生肖名 - 同顺序箭头轨迹
				$excel_data['arrow1'] = get_sx_order_arrow(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num1), get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num1), TRUE );
				$excel_data['arrow2'] = get_sx_order_arrow(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num2), get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num2), TRUE );
				$excel_data['arrow3'] = get_sx_order_arrow(get_shengxiaoma($rs[$i]->sx_year,$rs[$i]->num3), get_shengxiaoma($rs[$i+1]->sx_year,$rs[$i+1]->num3), TRUE );

				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'sx_order_diagonal') // 生肖名 - 同顺序对角线
		{
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs->kgny;
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1.' '.get_shengxiaoma($rs->sx_year,$rs->num1) : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2.' '.get_shengxiaoma($rs->sx_year,$rs->num2) : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3.' '.get_shengxiaoma($rs->sx_year,$rs->num3) : '';
				// 生肖名 - 同顺序对角线箭头轨迹对应
				$excel_data['arrow1'] = (isset($rs->num2)) ? get_sx_order_diagonal_arrow(get_shengxiaoma($rs->sx_year,$rs->num1), get_shengxiaoma($rs->sx_year,$rs->num2), TRUE) : '';
				$excel_data['arrow2'] = (isset($rs->num3)) ? get_sx_order_diagonal_arrow(get_shengxiaoma($rs->sx_year,$rs->num2), get_shengxiaoma($rs->sx_year,$rs->num3), TRUE) : '';
				
				$excel_datas[] = $excel_data;
			}
		}
		// 等距加法、乘法、减法、绝对值系统 - 位号垂直
		elseif($status == 'plus_weihao_vertical' OR $status == 'multiply_weihao_vertical' OR $status == 'minus_weihao_vertical' OR $status == 'abs_weihao_vertical') 
		{
			// 想调用特定的excel导出函数的判断条件
			$excel_define = TRUE;
			$status_prefix = substr($status, 0,strpos($status, '_'));
			if($status_prefix == 'plus') $sign = '+';
			elseif($status_prefix == 'minus' OR $status_prefix == 'abs') $sign = '减';
			elseif($status_prefix == 'multiply') $sign = '乘';
			$status_suffix = substr($status, strpos($status, '_')+1);

			$rs = $result->result();
			$rs_count = count($rs);
			for($i=$rs_count-1; $i >= 0; $i--)
			{
				if(!isset($rs[$i]->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs[$i]->pid)) ? $rs[$i]->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs[$i]->created)) ? $rs[$i]->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs[$i]->lottery_qh)) ? $rs[$i]->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs[$i]->kgny;
				// 符号
				$excel_data['sign'] = $sign;

				if( ! isset($rs[$i-1])) {
					$excel_data['result_1'] = $rs[$i]->num1;
					$excel_data['result_2'] = $rs[$i]->num2;
					$excel_data['result_3'] = $rs[$i]->num3;
					$excel_datas[] = $excel_data;
					break;
				}
				// 等距加法系统 - 位号垂直
				$excel_data['result_1'] = get_weishu_pm($rs[$i-1]->num1, $rs[$i]->num1, $status);
				$excel_data['result_2'] = get_weishu_pm($rs[$i-1]->num2, $rs[$i]->num2, $status);
				$excel_data['result_3'] = get_weishu_pm($rs[$i-1]->num3, $rs[$i]->num3, $status);

				$excel_datas[] = $excel_data;
			}
			$excel_datas = array_reverse($excel_datas);
			$this->excel->plus_multiplication_writer($status_suffix,$excel_datas,$excel_data_count);
		}
		// 等距加法、乘法、减法、绝对值系统 - 位号左右斜
		elseif($status == 'plus_weihao_zy' OR $status == 'multiply_weihao_zy' OR $status == 'minus_weihao_zy' OR $status == 'abs_weihao_zy') 
		{
			// 想调用特定的excel导出函数的判断条件
			$excel_define = TRUE;
			$status_prefix = substr($status, 0,strpos($status, '_'));
			if($status_prefix == 'plus') $sign = '+';
			elseif($status_prefix == 'minus' OR $status_prefix == 'abs') $sign = '减';
			elseif($status_prefix == 'multiply') $sign = '乘';
			$status_suffix = substr($status, strpos($status, '_')+1);

			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs->kgny;
				// 符号
				$excel_data['sign'] = $sign;
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_data['num4'] = (isset($rs->num4)) ? $rs->num4 : '';
				$excel_data['num5'] = (isset($rs->num5)) ? $rs->num5 : '';
				$excel_data['num6'] = (isset($rs->num6)) ? $rs->num6 : '';
				// 等距加法系统 - 位号左右斜
				$excel_data['result_1'] = (isset($rs->num2)) ? get_weishu_pm($rs->num1, $rs->num2,$status) : '';
				$excel_data['result_2'] = (isset($rs->num3) && isset($rs->num4)) ? get_weishu_pm($rs->num3, $rs->num4,$status) : '';
				$excel_data['result_3'] = (isset($rs->num5) && isset($rs->num6)) ? get_weishu_pm($rs->num5, $rs->num6, $status) : '';
				$excel_datas[] = $excel_data;
			}
			$this->excel->plus_multiplication_writer($status_suffix,$excel_datas,$excel_data_count);
		}
		// 等距加法、减法、乘法、绝对值系统 - 位号对角线左右斜
		elseif($status == 'plus_weihao_diagonal_zy' OR $status == 'multiply_weihao_diagonal_zy' OR $status == 'minus_weihao_diagonal_zy' OR $status == 'abs_weihao_diagonal_zy') 
		{
			// 想调用特定的excel导出函数的判断条件
			$excel_define = TRUE;
			$status_prefix = substr($status, 0,strpos($status, '_'));
			if($status_prefix == 'plus') $sign = '+';
			elseif($status_prefix == 'minus' OR $status_prefix == 'abs') $sign = '减';
			elseif($status_prefix == 'multiply') $sign = '乘';
			$status_suffix = substr($status, strpos($status, '_')+1);

			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs->kgny;
				// 符号
				$excel_data['sign'] = $sign;
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				// 等距加法系统 - 位号对角线左右斜
				$excel_data['result_1'] = (isset($rs->num2)) ? get_weishu_pm($rs->num1, $rs->num2,$status) : '';
				$excel_data['result_2'] = (isset($rs->num3)) ? get_weishu_pm($rs->num1, $rs->num3,$status) : '';
				$excel_datas[] = $excel_data;
			}
			$this->excel->plus_multiplication_writer($status_suffix,$excel_datas,$excel_data_count);
		}
		elseif($status == 'zhuti_fuma_3') // 主题副码 - 位号右斜系统
		{
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = $rs->kgny;
				// 中奖号码
				$excel_data['lottery_number'] = $rs->lottery_number;
				// 主题副码
				$excel_data['zhuti_fuma'] = $rs->zhuti_fuma;
				
				// 每列的中奖号码 与 右斜的主题副码 发生“正”，‘反’关系
				$excel_data['a'] = get_zf_check2(isset($rs->lottery_number_2) ? $rs->lottery_number_2 : '', $rs->lottery_number[0]);
				$excel_data['b'] = get_zf_check2(isset($rs->lottery_number_2) ? $rs->lottery_number_2 : '', $rs->lottery_number[1]);
				$excel_data['c'] = get_zf_check2(isset($rs->lottery_number_2) ? $rs->lottery_number_2 : '', $rs->lottery_number[2]);
				$excel_datas[] = $excel_data;
			}
		}
		
		if( ! $excel_define) $this->excel->counteract_writer($status,$excel_datas,$excel_data_count);
     }

}

/* End of file counteract.php */
/* Location: ./application/controllers/admin/counteract.php */