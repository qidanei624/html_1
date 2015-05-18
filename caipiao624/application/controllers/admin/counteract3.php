<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * STBLOG Posts Controller Class
 *
 * 相生相克表
 *
 */
class Counteract3 extends ST_Auth_Controller
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
	public function manage($status = 'xsx_tiangan')
	{
		/** 默认标题 */
		$this->_data['page_title'] = '管理文章';
		/** 已发布，待审核 状态表示变量 **/
		$this->_data['status'] = $status;
		/** 分页的query string */
		$query = array();


		/** check status */
		if(!in_array($status, array('xsx_tiangan','xsx_dizhi','xsx_nayin','xsx_jgx','xsx_x_week',
			'xsx_lunar','xsx_solar','xsx_xc_suishu','xsx_pid')))
		{
			redirect('admin/counteract3/manage');
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
			redirect('admin/counteract3/manage');
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
				$values[$key]->lottery_number	= $value->lottery_number;
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
					$values[$key]->xu_shuxing	=	isset($rs[$key-1]->lottery_number) ? get_xuShuXing($rs[$key-1]->lottery_number) : '';
				}
				if($key == 0) { // 第1行空
					$values[$key]->xu_shuxing = array();
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
				$values[$key]->lottery_number	= $value->lottery_number;
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

				$values[$key]->xu_shuxing	=	isset($rs[$key+1]->lottery_number) ? get_xuShuXing($rs[$key+1]->lottery_number) : array();
				
				
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
				if ($status == 'xsx_tiangan') $page_status = 'xsx_tiangan';
				elseif($status == 'xsx_dizhi') $page_status = 'xsx_dizhi';
				elseif($status == 'xsx_nayin') $page_status = 'xsx_nayin';
				elseif($status == 'xsx_jgx') $page_status = 'xsx_jgx';
				elseif($status == 'xsx_x_week') $page_status = 'xsx_x_week';
				elseif($status == 'xsx_lunar') $page_status = 'xsx_lunar';
				elseif($status == 'xsx_solar') $page_status = 'xsx_solar';
				elseif($status == 'xsx_xc_suishu') $page_status = 'xsx_xc_suishu';
				elseif($status == 'xsx_pid') $page_status = 'xsx_pid';
				

				$this->dpagination->currentPage($page);
				$this->dpagination->items($result_count);
				$this->dpagination->limit($limit);
				$this->dpagination->adjacents(5);
				$this->dpagination->target(site_url("admin/counteract3/manage/$page_status?".implode('&',$query)));
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

		if($status == 'xsx_tiangan') {
			$this->_data['page_title'] = '虚数型 - 天干系统';
			$this->load->view('admin/3d_xsx_tiangan', $this->_data);
		}elseif($status == 'xsx_dizhi') {
			$this->_data['page_title'] = '虚数型 - 地支系统';
			$this->load->view('admin/3d_xsx_dizhi', $this->_data);
		}elseif($status == 'xsx_nayin') {
			$this->_data['page_title'] = '虚数型 - 纳音系统';
			$this->load->view('admin/3d_xsx_nayin', $this->_data);
		}elseif($status == 'xsx_jgx') {
			$this->_data['page_title'] = '虚数型 - 九宫星系统';
			$this->load->view('admin/3d_xsx_jgx', $this->_data);
		}elseif($status == 'xsx_x_week') {
			$this->_data['page_title'] = '虚数型 - 西式星期系统';
			$this->_data['x_week'] = $this->x_week; // 传递西式星期数组
			$this->load->view('admin/3d_xsx_x_week', $this->_data);
		}elseif($status == 'xsx_lunar') {
			$this->_data['page_title'] = '虚数型 - 农历系统';
			$this->load->view('admin/3d_xsx_lunar', $this->_data);
		}elseif($status == 'xsx_solar') {
			$this->_data['page_title'] = '虚数型 - 新历系统';
			$this->load->view('admin/3d_xsx_solar', $this->_data);
		}elseif($status == 'xsx_xc_suishu') {
			$this->_data['page_title'] = '虚数型 - 相岁';
			$this->load->view('admin/3d_xsx_xc_suishu', $this->_data);
		}elseif($status == 'xsx_pid') {
			$this->_data['page_title'] = '虚数型 - 总期号';
			$this->load->view('admin/3d_xsx_pid', $this->_data);
		}
		
	}

	/**
     * 
     * EXCEL 导出
     * 
     */
     public function excel_write($status='xsx_tiangan', $result='', $excel_data_count=0)
     {
		if(empty($result) || empty($excel_data_count)) return;
		
		ini_set('memory_limit', '200M'); // 默认内存128

        $this->load->library('Classes/PHPExcel');
		$this->load->library('excel_2');
		$excel_datas = array();
		$excel_define = FALSE;

		if($status == 'xsx_tiangan') { // 虚数型 - 天干
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 中奖号码
				$excel_data['lottery_number'] = (isset($rs->lottery_number)) ? $rs->lottery_number : '';

				$shuxing = get_shuxing($rs->lottery_number);
				$xu_shuxing = (isset($rs->xu_shuxing)) ? $rs->xu_shuxing : get_xuShuXing($rs->lottery_number);
				// 数型
				$excel_data['sx'] = $shuxing['sx'];
				// 配伍
				$excel_data['sum'] = $shuxing['sum'];
				// 天干
				$excel_data['common'] = (isset($rs->tiangan)) ? $rs->tiangan.' ('.get_sx_ganzhi_wuxing($rs->tiangan).')' : '';
				// 虚数型
				$excel_data['xsx_0'] = (in_array(0, $xu_shuxing)) ? 'ⓧ0' : '';
				$excel_data['xsx_1'] = (in_array(1, $xu_shuxing)) ? 'ⓧ1' : '';
				$excel_data['xsx_2'] = (in_array(2, $xu_shuxing)) ? 'ⓧ2' : '';
				$excel_data['xsx_3'] = (in_array(3, $xu_shuxing)) ? 'ⓧ3' : '';
				$excel_data['xsx_4'] = (in_array(4, $xu_shuxing)) ? 'ⓧ4' : '';
				// 天干 生克刑轨迹
				$excel_data['counteract_0'] = (isset($rs->tiangan)) ? get_ganzhi_counteract($rs->tiangan, (in_array(0, $xu_shuxing)) ? 0 : '', TRUE) : '';
				$excel_data['counteract_1'] = (isset($rs->tiangan)) ? get_ganzhi_counteract($rs->tiangan, (in_array(1, $xu_shuxing)) ? 1 : '', TRUE) : '';
				$excel_data['counteract_2'] = (isset($rs->tiangan)) ? get_ganzhi_counteract($rs->tiangan, (in_array(2, $xu_shuxing)) ? 2 : '', TRUE) : '';
				$excel_data['counteract_3'] = (isset($rs->tiangan)) ? get_ganzhi_counteract($rs->tiangan, (in_array(3, $xu_shuxing)) ? 3 : '', TRUE) : '';
				$excel_data['counteract_4'] = (isset($rs->tiangan)) ? get_ganzhi_counteract($rs->tiangan, (in_array(4, $xu_shuxing)) ? 4 : '', TRUE) : '';
				
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'xsx_dizhi') { // 虚数型 - 地支
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 中奖号码
				$excel_data['lottery_number'] = (isset($rs->lottery_number)) ? $rs->lottery_number : '';

				$shuxing = get_shuxing($rs->lottery_number);
				$xu_shuxing = (isset($rs->xu_shuxing)) ? $rs->xu_shuxing : get_xuShuXing($rs->lottery_number);
				// 数型
				$excel_data['sx'] = $shuxing['sx'];
				// 配伍
				$excel_data['sum'] = $shuxing['sum'];
				// 地支
				$excel_data['common'] = (isset($rs->dizhi)) ? $rs->dizhi.' ('.get_sx_ganzhi_wuxing($rs->dizhi).')' : '';
				// 虚数型
				$excel_data['xsx_0'] = (in_array(0, $xu_shuxing)) ? 'ⓧ0' : '';
				$excel_data['xsx_1'] = (in_array(1, $xu_shuxing)) ? 'ⓧ1' : '';
				$excel_data['xsx_2'] = (in_array(2, $xu_shuxing)) ? 'ⓧ2' : '';
				$excel_data['xsx_3'] = (in_array(3, $xu_shuxing)) ? 'ⓧ3' : '';
				$excel_data['xsx_4'] = (in_array(4, $xu_shuxing)) ? 'ⓧ4' : '';
				// 天干 生克刑轨迹
				$excel_data['counteract_0'] = (isset($rs->dizhi)) ? get_ganzhi_counteract($rs->dizhi, (in_array(0, $xu_shuxing)) ? 0 : '', TRUE) : '';
				$excel_data['counteract_1'] = (isset($rs->dizhi)) ? get_ganzhi_counteract($rs->dizhi, (in_array(1, $xu_shuxing)) ? 1 : '', TRUE) : '';
				$excel_data['counteract_2'] = (isset($rs->dizhi)) ? get_ganzhi_counteract($rs->dizhi, (in_array(2, $xu_shuxing)) ? 2 : '', TRUE) : '';
				$excel_data['counteract_3'] = (isset($rs->dizhi)) ? get_ganzhi_counteract($rs->dizhi, (in_array(3, $xu_shuxing)) ? 3 : '', TRUE) : '';
				$excel_data['counteract_4'] = (isset($rs->dizhi)) ? get_ganzhi_counteract($rs->dizhi, (in_array(4, $xu_shuxing)) ? 4 : '', TRUE) : '';
				
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'xsx_nayin') { // 虚数型 - 纳音
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 中奖号码
				$excel_data['lottery_number'] = (isset($rs->lottery_number)) ? $rs->lottery_number : '';

				$shuxing = get_shuxing($rs->lottery_number);
				$xu_shuxing = (isset($rs->xu_shuxing)) ? $rs->xu_shuxing : get_xuShuXing($rs->lottery_number);
				// 数型
				$excel_data['sx'] = $shuxing['sx'];
				// 配伍
				$excel_data['sum'] = $shuxing['sum'];
				// 纳音
				$excel_data['common'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 虚数型
				$excel_data['xsx_0'] = (in_array(0, $xu_shuxing)) ? 'ⓧ0' : '';
				$excel_data['xsx_1'] = (in_array(1, $xu_shuxing)) ? 'ⓧ1' : '';
				$excel_data['xsx_2'] = (in_array(2, $xu_shuxing)) ? 'ⓧ2' : '';
				$excel_data['xsx_3'] = (in_array(3, $xu_shuxing)) ? 'ⓧ3' : '';
				$excel_data['xsx_4'] = (in_array(4, $xu_shuxing)) ? 'ⓧ4' : '';
				// 天干 生克刑轨迹
				$excel_data['counteract_0'] = (isset($rs->kgny)) ? get_nayin_counteract($rs->kgny, (in_array(0, $xu_shuxing)) ? 0 : '', TRUE) : '';
				$excel_data['counteract_1'] = (isset($rs->kgny)) ? get_nayin_counteract($rs->kgny, (in_array(1, $xu_shuxing)) ? 1 : '', TRUE) : '';
				$excel_data['counteract_2'] = (isset($rs->kgny)) ? get_nayin_counteract($rs->kgny, (in_array(2, $xu_shuxing)) ? 2 : '', TRUE) : '';
				$excel_data['counteract_3'] = (isset($rs->kgny)) ? get_nayin_counteract($rs->kgny, (in_array(3, $xu_shuxing)) ? 3 : '', TRUE) : '';
				$excel_data['counteract_4'] = (isset($rs->kgny)) ? get_nayin_counteract($rs->kgny, (in_array(4, $xu_shuxing)) ? 4 : '', TRUE) : '';
				
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'xsx_jgx') { // 虚数型 - 九宫星
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 中奖号码
				$excel_data['lottery_number'] = (isset($rs->lottery_number)) ? $rs->lottery_number : '';

				$shuxing = get_shuxing($rs->lottery_number);
				$xu_shuxing = (isset($rs->xu_shuxing)) ? $rs->xu_shuxing : get_xuShuXing($rs->lottery_number);
				// 数型
				$excel_data['sx'] = $shuxing['sx'];
				// 配伍
				$excel_data['sum'] = $shuxing['sum'];
				// 九宫星
				$excel_data['common'] = $rs->jgx.' ('.get_jgx_wuxing($rs->jgx).')';
				// 虚数型
				$excel_data['xsx_0'] = (in_array(0, $xu_shuxing)) ? 'ⓧ0' : '';
				$excel_data['xsx_1'] = (in_array(1, $xu_shuxing)) ? 'ⓧ1' : '';
				$excel_data['xsx_2'] = (in_array(2, $xu_shuxing)) ? 'ⓧ2' : '';
				$excel_data['xsx_3'] = (in_array(3, $xu_shuxing)) ? 'ⓧ3' : '';
				$excel_data['xsx_4'] = (in_array(4, $xu_shuxing)) ? 'ⓧ4' : '';
				// 天干 生克刑轨迹
				$excel_data['counteract_0'] = (isset($rs->jgx)) ? get_jgx_counteract($rs->jgx, (in_array(0, $xu_shuxing)) ? 0 : '', TRUE) : '';
				$excel_data['counteract_1'] = (isset($rs->jgx)) ? get_jgx_counteract($rs->jgx, (in_array(1, $xu_shuxing)) ? 1 : '', TRUE) : '';
				$excel_data['counteract_2'] = (isset($rs->jgx)) ? get_jgx_counteract($rs->jgx, (in_array(2, $xu_shuxing)) ? 2 : '', TRUE) : '';
				$excel_data['counteract_3'] = (isset($rs->jgx)) ? get_jgx_counteract($rs->jgx, (in_array(3, $xu_shuxing)) ? 3 : '', TRUE) : '';
				$excel_data['counteract_4'] = (isset($rs->jgx)) ? get_jgx_counteract($rs->jgx, (in_array(4, $xu_shuxing)) ? 4 : '', TRUE) : '';
				
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'xsx_x_week') { // 虚数型 - 西式星期
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 中奖号码
				$excel_data['lottery_number'] = (isset($rs->lottery_number)) ? $rs->lottery_number : '';

				$shuxing = get_shuxing($rs->lottery_number);
				$xu_shuxing = (isset($rs->xu_shuxing)) ? $rs->xu_shuxing : get_xuShuXing($rs->lottery_number);
				// 数型
				$excel_data['sx'] = $shuxing['sx'];
				// 配伍
				$excel_data['sum'] = $shuxing['sum'];
				// 西式星期
				$week = (isset($rs->x_week)) ? $rs->x_week : $x_week[$rs->week];
				$excel_data['common'] = $week;
				// 虚数型
				$excel_data['xsx_0'] = (in_array(0, $xu_shuxing)) ? 'ⓧ0' : '';
				$excel_data['xsx_1'] = (in_array(1, $xu_shuxing)) ? 'ⓧ1' : '';
				$excel_data['xsx_2'] = (in_array(2, $xu_shuxing)) ? 'ⓧ2' : '';
				$excel_data['xsx_3'] = (in_array(3, $xu_shuxing)) ? 'ⓧ3' : '';
				$excel_data['xsx_4'] = (in_array(4, $xu_shuxing)) ? 'ⓧ4' : '';
				// 天干 生克刑轨迹
				$excel_data['counteract_0'] = (isset($week)) ? get_x_week_counteract($week, (in_array(0, $xu_shuxing)) ? 0 : '', TRUE) : '';
				$excel_data['counteract_1'] = (isset($week)) ? get_x_week_counteract($week, (in_array(1, $xu_shuxing)) ? 1 : '', TRUE) : '';
				$excel_data['counteract_2'] = (isset($week)) ? get_x_week_counteract($week, (in_array(2, $xu_shuxing)) ? 2 : '', TRUE) : '';
				$excel_data['counteract_3'] = (isset($week)) ? get_x_week_counteract($week, (in_array(3, $xu_shuxing)) ? 3 : '', TRUE) : '';
				$excel_data['counteract_4'] = (isset($week)) ? get_x_week_counteract($week, (in_array(4, $xu_shuxing)) ? 4 : '', TRUE) : '';
				
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'xsx_lunar') { // 虚数型 - 农历
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 中奖号码
				$excel_data['lottery_number'] = (isset($rs->lottery_number)) ? $rs->lottery_number : '';

				$shuxing = get_shuxing($rs->lottery_number);
				$xu_shuxing = (isset($rs->xu_shuxing)) ? $rs->xu_shuxing : get_xuShuXing($rs->lottery_number);
				// 数型
				$excel_data['sx'] = $shuxing['sx'];
				// 配伍
				$excel_data['sum'] = $shuxing['sum'];
				// 农历
				$excel_data['common'] = $rs->lunar;
				// 虚数型
				$excel_data['xsx_0'] = (in_array(0, $xu_shuxing)) ? 'ⓧ0' : '';
				$excel_data['xsx_1'] = (in_array(1, $xu_shuxing)) ? 'ⓧ1' : '';
				$excel_data['xsx_2'] = (in_array(2, $xu_shuxing)) ? 'ⓧ2' : '';
				$excel_data['xsx_3'] = (in_array(3, $xu_shuxing)) ? 'ⓧ3' : '';
				$excel_data['xsx_4'] = (in_array(4, $xu_shuxing)) ? 'ⓧ4' : '';
				// 天干 生克刑轨迹
				$excel_data['counteract_0'] = (isset($rs->lunar)) ? get_lunar_counteract($rs->lunar, (in_array(0, $xu_shuxing)) ? 0 : '', TRUE) : '';
				$excel_data['counteract_1'] = (isset($rs->lunar)) ? get_lunar_counteract($rs->lunar, (in_array(1, $xu_shuxing)) ? 1 : '', TRUE) : '';
				$excel_data['counteract_2'] = (isset($rs->lunar)) ? get_lunar_counteract($rs->lunar, (in_array(2, $xu_shuxing)) ? 2 : '', TRUE) : '';
				$excel_data['counteract_3'] = (isset($rs->lunar)) ? get_lunar_counteract($rs->lunar, (in_array(3, $xu_shuxing)) ? 3 : '', TRUE) : '';
				$excel_data['counteract_4'] = (isset($rs->lunar)) ? get_lunar_counteract($rs->lunar, (in_array(4, $xu_shuxing)) ? 4 : '', TRUE) : '';
				
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'xsx_solar') { // 虚数型 - 新历
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 中奖号码
				$excel_data['lottery_number'] = (isset($rs->lottery_number)) ? $rs->lottery_number : '';

				$shuxing = get_shuxing($rs->lottery_number);
				$xu_shuxing = (isset($rs->xu_shuxing)) ? $rs->xu_shuxing : get_xuShuXing($rs->lottery_number);
				// 数型
				$excel_data['sx'] = $shuxing['sx'];
				// 配伍
				$excel_data['sum'] = $shuxing['sum'];
				// 新历
				$excel_data['common'] = $rs->solar;
				// 虚数型
				$excel_data['xsx_0'] = (in_array(0, $xu_shuxing)) ? 'ⓧ0' : '';
				$excel_data['xsx_1'] = (in_array(1, $xu_shuxing)) ? 'ⓧ1' : '';
				$excel_data['xsx_2'] = (in_array(2, $xu_shuxing)) ? 'ⓧ2' : '';
				$excel_data['xsx_3'] = (in_array(3, $xu_shuxing)) ? 'ⓧ3' : '';
				$excel_data['xsx_4'] = (in_array(4, $xu_shuxing)) ? 'ⓧ4' : '';
				// 天干 生克刑轨迹
				$excel_data['counteract_0'] = (isset($rs->solar)) ? get_prefix_nums($rs->solar, (in_array(0, $xu_shuxing)) ? 0 : '', TRUE) : '';
				$excel_data['counteract_1'] = (isset($rs->solar)) ? get_prefix_nums($rs->solar, (in_array(1, $xu_shuxing)) ? 1 : '', TRUE) : '';
				$excel_data['counteract_2'] = (isset($rs->solar)) ? get_prefix_nums($rs->solar, (in_array(2, $xu_shuxing)) ? 2 : '', TRUE) : '';
				$excel_data['counteract_3'] = (isset($rs->solar)) ? get_prefix_nums($rs->solar, (in_array(3, $xu_shuxing)) ? 3 : '', TRUE) : '';
				$excel_data['counteract_4'] = (isset($rs->solar)) ? get_prefix_nums($rs->solar, (in_array(4, $xu_shuxing)) ? 4 : '', TRUE) : '';
				
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'xsx_xc_suishu') { // 虚数型 - 相岁
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 中奖号码
				$excel_data['lottery_number'] = (isset($rs->lottery_number)) ? $rs->lottery_number : '';

				$shuxing = get_shuxing($rs->lottery_number);
				$xu_shuxing = (isset($rs->xu_shuxing)) ? $rs->xu_shuxing : get_xuShuXing($rs->lottery_number);
				// 数型
				$excel_data['sx'] = $shuxing['sx'];
				// 配伍
				$excel_data['sum'] = $shuxing['sum'];
				// 相岁
				$excel_data['common'] = $rs->xc_suishu;
				// 虚数型
				$excel_data['xsx_0'] = (in_array(0, $xu_shuxing)) ? 'ⓧ0' : '';
				$excel_data['xsx_1'] = (in_array(1, $xu_shuxing)) ? 'ⓧ1' : '';
				$excel_data['xsx_2'] = (in_array(2, $xu_shuxing)) ? 'ⓧ2' : '';
				$excel_data['xsx_3'] = (in_array(3, $xu_shuxing)) ? 'ⓧ3' : '';
				$excel_data['xsx_4'] = (in_array(4, $xu_shuxing)) ? 'ⓧ4' : '';
				// 天干 生克刑轨迹
				$excel_data['counteract_0'] = (isset($rs->xc_suishu)) ? get_prefix_nums($rs->xc_suishu, (in_array(0, $xu_shuxing)) ? 0 : '', TRUE) : '';
				$excel_data['counteract_1'] = (isset($rs->xc_suishu)) ? get_prefix_nums($rs->xc_suishu, (in_array(1, $xu_shuxing)) ? 1 : '', TRUE) : '';
				$excel_data['counteract_2'] = (isset($rs->xc_suishu)) ? get_prefix_nums($rs->xc_suishu, (in_array(2, $xu_shuxing)) ? 2 : '', TRUE) : '';
				$excel_data['counteract_3'] = (isset($rs->xc_suishu)) ? get_prefix_nums($rs->xc_suishu, (in_array(3, $xu_shuxing)) ? 3 : '', TRUE) : '';
				$excel_data['counteract_4'] = (isset($rs->xc_suishu)) ? get_prefix_nums($rs->xc_suishu, (in_array(4, $xu_shuxing)) ? 4 : '', TRUE) : '';
				
				$excel_datas[] = $excel_data;
			}
		}
		elseif($status == 'xsx_pid') { // 虚数型 - 总期号
			foreach($result->result() as $rs)
			{
				if(!isset($rs->pid)) continue;	// 右斜时候，防止Undefined错误
				// 开奖日期
				$excel_data['created'] = (isset($rs->created)) ? $rs->created : '';
				// 年度期号
				$excel_data['lottery_qh'] = (isset($rs->lottery_qh)) ? $rs->lottery_qh : '';
				// 纳音
				$excel_data['kgny'] = (isset($rs->kgny)) ? $rs->kgny : '';
				// 中奖号码
				$excel_data['lottery_number'] = (isset($rs->lottery_number)) ? $rs->lottery_number : '';

				$shuxing = get_shuxing($rs->lottery_number);
				$xu_shuxing = (isset($rs->xu_shuxing)) ? $rs->xu_shuxing : get_xuShuXing($rs->lottery_number);
				// 数型
				$excel_data['sx'] = $shuxing['sx'];
				// 配伍
				$excel_data['sum'] = $shuxing['sum'];
				// 总期号
				$excel_data['common'] = $rs->pid;
				// 虚数型
				$excel_data['xsx_0'] = (in_array(0, $xu_shuxing)) ? 'ⓧ0' : '';
				$excel_data['xsx_1'] = (in_array(1, $xu_shuxing)) ? 'ⓧ1' : '';
				$excel_data['xsx_2'] = (in_array(2, $xu_shuxing)) ? 'ⓧ2' : '';
				$excel_data['xsx_3'] = (in_array(3, $xu_shuxing)) ? 'ⓧ3' : '';
				$excel_data['xsx_4'] = (in_array(4, $xu_shuxing)) ? 'ⓧ4' : '';
				// 天干 生克刑轨迹
				$excel_data['counteract_0'] = (isset($rs->pid)) ? get_prefix_nums($rs->pid, (in_array(0, $xu_shuxing)) ? 0 : '', TRUE) : '';
				$excel_data['counteract_1'] = (isset($rs->pid)) ? get_prefix_nums($rs->pid, (in_array(1, $xu_shuxing)) ? 1 : '', TRUE) : '';
				$excel_data['counteract_2'] = (isset($rs->pid)) ? get_prefix_nums($rs->pid, (in_array(2, $xu_shuxing)) ? 2 : '', TRUE) : '';
				$excel_data['counteract_3'] = (isset($rs->pid)) ? get_prefix_nums($rs->pid, (in_array(3, $xu_shuxing)) ? 3 : '', TRUE) : '';
				$excel_data['counteract_4'] = (isset($rs->pid)) ? get_prefix_nums($rs->pid, (in_array(4, $xu_shuxing)) ? 4 : '', TRUE) : '';
				
				$excel_datas[] = $excel_data;
			}
		}
		

		if( ! $excel_define) $this->excel_2->counteract_writer($status,$excel_datas,$excel_data_count);
		
     }

}

/* End of file counteract.php */
/* Location: ./application/controllers/admin/counteract.php */