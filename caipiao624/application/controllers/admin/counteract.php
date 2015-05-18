<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * STBLOG Posts Controller Class
 *
 * 相生相克表
 *
 */
class Counteract extends ST_Auth_Controller
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
	public function manage($status = 'rownum')
	{
		/** 默认标题 */
		$this->_data['page_title'] = '管理文章';
		/** 已发布，待审核 状态表示变量 **/
		$this->_data['status'] = $status;
		/** 分页的query string */
		$query = array();


		/** check status */
		if(!in_array($status, array('rownum','tiangan','dizhi','nayin','week','x_week','lottery_qh','suishu','jgx','lunar','solar','sx_tiangan','sx_dizhi','sx_nayin','pi',
									'plus_lunar','plus_jgx','plus_week','plus_solar','plus_xc_suishu','plus_pid','plus_lottery_qh','plus_pi',
									'multiply_lunar','multiply_jgx','multiply_week','multiply_solar','multiply_xc_suishu','multiply_pid','multiply_lottery_qh','multiply_pi',
									'minus_lunar','minus_jgx','minus_week','minus_solar','minus_xc_suishu','minus_pid','minus_lottery_qh','minus_pi',
									'abs_lunar','abs_jgx','abs_week','abs_solar','abs_xc_suishu','abs_pid','abs_lottery_qh','abs_pi',
									'zhuti_fuma','zhuti_fuma_2')))
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
		/** 选择主题 */
		//$zhutis_filter = $this->input->get('zhutis', TRUE);
		//$zhutis_filter = (!empty($zhutis_filter)) ? $zhutis_filter : 0;
		//if(!empty($zhutis_filter)) 
		//{
			//$query[] = 'zhutis='.$zhutis_filter;
		//}

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
			$result->result_object = (Object)(array_reverse($rs));
			unset($ars, $rs);
		}
		else
		{
			$result = $this->counteract_mdl->get_threed($limit, $offset, '',$years_filter,$col_filter,$weishu_filter,$nayins_filter,$zhutis_filter);
			$result_count = $this->counteract_mdl->get_threed(20000,0,'',$years_filter,$col_filter,$weishu_filter,$nayins_filter,$zhutis_filter)->num_rows();
		}
		
		// 左斜 处理
		if($airt_filter == 'left_x') 
		{
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
				$values[$key]->week				= $value->week;// 中式星期
				$values[$key]->x_week			= $this->x_week[$value->week];// 西式星期
				$values[$key]->xc_suishu		= $value->xc_suishu;
				$values[$key]->tiangan			= $value->tiangan;
				$values[$key]->dizhi			= $value->dizhi;
				$values[$key]->kgny				= $value->kgny;
				$values[$key]->xc_nayin_z		= $value->xc_nayin_z;
				$values[$key]->xc_nayin_c		= $value->xc_nayin_c;
				$values[$key]->xc_ganzhi_z_1	= $value->xc_ganzhi_z_1;
				$values[$key]->xc_ganzhi_z_2	= $value->xc_ganzhi_z_2;
				$values[$key]->xc_ganzhi_c_1	= $value->xc_ganzhi_c_1;
				$values[$key]->xc_ganzhi_c_2	= $value->xc_ganzhi_c_2;
				$values[$key]->jgx				= $value->jgx;
				$values[$key]->lunar			= $value->lunar;
				$values[$key]->solar			= $value->solar;
				$values[$key]->sx_year			= $value->sx_year;

				if($key > 0) 
				{
					$values[$key]->num1	= $rs[$key-1]->num1;
					$values[$key]->num2	= $rs[$key-1]->num2;
					$values[$key]->num3	= $rs[$key-1]->num3;
				}

				$air_k[$key] = $keys[$key];
				$air_v[$key] = $values[$key];
				
				$airs->$key = (object)((array)$air_k[$key] + (array)$air_v[$key]);
			}

			$result->result_object = $airs;
			unset($rs, $keys, $values, $air_k, $air_v, $airs);
		}
		// 右斜 处理
		else if($airt_filter == 'right_x')
		{
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
				$values[$key]->created		= $value->created;
				$values[$key]->lottery_qh		= $value->lottery_qh;
				$values[$key]->week				= $value->week;// 中式星期
				$values[$key]->x_week			= $this->x_week[$value->week];// 西式星期
				$values[$key]->xc_suishu		= $value->xc_suishu;
				$values[$key]->tiangan			= $value->tiangan;
				$values[$key]->dizhi			= $value->dizhi;
				$values[$key]->kgny				= $value->kgny;
				$values[$key]->xc_nayin_z		= $value->xc_nayin_z;
				$values[$key]->xc_nayin_c		= $value->xc_nayin_c;
				$values[$key]->xc_ganzhi_z_1	= $value->xc_ganzhi_z_1;
				$values[$key]->xc_ganzhi_z_2	= $value->xc_ganzhi_z_2;
				$values[$key]->xc_ganzhi_c_1	= $value->xc_ganzhi_c_1;
				$values[$key]->xc_ganzhi_c_2	= $value->xc_ganzhi_c_2;
				$values[$key]->jgx				= $value->jgx;
				$values[$key]->lunar			= $value->lunar;
				$values[$key]->solar			= $value->solar;
				$values[$key]->sx_year			= $value->sx_year;

				$values[$key]->num1				= (isset($rs[$key+1]->num1)) ? $rs[$key+1]->num1 : '';
				$values[$key]->num2				= (isset($rs[$key+1]->num2)) ? $rs[$key+1]->num2 : '';
				$values[$key]->num3				= (isset($rs[$key+1]->num3)) ? $rs[$key+1]->num3 : '';
				
				
				$air_k[$key] = $keys[$key];
				$air_v[$key] = $values[$key];
				
				$airs->$key = (object)((array)$air_k[$key] + (array)$air_v[$key]);				
				//echo $key.'--'.$air_k[$key]->pid.'---'.$air_v[$key]->lottery_number.'<br>';
			}
			
			$result->result_object = $airs;
			unset($rs, $keys, $values, $air_k, $air_v, $airs);
		}

		if($result)
		{
			$pagination = '';
			
			if($result_count > $limit)
			{	
				if ($status == 'rownum') $page_status = 'rownum';
				elseif ($status == 'tiangan') $page_status = 'tiangan';
				elseif ($status == 'dizhi') $page_status = 'dizhi';
				elseif ($status == 'nayin') $page_status = 'nayin';
				elseif ($status == 'week') $page_status = 'week';
				elseif ($status == 'x_week') $page_status = 'x_week';
				elseif ($status == 'lottery_qh') $page_status = 'lottery_qh';
				elseif ($status == 'suishu') $page_status = 'suishu';
				elseif ($status == 'jgx') $page_status = 'jgx';
				elseif ($status == 'lunar') $page_status = 'lunar';
				elseif ($status == 'solar') $page_status = 'solar';
				elseif ($status == 'sx_tiangan') $page_status = 'sx_tiangan';
				elseif ($status == 'sx_dizhi') $page_status = 'sx_dizhi';
				elseif ($status == 'sx_nayin') $page_status = 'sx_nayin';
				elseif ($status == 'plus_lunar') $page_status = 'plus_lunar';
				elseif ($status == 'plus_jgx') $page_status = 'plus_jgx';
				elseif ($status == 'plus_week') $page_status = 'plus_week';
				elseif ($status == 'plus_solar') $page_status = 'plus_solar';
				elseif ($status == 'plus_xc_suishu') $page_status = 'plus_xc_suishu';
				elseif ($status == 'plus_pid') $page_status = 'plus_pid';
				elseif ($status == 'plus_lottery_qh') $page_status = 'plus_lottery_qh';
				elseif ($status == 'plus_pi') $page_status = 'plus_pi';
				elseif ($status == 'multiply_lunar') $page_status = 'multiply_lunar';
				elseif ($status == 'multiply_jgx') $page_status = 'multiply_jgx';
				elseif ($status == 'multiply_week') $page_status = 'multiply_week';
				elseif ($status == 'multiply_solar') $page_status = 'multiply_solar';
				elseif ($status == 'multiply_xc_suishu') $page_status = 'multiply_xc_suishu';
				elseif ($status == 'multiply_pid') $page_status = 'multiply_pid';
				elseif ($status == 'multiply_lottery_qh') $page_status = 'multiply_lottery_qh';
				elseif ($status == 'minus_lunar') $page_status = 'minus_lunar';
				elseif ($status == 'minus_jgx') $page_status = 'minus_jgx';
				elseif ($status == 'minus_week') $page_status = 'minus_week';
				elseif ($status == 'minus_solar') $page_status = 'minus_solar';
				elseif ($status == 'minus_xc_suishu') $page_status = 'minus_xc_suishu';
				elseif ($status == 'minus_pid') $page_status = 'minus_pid';
				elseif ($status == 'minus_lottery_qh') $page_status = 'minus_lottery_qh';
				elseif ($status == 'abs_lunar') $page_status = 'abs_lunar';
				elseif ($status == 'abs_jgx') $page_status = 'abs_jgx';
				elseif ($status == 'abs_week') $page_status = 'abs_week';
				elseif ($status == 'abs_solar') $page_status = 'abs_solar';
				elseif ($status == 'abs_xc_suishu') $page_status = 'abs_xc_suishu';
				elseif ($status == 'abs_pid') $page_status = 'abs_pid';
				elseif ($status == 'abs_lottery_qh') $page_status = 'abs_lottery_qh';
				

				$this->dpagination->currentPage($page);
				$this->dpagination->items($result_count);
				$this->dpagination->limit($limit);
				$this->dpagination->adjacents(5);
				$this->dpagination->target(site_url("admin/counteract/manage/$page_status?".implode('&',$query)));
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

		if($status == 'rownum') {
			$this->_data['page_title'] = '总期号系统';
			$this->load->view('admin/counteract_3d_pid', $this->_data);
		}elseif($status == 'tiangan') {
			$this->_data['page_title'] = '天干主系统';
			$this->load->view('admin/counteract_3d_tiangan', $this->_data);
		}elseif($status == 'dizhi') {
			$this->_data['page_title'] = '地支主系统';
			$this->load->view('admin/counteract_3d_dizhi', $this->_data);
		}elseif($status == 'nayin') {
			$this->_data['page_title'] = '纳音主系统';
			$this->load->view('admin/counteract_3d_nayin', $this->_data);
		}elseif($status == 'week') {
			$this->_data['page_title'] = '中式星期主系统';
			$this->load->view('admin/counteract_3d_week', $this->_data);
		}elseif($status == 'x_week') {
			$this->_data['page_title'] = '西式星期主系统';
			$this->_data['x_week'] = $this->x_week; // 传递西式星期数组
			$this->load->view('admin/counteract_3d_x_week', $this->_data);
		}elseif($status == 'lottery_qh') {
			$this->_data['page_title'] = '年度期号主系统';
			$this->load->view('admin/counteract_3d_lottery_qh', $this->_data);
		}elseif($status == 'suishu') {
			$this->_data['page_title'] = '相冲岁数主系统';
			$this->load->view('admin/counteract_3d_suishu', $this->_data);
		}elseif($status == 'jgx') {
			$this->_data['page_title'] = '九宫星主系统';
			$this->load->view('admin/counteract_3d_jgx', $this->_data);
		}elseif($status == 'lunar') {
			$this->_data['page_title'] = '农历主系统';
			$this->load->view('admin/counteract_3d_lunar', $this->_data);
		}elseif($status == 'solar') {
			$this->_data['page_title'] = '新历主系统';
			$this->load->view('admin/counteract_3d_solar', $this->_data);
		}elseif($status == 'sx_tiangan') {
			$this->_data['page_title'] = '生肖 - 天干 主系统';
			$this->load->view('admin/counteract_3d_sx_tiangan', $this->_data);
		}elseif($status == 'sx_dizhi') {
			$this->_data['page_title'] = '生肖 - 地支 主系统';
			$this->load->view('admin/counteract_3d_sx_dizhi', $this->_data);
		}elseif($status == 'sx_nayin') {
			$this->_data['page_title'] = '生肖 - 纳音 主系统';
			$this->load->view('admin/counteract_3d_sx_nayin', $this->_data);
		}elseif($status == 'pi') {
			$this->_data['page_title'] = '圆周率 主系统';
			$this->_data['pi'] = file_get_contents(base_url()."resources/PI.txt");
			$order   = array("\r\n", "\n", "\r");
			$replace = '';
			$this->_data['pi'] = str_replace($order, $replace, $this->_data['pi']);
			$this->load->view('admin/counteract_3d_pi', $this->_data);
		}elseif($status == 'plus_lunar') {
			$this->_data['page_title'] = '等距加法系统 - 农历';
			$this->load->view('admin/counteract_3d_plus_lunar', $this->_data);
		}elseif($status == 'plus_jgx') {
			$this->_data['page_title'] = '等距加法系统 - 九宫星';
			$this->load->view('admin/counteract_3d_plus_jgx', $this->_data);
		}elseif($status == 'plus_week') {
			$this->_data['page_title'] = '等距加法系统 - 中式星期';
			$this->load->view('admin/counteract_3d_plus_week', $this->_data);
		}elseif($status == 'plus_solar') {
			$this->_data['page_title'] = '等距加法系统 - 新历';
			$this->load->view('admin/counteract_3d_plus_solar', $this->_data);
		}elseif($status == 'plus_xc_suishu') {
			$this->_data['page_title'] = '等距加法系统 - 相冲岁数';
			$this->load->view('admin/counteract_3d_plus_xc_suishu', $this->_data);
		}elseif($status == 'plus_pid') {
			$this->_data['page_title'] = '等距加法系统 - 总期号';
			$this->load->view('admin/counteract_3d_plus_pid', $this->_data);
		}elseif($status == 'plus_lottery_qh') {
			$this->_data['page_title'] = '等距加法系统 - 年度期号';
			$this->load->view('admin/counteract_3d_plus_lottery_qh', $this->_data);
		}elseif($status == 'plus_pi') {
			$this->_data['page_title'] = '等距加法系统 - 圆周率';
			$this->_data['pi'] = file_get_contents(base_url()."resources/PI.txt");
			$order   = array("\r\n", "\n", "\r");
			$replace = '';
			$this->_data['pi'] = str_replace($order, $replace, $this->_data['pi']);
			$this->load->view('admin/counteract_3d_plus_pi', $this->_data);
		}elseif($status == 'multiply_lunar') {
			$this->_data['page_title'] = '等距乘法系统 - 农历';
			$this->load->view('admin/counteract_3d_multiply_lunar', $this->_data);
		}elseif($status == 'multiply_jgx') {
			$this->_data['page_title'] = '等距乘法系统 - 九宫星';
			$this->load->view('admin/counteract_3d_multiply_jgx', $this->_data);
		}elseif($status == 'multiply_week') {
			$this->_data['page_title'] = '等距乘法系统 - 中式星期';
			$this->load->view('admin/counteract_3d_multiply_week', $this->_data);
		}elseif($status == 'multiply_solar') {
			$this->_data['page_title'] = '等距乘法系统 - 新历';
			$this->load->view('admin/counteract_3d_multiply_solar', $this->_data);
		}elseif($status == 'multiply_xc_suishu') {
			$this->_data['page_title'] = '等距乘法系统 - 相岁';
			$this->load->view('admin/counteract_3d_multiply_xc_suishu', $this->_data);
		}elseif($status == 'multiply_pid') {
			$this->_data['page_title'] = '等距乘法系统 - 总期号';
			$this->load->view('admin/counteract_3d_multiply_pid', $this->_data);
		}elseif($status == 'multiply_lottery_qh') {
			$this->_data['page_title'] = '等距乘法系统 - 年度期号';
			$this->load->view('admin/counteract_3d_multiply_lottery_qh', $this->_data);
		}elseif($status == 'multiply_pi') {
			$this->_data['page_title'] = '等距乘法系统 - 圆周率';
			$this->_data['pi'] = file_get_contents(base_url()."resources/PI.txt");
			$order   = array("\r\n", "\n", "\r");
			$replace = '';
			$this->_data['pi'] = str_replace($order, $replace, $this->_data['pi']);
			$this->load->view('admin/counteract_3d_multiply_pi', $this->_data);
		}elseif($status == 'minus_lunar') {
			$this->_data['page_title'] = '等距减法系统 - 农历';
			$this->load->view('admin/counteract_3d_minus_lunar', $this->_data);
		}elseif($status == 'minus_jgx') {
			$this->_data['page_title'] = '等距减法系统 - 九宫星';
			$this->load->view('admin/counteract_3d_minus_jgx', $this->_data);
		}elseif($status == 'minus_week') {
			$this->_data['page_title'] = '等距减法系统 - 中式星期';
			$this->load->view('admin/counteract_3d_minus_week', $this->_data);
		}elseif($status == 'minus_solar') {
			$this->_data['page_title'] = '等距减法系统 - 新历';
			$this->load->view('admin/counteract_3d_minus_solar', $this->_data);
		}elseif($status == 'minus_xc_suishu') {
			$this->_data['page_title'] = '等距减法系统 - 相岁';
			$this->load->view('admin/counteract_3d_minus_xc_suishu', $this->_data);
		}elseif($status == 'minus_pid') {
			$this->_data['page_title'] = '等距减法系统 - 总期号';
			$this->load->view('admin/counteract_3d_minus_pid', $this->_data);
		}elseif($status == 'minus_lottery_qh') {
			$this->_data['page_title'] = '等距减法系统 - 年度期号';
			$this->load->view('admin/counteract_3d_minus_lottery_qh', $this->_data);
		}elseif($status == 'minus_pi') {
			$this->_data['page_title'] = '等距减法系统 - 圆周率';
			$this->_data['pi'] = file_get_contents(base_url()."resources/PI.txt");
			$order   = array("\r\n", "\n", "\r");
			$replace = '';
			$this->_data['pi'] = str_replace($order, $replace, $this->_data['pi']);
			$this->load->view('admin/counteract_3d_minus_pi', $this->_data);
		}elseif($status == 'abs_lunar') {
			$this->_data['page_title'] = '等距绝对值系统 - 农历';
			$this->load->view('admin/counteract_3d_abs_lunar', $this->_data);
		}elseif($status == 'abs_jgx') {
			$this->_data['page_title'] = '等距绝对值系统 - 九宫星';
			$this->load->view('admin/counteract_3d_abs_jgx', $this->_data);
		}elseif($status == 'abs_week') {
			$this->_data['page_title'] = '等距绝对值系统 - 中式星期';
			$this->load->view('admin/counteract_3d_abs_week', $this->_data);
		}elseif($status == 'abs_solar') {
			$this->_data['page_title'] = '等距绝对值系统 - 新历';
			$this->load->view('admin/counteract_3d_abs_solar', $this->_data);
		}elseif($status == 'abs_xc_suishu') {
			$this->_data['page_title'] = '等距绝对值系统 - 相岁';
			$this->load->view('admin/counteract_3d_abs_xc_suishu', $this->_data);
		}elseif($status == 'abs_pid') {
			$this->_data['page_title'] = '等距绝对值系统 - 总期号';
			$this->load->view('admin/counteract_3d_abs_pid', $this->_data);
		}elseif($status == 'abs_lottery_qh') {
			$this->_data['page_title'] = '等距绝对值系统 - 年度期号';
			$this->load->view('admin/counteract_3d_abs_lottery_qh', $this->_data);
		}elseif($status == 'abs_pi') {
			$this->_data['page_title'] = '等距绝对值系统 - 圆周率';
			$this->_data['pi'] = file_get_contents(base_url()."resources/PI.txt");
			$order   = array("\r\n", "\n", "\r");
			$replace = '';
			$this->_data['pi'] = str_replace($order, $replace, $this->_data['pi']);
			$this->load->view('admin/counteract_3d_abs_pi', $this->_data);
		}


		// 测试
		elseif($status == 'zhuti_fuma') {
			$this->_data['page_title'] = '主题副码';
			$this->_data['x_week'] = $this->x_week; // 传递西式星期数组
			$this->load->view('admin/counteract_3d_zhuti_fuma', $this->_data);
		}elseif($status == 'zhuti_fuma_2') {
			$this->_data['page_title'] = '主题副码2';
			$this->_data['lunars'] = $this->lunars; // 传递农历数组
			$this->load->view('admin/counteract_3d_zhuti_fuma_2', $this->_data);
		}
		
	}

	/**
     * 
     * EXCEL 导出
     * 
     */
     public function excel_write($status='rownum', $result='', $excel_data_count=0)
     {
		if(empty($result) || empty($excel_data_count)) return;
		
		ini_set('memory_limit', '200M'); // 默认内存128

        $this->load->library('Classes/PHPExcel');
		$this->load->library('excel');
		$excel_datas = array();
		$excel_define = FALSE;

		if($status == 'rownum') {
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 开干纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 总期号 生克刑轨迹
				$excel_data['pid_counteract_1'] = (isset($rs->num1)) ? get_prefix_nums($rs->pid, $rs->num1, TRUE) : '';
				$excel_data['pid_counteract_2'] = (isset($rs->num2)) ? get_prefix_nums($rs->pid, $rs->num2, TRUE) : '';
				$excel_data['pid_counteract_3'] = (isset($rs->num3)) ? get_prefix_nums($rs->pid, $rs->num3, TRUE) : '';
				// 总期号 纯杂轨迹
				$excel_data['pid_cz_1'] = (isset($rs->num1)) ? get_numeric_cz($rs->pid, $rs->num1, TRUE) : '';
				$excel_data['pid_cz_2'] = (isset($rs->num2)) ? get_numeric_cz($rs->pid, $rs->num2, TRUE) : '';
				$excel_data['pid_cz_3'] = (isset($rs->num3)) ? get_numeric_cz($rs->pid, $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'tiangan') {
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 开干纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 天干
				$excel_data['tiangan'] = (isset($rs->tiangan) && isset($rs->dizhi)) ? 
												   $rs->tiangan.' ('.$rs->dizhi.')' : '';
				// 天干 生克刑轨迹
				$excel_data['tiangan_counteract_1'] = (isset($rs->num1)) ? get_ganzhi_counteract($rs->tiangan, $rs->num1, TRUE) : '';
				$excel_data['tiangan_counteract_2'] = (isset($rs->num2)) ? get_ganzhi_counteract($rs->tiangan, $rs->num2, TRUE) : '';
				$excel_data['tiangan_counteract_3'] = (isset($rs->num3)) ? get_ganzhi_counteract($rs->tiangan, $rs->num3, TRUE) : '';
				// 正相冲天干
				$excel_data['z_tiangan'] = (isset($rs->xc_ganzhi_z_1) && isset($rs->xc_ganzhi_z_2)) ? 
													 $rs->xc_ganzhi_z_1.' ('.$rs->xc_ganzhi_z_2.')' : '';
				// 正相冲天干 生克刑轨迹
				$excel_data['z_tiangan_counteract_1'] = (isset($rs->num1)) ? get_ganzhi_counteract($rs->xc_ganzhi_z_1, $rs->num1, TRUE) : '';
				$excel_data['z_tiangan_counteract_2'] = (isset($rs->num2)) ? get_ganzhi_counteract($rs->xc_ganzhi_z_1, $rs->num2, TRUE) : '';
				$excel_data['z_tiangan_counteract_3'] = (isset($rs->num3)) ? get_ganzhi_counteract($rs->xc_ganzhi_z_1, $rs->num3, TRUE) : '';
				// 次相冲天干
				$excel_data['c_tiangan'] = (isset($rs->xc_ganzhi_c_1) && isset($rs->xc_ganzhi_c_2)) ? 
													 $rs->xc_ganzhi_c_1.' ('.$rs->xc_ganzhi_c_2.')' : '';
				// 次相冲天干 生克刑轨迹
				$excel_data['c_tiangan_counteract_1'] = (isset($rs->num1)) ? get_ganzhi_counteract($rs->xc_ganzhi_c_1, $rs->num1, TRUE) : '';
				$excel_data['c_tiangan_counteract_2'] = (isset($rs->num2)) ? get_ganzhi_counteract($rs->xc_ganzhi_c_1, $rs->num2, TRUE) : '';
				$excel_data['c_tiangan_counteract_3'] = (isset($rs->num3)) ? get_ganzhi_counteract($rs->xc_ganzhi_c_1, $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'dizhi') 
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
				// 开干纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 地支
				$excel_data['dizhi'] = (isset($rs->tiangan) && isset($rs->dizhi)) ? 
												   '('.$rs->tiangan.') '.$rs->dizhi : '';
				// 地支 生克刑轨迹
				$excel_data['dizhi_counteract_1'] = (isset($rs->num1)) ? get_ganzhi_counteract($rs->dizhi, $rs->num1, TRUE) : '';
				$excel_data['dizhi_counteract_2'] = (isset($rs->num2)) ? get_ganzhi_counteract($rs->dizhi, $rs->num2, TRUE) : '';
				$excel_data['dizhi_counteract_3'] = (isset($rs->num3)) ? get_ganzhi_counteract($rs->dizhi, $rs->num3, TRUE) : '';
				// 正相冲地支
				$excel_data['z_dizhi'] = (isset($rs->xc_ganzhi_z_1) && isset($rs->xc_ganzhi_z_2)) ? 
												   '('.$rs->xc_ganzhi_z_1.') '.$rs->xc_ganzhi_z_2 : '';
				// 正相冲地支 生克刑轨迹
				$excel_data['z_dizhi_counteract_1'] = (isset($rs->num1)) ? get_ganzhi_counteract($rs->xc_ganzhi_z_2, $rs->num1, TRUE) : '';
				$excel_data['z_dizhi_counteract_2'] = (isset($rs->num2)) ? get_ganzhi_counteract($rs->xc_ganzhi_z_2, $rs->num2, TRUE) : '';
				$excel_data['z_dizhi_counteract_3'] = (isset($rs->num3)) ? get_ganzhi_counteract($rs->xc_ganzhi_z_2, $rs->num3, TRUE) : '';
				// 次相冲地支
				$excel_data['c_dizhi'] = (isset($rs->xc_ganzhi_c_1) && isset($rs->xc_ganzhi_c_2)) ? 
												  '('. $rs->xc_ganzhi_c_1.') '.$rs->xc_ganzhi_c_2 : '';
				// 次相冲地支 生克刑轨迹
				$excel_data['c_dizhi_counteract_1'] = (isset($rs->num1)) ? get_ganzhi_counteract($rs->xc_ganzhi_c_2, $rs->num1, TRUE) : '';
				$excel_data['c_dizhi_counteract_2'] = (isset($rs->num2)) ? get_ganzhi_counteract($rs->xc_ganzhi_c_2, $rs->num2, TRUE) : '';
				$excel_data['c_dizhi_counteract_3'] = (isset($rs->num3)) ? get_ganzhi_counteract($rs->xc_ganzhi_c_2, $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'nayin') 
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
				// 纳音 生克刑轨迹
				$excel_data['nayin_counteract_1'] = (isset($rs->num1)) ? get_nayin_counteract($rs->kgny, $rs->num1, TRUE) : '';
				$excel_data['nayin_counteract_2'] = (isset($rs->num2)) ? get_nayin_counteract($rs->kgny, $rs->num2, TRUE) : '';
				$excel_data['nayin_counteract_3'] = (isset($rs->num3)) ? get_nayin_counteract($rs->kgny, $rs->num3, TRUE) : '';
				// 正相冲纳音
				$excel_data['z_nayin'] = $rs->xc_nayin_z;
				// 正相冲纳音 生克刑轨迹
				$excel_data['z_nayin_counteract_1'] = (isset($rs->num1)) ? get_nayin_counteract($rs->xc_nayin_z, $rs->num1, TRUE) : '';
				$excel_data['z_nayin_counteract_2'] = (isset($rs->num2)) ? get_nayin_counteract($rs->xc_nayin_z, $rs->num2, TRUE) : '';
				$excel_data['z_nayin_counteract_3'] = (isset($rs->num3)) ? get_nayin_counteract($rs->xc_nayin_z, $rs->num3, TRUE) : '';
				// 次相冲纳音
				$excel_data['c_nayin'] = $rs->xc_nayin_c;
				// 次相冲纳音 生克刑轨迹
				$excel_data['c_nayin_counteract_1'] = (isset($rs->num1)) ? get_nayin_counteract($rs->xc_nayin_c, $rs->num1, TRUE) : '';
				$excel_data['c_nayin_counteract_2'] = (isset($rs->num2)) ? get_nayin_counteract($rs->xc_nayin_c, $rs->num2, TRUE) : '';
				$excel_data['c_nayin_counteract_3'] = (isset($rs->num3)) ? get_nayin_counteract($rs->xc_nayin_c, $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'week') // 中式星期
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
				// 中式星期
				$excel_data['week'] = $rs->week;
				// 中式星期 生克刑轨迹
				$excel_data['week_counteract_1'] = (isset($rs->num1)) ? get_week_counteract($rs->week, $rs->num1, TRUE) : '';
				$excel_data['week_counteract_2'] = (isset($rs->num2)) ? get_week_counteract($rs->week, $rs->num2, TRUE) : '';
				$excel_data['week_counteract_3'] = (isset($rs->num3)) ? get_week_counteract($rs->week, $rs->num3, TRUE) : '';
				// 中式星期 纯杂轨迹
				$excel_data['week_cz_1'] = (isset($rs->num1)) ? get_week_cz($rs->week, $rs->num1, TRUE) : '';
				$excel_data['week_cz_2'] = (isset($rs->num2)) ? get_week_cz($rs->week, $rs->num2, TRUE) : '';
				$excel_data['week_cz_3'] = (isset($rs->num3)) ? get_week_cz($rs->week, $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'x_week') // 西式星期
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
				// 西式星期, $rs->x_week是左右斜处理的结果
				$excel_data['x_week'] = (isset($rs->x_week)) ? $rs->x_week : $this->x_week[$rs->week];
				// 西式星期 生克刑轨迹
				$excel_data['week_counteract_1'] = (isset($rs->num1)) ? get_x_week_counteract($excel_data['x_week'], $rs->num1, TRUE) : '';
				$excel_data['week_counteract_2'] = (isset($rs->num2)) ? get_x_week_counteract($excel_data['x_week'], $rs->num2, TRUE) : '';
				$excel_data['week_counteract_3'] = (isset($rs->num3)) ? get_x_week_counteract($excel_data['x_week'], $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'lottery_qh') // 年度期号
		{
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 纳音
				$excel_data['kgny'] = $rs->kgny;
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 年度期号 生克刑轨迹
				$excel_data['qh_counteract_1'] = (isset($rs->num1)) ? get_prefix_nums($excel_data['lottery_qh'], $rs->num1, TRUE) : '';
				$excel_data['qh_counteract_2'] = (isset($rs->num2)) ? get_prefix_nums($excel_data['lottery_qh'], $rs->num2, TRUE) : '';
				$excel_data['qh_counteract_3'] = (isset($rs->num3)) ? get_prefix_nums($excel_data['lottery_qh'], $rs->num3, TRUE) : '';
				// 年度期号 纯杂轨迹
				$excel_data['qh_cz_1'] = (isset($rs->num1)) ? get_numeric_cz($rs->lottery_qh, $rs->num1, TRUE) : '';
				$excel_data['qh_cz_2'] = (isset($rs->num2)) ? get_numeric_cz($rs->lottery_qh, $rs->num2, TRUE) : '';
				$excel_data['qh_cz_3'] = (isset($rs->num3)) ? get_numeric_cz($rs->lottery_qh, $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'pi') // 圆周率
		{
			// 圆周率
			$pi		 = file_get_contents(base_url()."resources/PI.txt");
			$order   = array("\r\n", "\n", "\r");
			$replace = '';
			$pi = str_replace($order, $replace, $pi);
			foreach($result->result() as $k=>$rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 纳音
				$excel_data['kgny'] = $rs->kgny;
				//圆周率
				$excel_data['pi'] = (isset($pi)) ? $pi[$k] : '';
				// 年度期号 生克刑轨迹
				$excel_data['counteract_1'] = (isset($rs->num1)) ? get_prefix_nums($excel_data['pi'], $rs->num1, TRUE) : '';
				$excel_data['counteract_2'] = (isset($rs->num2)) ? get_prefix_nums($excel_data['pi'], $rs->num2, TRUE) : '';
				$excel_data['counteract_3'] = (isset($rs->num3)) ? get_prefix_nums($excel_data['pi'], $rs->num3, TRUE) : '';
				// 年度期号 纯杂轨迹
				$excel_data['cz_1'] = (isset($rs->num1)) ? get_numeric_cz($excel_data['pi'], $rs->num1, TRUE) : '';
				$excel_data['cz_2'] = (isset($rs->num2)) ? get_numeric_cz($excel_data['pi'], $rs->num2, TRUE) : '';
				$excel_data['cz_3'] = (isset($rs->num3)) ? get_numeric_cz($excel_data['pi'], $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'suishu') // 相冲岁数
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
				// 岁数
				$excel_data['xc_suishu'] = $rs->xc_suishu;
				// 岁数 生克刑轨迹
				$excel_data['suishu_counteract_1'] = (isset($rs->num1)) ? get_prefix_nums($excel_data['xc_suishu'], $rs->num1, TRUE) : '';
				$excel_data['suishu_counteract_2'] = (isset($rs->num2)) ? get_prefix_nums($excel_data['xc_suishu'], $rs->num2, TRUE) : '';
				$excel_data['suishu_counteract_3'] = (isset($rs->num3)) ? get_prefix_nums($excel_data['xc_suishu'], $rs->num3, TRUE) : '';
				// 岁数 纯杂轨迹
				$excel_data['suishu_cz_1'] = (isset($rs->num1)) ? get_numeric_cz($rs->xc_suishu, $rs->num1, TRUE) : '';
				$excel_data['suishu_cz_2'] = (isset($rs->num2)) ? get_numeric_cz($rs->xc_suishu, $rs->num2, TRUE) : '';
				$excel_data['suishu_cz_3'] = (isset($rs->num3)) ? get_numeric_cz($rs->xc_suishu, $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'jgx') // 九宫星
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
				// 九宫星
				$excel_data['jgx'] = $rs->jgx;
				// 九宫星 生克刑轨迹
				$excel_data['jgx_counteract_1'] = (isset($rs->num1)) ? get_jgx_counteract($excel_data['jgx'], $rs->num1, TRUE) : '';
				$excel_data['jgx_counteract_2'] = (isset($rs->num2)) ? get_jgx_counteract($excel_data['jgx'], $rs->num2, TRUE) : '';
				$excel_data['jgx_counteract_3'] = (isset($rs->num3)) ? get_jgx_counteract($excel_data['jgx'], $rs->num3, TRUE) : '';
				// 九宫星 纯杂轨迹
				$excel_data['jgx_cz_1'] = (isset($rs->num1)) ? get_jgx_cz($excel_data['jgx'], $rs->num1, TRUE) : '';
				$excel_data['jgx_cz_2'] = (isset($rs->num2)) ? get_jgx_cz($excel_data['jgx'], $rs->num2, TRUE) : '';
				$excel_data['jgx_cz_3'] = (isset($rs->num3)) ? get_jgx_cz($excel_data['jgx'], $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'lunar') // 农历
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
				// 农历
				$excel_data['lunar'] = $rs->lunar;
				// 农历 生克刑轨迹
				$excel_data['lunar_counteract_1'] = (isset($rs->num1)) ? get_lunar_counteract($excel_data['lunar'], $rs->num1, TRUE) : '';
				$excel_data['lunar_counteract_2'] = (isset($rs->num2)) ? get_lunar_counteract($excel_data['lunar'], $rs->num2, TRUE) : '';
				$excel_data['lunar_counteract_3'] = (isset($rs->num3)) ? get_lunar_counteract($excel_data['lunar'], $rs->num3, TRUE) : '';
				// 农历 纯杂轨迹
				$excel_data['lunar_cz_1'] = (isset($rs->num1)) ? get_lunar_cz($excel_data['lunar'], $rs->num1, TRUE) : '';
				$excel_data['lunar_cz_2'] = (isset($rs->num2)) ? get_lunar_cz($excel_data['lunar'], $rs->num2, TRUE) : '';
				$excel_data['lunar_cz_3'] = (isset($rs->num3)) ? get_lunar_cz($excel_data['lunar'], $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'solar') // 新历
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
				// 新历
				$excel_data['solar'] = $rs->solar;
				// 新历 生克刑轨迹
				$excel_data['solar_counteract_1'] = (isset($rs->num1)) ? get_prefix_nums($excel_data['solar'], $rs->num1, TRUE) : '';
				$excel_data['solar_counteract_2'] = (isset($rs->num2)) ? get_prefix_nums($excel_data['solar'], $rs->num2, TRUE) : '';
				$excel_data['solar_counteract_3'] = (isset($rs->num3)) ? get_prefix_nums($excel_data['solar'], $rs->num3, TRUE) : '';
				// 新历 纯杂轨迹
				$excel_data['solar_cz_1'] = (isset($rs->num1)) ? get_numeric_cz($excel_data['solar'], $rs->num1, TRUE) : '';
				$excel_data['solar_cz_2'] = (isset($rs->num2)) ? get_numeric_cz($excel_data['solar'], $rs->num2, TRUE) : '';
				$excel_data['solar_cz_3'] = (isset($rs->num3)) ? get_numeric_cz($excel_data['solar'], $rs->num3, TRUE) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1 : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2 : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3 : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'sx_tiangan') { // 生肖 - 天干
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 开干纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 天干
				$excel_data['tiangan'] = (isset($rs->tiangan) && isset($rs->dizhi)) ? 
												   $rs->tiangan.' ('.$rs->dizhi.')' : '';
				// 生肖 - 天干 生克刑轨迹
				$excel_data['sx_tiangan_c_1'] = (isset($rs->num1)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->tiangan), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), TRUE ) : '';
				$excel_data['sx_tiangan_c_2'] = (isset($rs->num2)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->tiangan), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)), TRUE ) : '';
				$excel_data['sx_tiangan_c_3'] = (isset($rs->num3)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->tiangan), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), TRUE ) : '';
				// 正相冲天干
				$excel_data['z_tiangan'] = (isset($rs->xc_ganzhi_z_1) && isset($rs->xc_ganzhi_z_2)) ? 
													 $rs->xc_ganzhi_z_1.' ('.$rs->xc_ganzhi_z_2.')' : '';
				// 生肖 - 正相冲天干 生克刑轨迹
				$excel_data['sx_z_tiangan_c_1'] = (isset($rs->num1)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_z_1), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), TRUE ) : '';
				$excel_data['sx_z_tiangan_c_2'] = (isset($rs->num2)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_z_1), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)), TRUE ) : '';
				$excel_data['sx_z_tiangan_c_3'] = (isset($rs->num3)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_z_1), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), TRUE ) : '';
				// 次相冲天干
				$excel_data['c_tiangan'] = (isset($rs->xc_ganzhi_c_1) && isset($rs->xc_ganzhi_c_2)) ? 
													 $rs->xc_ganzhi_c_1.' ('.$rs->xc_ganzhi_c_2.')' : '';
				// 生肖 - 次相冲天干 生克刑轨迹
				$excel_data['sx_c_tiangan_c_1'] = (isset($rs->num1)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_c_1), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), TRUE ) : '';
				$excel_data['sx_c_tiangan_c_2'] = (isset($rs->num2)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_c_1), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)), TRUE ) : '';
				$excel_data['sx_c_tiangan_c_3'] = (isset($rs->num3)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_c_1), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), TRUE ) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1.' '.get_shengxiaoma($rs->sx_year,$rs->num1) : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2.' '.get_shengxiaoma($rs->sx_year,$rs->num2) : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3.' '.get_shengxiaoma($rs->sx_year,$rs->num3) : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'sx_dizhi') // 生肖 - 地支
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
				// 开干纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 地支
				$excel_data['dizhi'] = (isset($rs->tiangan) && isset($rs->dizhi)) ? 
												   '('.$rs->tiangan.') '.$rs->dizhi : '';
				// 生肖 - 地支 生克刑轨迹
				$excel_data['sx_dizhi_c_1'] = (isset($rs->num1)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->dizhi), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), TRUE ) : '';
				$excel_data['sx_dizhi_c_2'] = (isset($rs->num2)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->dizhi), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)), TRUE ) : '';
				$excel_data['sx_dizhi_c_3'] = (isset($rs->num3)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->dizhi), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), TRUE ) : '';
				// 正相冲地支
				$excel_data['z_dizhi'] = (isset($rs->xc_ganzhi_z_1) && isset($rs->xc_ganzhi_z_2)) ? 
												   '('.$rs->xc_ganzhi_z_1.') '.$rs->xc_ganzhi_z_2 : '';
				// 生肖 - 正相冲地支 生克刑轨迹
				$excel_data['sx_z_dizhi_c_1'] = (isset($rs->num1)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_z_2), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), TRUE ) : '';
				$excel_data['sx_z_dizhi_c_2'] = (isset($rs->num2)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_z_2), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)), TRUE ) : '';
				$excel_data['sx_z_dizhi_c_3'] = (isset($rs->num3)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_z_2), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), TRUE ) : '';
				// 次相冲地支
				$excel_data['c_dizhi'] = (isset($rs->xc_ganzhi_c_1) && isset($rs->xc_ganzhi_c_2)) ? 
												  '('. $rs->xc_ganzhi_c_1.') '.$rs->xc_ganzhi_c_2 : '';
				// 生肖 - 次相冲地支 生克刑轨迹
				$excel_data['sx_c_dizhi_c_1'] = (isset($rs->num1)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_c_2), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), TRUE ) : '';
				$excel_data['sx_c_dizhi_c_2'] = (isset($rs->num2)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_c_2), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)), TRUE ) : '';
				$excel_data['sx_c_dizhi_c_3'] = (isset($rs->num3)) ? get_sx_counteract( get_sx_ganzhi_wuxing($rs->xc_ganzhi_c_2), get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), TRUE ) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1.' '.get_shengxiaoma($rs->sx_year,$rs->num1) : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2.' '.get_shengxiaoma($rs->sx_year,$rs->num2) : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3.' '.get_shengxiaoma($rs->sx_year,$rs->num3) : '';
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'sx_nayin') // 生肖 - 纳音
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
				// 生肖 - 纳音 生克刑轨迹
				$excel_data['sx_nayin_c_1'] = (isset($rs->num1)) ? get_sx_counteract( $rs->kgny, get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), TRUE ) : '';
				$excel_data['sx_nayin_c_2'] = (isset($rs->num2)) ? get_sx_counteract( $rs->kgny, get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)), TRUE ) : '';
				$excel_data['sx_nayin_c_3'] = (isset($rs->num3)) ? get_sx_counteract( $rs->kgny, get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), TRUE ) : '';
				// 正相冲纳音
				$excel_data['z_nayin'] = $rs->xc_nayin_z;
				// 生肖 - 正相冲纳音 生克刑轨迹
				$excel_data['sx_z_nayin_c_1'] = (isset($rs->num1)) ? get_sx_counteract( $rs->xc_nayin_z, get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), TRUE ) : '';
				$excel_data['sx_z_nayin_c_2'] = (isset($rs->num2)) ? get_sx_counteract( $rs->xc_nayin_z, get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)), TRUE ) : '';
				$excel_data['sx_z_nayin_c_3'] = (isset($rs->num3)) ? get_sx_counteract( $rs->xc_nayin_z, get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), TRUE ) : '';
				// 次相冲纳音
				$excel_data['c_nayin'] = $rs->xc_nayin_c;
				// 生肖 - 次相冲纳音 生克刑轨迹
				$excel_data['sx_c_nayin_c_1'] = (isset($rs->num1)) ? get_sx_counteract( $rs->xc_nayin_c, get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num1)), TRUE ) : '';
				$excel_data['sx_c_nayin_c_2'] = (isset($rs->num2)) ? get_sx_counteract( $rs->xc_nayin_c, get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num2)), TRUE ) : '';
				$excel_data['sx_c_nayin_c_3'] = (isset($rs->num3)) ? get_sx_counteract( $rs->xc_nayin_c, get_sx_ganzhi_wuxing(get_shengxiaoma($rs->sx_year,$rs->num3)), TRUE ) : '';
				// 中奖号码
				$excel_data['num1'] = (isset($rs->num1)) ? $rs->num1.' '.get_shengxiaoma($rs->sx_year,$rs->num1) : '';
				$excel_data['num2'] = (isset($rs->num2)) ? $rs->num2.' '.get_shengxiaoma($rs->sx_year,$rs->num2) : '';
				$excel_data['num3'] = (isset($rs->num3)) ? $rs->num3.' '.get_shengxiaoma($rs->sx_year,$rs->num3) : '';
				$excel_datas[] = $excel_data;
			}
		}
		// 等距离加法系统、等距离乘法系统、等距离减法系统、等距离绝对值系统
		elseif($status == 'plus_lunar' OR $status == 'plus_jgx' OR $status == 'plus_week' OR $status == 'plus_solar' OR $status=='plus_xc_suishu' OR $status=='plus_pid' OR $status=='plus_lottery_qh' OR $status=='plus_pi' OR 
			   $status=='multiply_lunar' OR $status=='multiply_jgx' OR $status=='multiply_week' OR $status=='multiply_solar' OR $status=='multiply_xc_suishu' OR $status=='multiply_pid' OR $status=='multiply_lottery_qh' OR $status=='multiply_pi' OR 
				$status=='minus_lunar' OR $status=='minus_jgx' OR $status=='minus_week' OR $status=='minus_solar' OR $status=='minus_xc_suishu' OR $status=='minus_pid' OR $status=='minus_lottery_qh' OR $status=='minus_pi' OR
				$status=='abs_lunar' OR $status=='abs_jgx' OR $status=='abs_week' OR $status=='abs_solar' OR $status=='abs_xc_suishu' OR $status=='abs_pid' OR $status=='abs_lottery_qh' OR $status=='abs_pi')
		{
			$excel_define = TRUE;// 想调用特定的excel导出函数的判断条件
			$status_prefix = substr($status, 0,strpos($status, '_'));
			if($status_prefix == 'plus') $sign = '+';
			elseif($status_prefix == 'minus' OR $status_prefix == 'abs') $sign = '减';
			elseif($status_prefix == 'multiply') $sign = '乘';
			$status_suffix = substr($status, strpos($status, '_')+1);

			// 获取圆周率 (特殊情况)
			$pi		 = file_get_contents(base_url()."resources/PI.txt");
			$order   = array("\r\n", "\n", "\r");// 过滤换行符
			$replace = '';
			$pi = str_replace($order, $replace, $pi);

			foreach($result->result() as $k=>$rs)
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
				// column 变量: 要和数据库column 名对应才可以
				if($status_suffix == 'pi') { // 圆周率是特殊情况
					$excel_data[$status_suffix] = $pi[$k];
				} else {
					$excel_data[$status_suffix] = $rs->$status_suffix;
				}
				// 符号
				$excel_data['sign'] = $sign;

				$excel_data['counteract_1'] = (isset($rs->num1)) ? get_lunar_counteract($rs->lunar, get_weishu_pm($rs->lunar, $rs->num1,$status), TRUE):'';
				$excel_data['counteract_2'] = (isset($rs->num1)) ? get_lunar_counteract($rs->lunar, get_weishu_pm($rs->lunar, $rs->num2,$status), TRUE):'';
				$excel_data['counteract_3'] = (isset($rs->num1)) ? get_lunar_counteract($rs->lunar, get_weishu_pm($rs->lunar, $rs->num3,$status), TRUE):'';

				// 农历 生克刑轨迹
				$excel_data['result_1'] = (isset($rs->num1)) ? $rs->num1.' ['.get_weishu_pm($excel_data[$status_suffix], $rs->num1,$status).']' : '';
				$excel_data['result_2'] = (isset($rs->num2)) ? $rs->num2.' ['.get_weishu_pm($excel_data[$status_suffix], $rs->num2,$status).']' : '';
				$excel_data['result_3'] = (isset($rs->num3)) ? $rs->num3.' ['.get_weishu_pm($excel_data[$status_suffix], $rs->num3,$status).']' : '';
				
				$excel_datas[] = $excel_data;
			}
			$this->excel->plus_multiplication_writer($status_suffix,$excel_datas,$excel_data_count);
		}
		elseif($status == 'zhuti_fuma') // 主题副码系统
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
				// 开奖日干支
				$excel_data['ganzhi'] = $rs->tiangan.'('.get_ganzhi_wuxing($rs->tiangan).') '.$rs->dizhi.'('.get_ganzhi_wuxing($rs->dizhi).')';;
				// 中奖号码
				$excel_data['lottery_number'] = $rs->lottery_number;
				// 主题副码
				$excel_data['zhuti_fuma'] = $rs->zhuti_fuma;
				// 九宫星
				$excel_data['jgx'] = $rs->jgx.'('.get_jgx_wuxing($rs->jgx).')';
				// 西式星期
				$x_week = (isset($rs->x_week)) ? $rs->x_week : $this->x_week[$rs->week];
				$excel_data['x_week'] = $x_week.'('.get_x_week_wuxing($x_week).')';
				// 中式星期
				$excel_data['week'] = $rs->week.'('.get_week_wuxing($rs->week).')';
				
				// 判断主题 和 （天干、地支、纳音、九宫星） 是否存在同样的五行属性
				$excel_data['t'] = get_zf_check($rs->lottery_number, get_ganzhi_wuxing($rs->tiangan));
				$excel_data['d'] = get_zf_check($rs->lottery_number, get_ganzhi_wuxing($rs->dizhi));
				$excel_data['n'] = get_zf_check($rs->lottery_number, $rs->kgny);
				$excel_data['j'] = get_zf_check($rs->lottery_number, get_jgx_wuxing($rs->jgx));	
				$excel_data['x'] = get_zf_check($rs->lottery_number, get_x_week_wuxing($x_week));
				$excel_data['z'] = get_zf_check($rs->lottery_number, get_week_wuxing($rs->week));
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'zhuti_fuma_2') // 主题副码系统2
		{
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 总期号
				$excel_data['pid'] = (isset($rs->pid)) ? $rs->pid : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 农历
				$excel_data['lunar'] = $rs->lunar;
				// 新历
				$excel_data['solar'] = $rs->solar;
				// 岁数
				$excel_data['suishu'] = $rs->xc_suishu;
				// 中奖号码
				$excel_data['lottery_number'] = $rs->lottery_number;
				// 主题副码
				$excel_data['zhuti_fuma'] = $rs->zhuti_fuma;
				
				// 判断主题 和 （总期号、年度期号、农历、新历、相岁） 是否存在同样的五行属性
				$excel_data['mark_pid'] = get_zf_check2($rs->lottery_number, substr($rs->pid,-1,1));
				$excel_data['mark_qh'] = get_zf_check2($rs->lottery_number, substr($rs->lottery_qh,-1,1));
				$excel_data['mark_lunar'] = get_zf_check2($rs->lottery_number, array_search(cut_str($rs->lunar, -1, 1), $this->lunars));
				$excel_data['mark_solar'] = get_zf_check2($rs->lottery_number, substr($rs->solar,-1,1));
				$excel_data['mark_suishu'] = get_zf_check2($rs->lottery_number, substr($rs->xc_suishu,-1,1));
				$excel_datas[] = $excel_data;
			}
		}

		if( ! $excel_define) $this->excel->counteract_writer($status,$excel_datas,$excel_data_count);
		
     }

}

/* End of file counteract.php */
/* Location: ./application/controllers/admin/counteract.php */