<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * STBLOG Posts Controller Class
 *
 * 日志撰写和管理
 *
 * @package		STBLOG
 * @subpackage	Controller
 * @category	Admin Controller
 * @author		Saturn <huyanggang@gmail.com>
 * @link 		http://code.google.com/p/stblog/
 */
class Threed extends ST_Auth_Controller {
	
	/**
     * 传递到对应视图的数据
     *
     * @access private
     * @var array
     */
	private $_data = array();
	
	/**
     * 开奖日干支
     *
     * @access private
     * @var array
     */
	private $ganzhi_arrays = array(
		'甲子','乙丑','丙寅','丁卯','戊辰','己巳','庚午','辛未','壬申','癸酉',
		'甲戌','乙亥','丙子','丁丑','戊寅','己卯','庚辰','辛巳','壬午','癸未',
		'甲申','乙酉','丙戌','丁亥','戊子','己丑','庚寅','辛卯','壬辰','癸巳',
		'甲午','乙未','丙申','丁酉','戊戌','己亥','庚子','辛丑','壬寅','癸卯',
		'甲辰','乙巳','丙午','丁未','戊申','己酉','庚戌','辛亥','壬子','癸丑',
		'甲寅','乙卯','丙辰','丁巳','戊午','己未','庚申','辛酉','壬戌','癸亥'
	);

	/**
     * 解析函数
     *
     * @access public
     * @var array
     */
	public function __construct()
	{
		parent::__construct();
		
		/** 权限确认 */
		//$this->auth->exceed('contributor');
		
		/** 导航栏和标题 */
		$this->_data['parentPage'] = 'post';
		$this->_data['currentPage'] = 'post';
		$this->_data['page_title'] = '总论：总系统 - 3D干支数字信息载体总系统';

	}
	
	/**
     * 获取表单数据
     *
     * @access private
     * @return array
     */
	private function _get_form_data()
	{
		return array(
			'created' 			=> 	$this->input->post('created',TRUE),
			'lottery_qh' 		=> 	$this->input->post('lottery_qh',TRUE),
			'lottery_ganzhi' 	=> 	$this->input->post('lottery_ganzhi',TRUE),
			'lunar' 			=> 	$this->input->post('lunar',TRUE),
			'solar' 			=>	$this->input->post('solar',TRUE),
			'week' 				=> 	$this->input->post('week',TRUE),
			'lottery_number' 	=> 	$this->input->post('lottery_number',TRUE),
			'kgny' 				=> 	$this->input->post('kgny',TRUE),
			'zhuti_fuma' 		=> 	$this->input->post('zhuti_fuma',TRUE),
			'xuzhuti' 			=> 	$this->input->post('xuzhuti',TRUE),
			'xc_suishu' 		=> 	$this->input->post('xc_suishu',TRUE),
			'xc_nayin' 			=> 	$this->input->post('xc_nayin',TRUE),
			'xc_ganzhi' 		=> 	$this->input->post('xc_ganzhi',TRUE),
			'jgx' 				=> 	$this->input->post('jgx',TRUE),
			'sx_year' 		=> 	$this->input->post('sx_year',TRUE)
		);
	}

	/**
     * 添加 按钮验证规则
     *
     * @access private
     * @return void
     */
	private function _add_bt_validation_rules()
	{
		$this->form_validation->set_rules('created', '开奖日期', 'required|trim|callback__created_check');
		$this->form_validation->set_rules('lottery_qh', '年度期号', 'required|trim|is_natural|min_length[7]|max_length[7]|callback__lottery_qh_check');
		$this->form_validation->set_rules('solar', '新历', 'required|trim');
		$this->form_validation->set_rules('lottery_number', '中奖号码', 'required|trim|is_natural|callback__lottery_number_len');
	}
	/**
     * 自动生成 按钮验证规则
     *
     * @access private
     * @return void
     */
	private function _auto_bt_validation_rules()
	{
		$this->form_validation->set_rules('solar', '新历', 'required|trim');
		$this->form_validation->set_rules('lottery_number', '中奖号码', 'required|trim|is_natural|callback__lottery_number_len');
	}
	/**
     * 编辑 按钮验证规则
     *
     * @access private
     * @return void
     */
	private function _edit_bt_validation_rules()
	{
		$this->form_validation->set_rules('solar', '新历', 'required|trim');
		$this->form_validation->set_rules('lottery_number', '中奖号码', 'required|trim|is_natural|callback__lottery_number_len');
	}
	/**
     * 回调函数：检查年度期号是否唯一
     * 
     * @access 	public
     * @param 	$str 输入值
     * @return 	bool
     */
	public function _lottery_qh_check($str)
	{
		if($this->threed_mdl->check_exist('lottery_qh', $str))
		{
			$this->form_validation->set_message('_lottery_qh_check', '已经存在一个为 '.$str.' 的年度期号');
			return FALSE;
		}
		return TRUE;
	}
	// 回调函数：检查开奖日期是否唯一
	public function _created_check($str)
	{
		if($this->threed_mdl->check_exist('created', $str))
		{
			$this->form_validation->set_message('_created_check', '已经存在一个为 '.$str.' 的开奖日期');
			return FALSE;
		}
		return TRUE;
	}
	// 回调函数：检查开奖号码是否唯一
	public function _lottery_number_len($str)	
	{
		if(strlen($str) != 3 && strlen($str) != 14) {
			$this->form_validation->set_message('_lottery_number_len', '只能填写3位，14位的开奖日期');
			return FALSE;
		}
		return TRUE;
	}

	/**
     * 默认执行函数
     *
     * @access public
     * @return void
     */
	public function index()
	{
		redirect('admin/threed/manage');
	}
	
	/**
     * 添加页面 分发器
     *
     * @access private
     * @return void
     */
	public function add()
	{
		$action = $this->input->post('do',TRUE);
		// 自动生成按钮判断
		if($action == 'auto') 
		{
			$this->_auto_generation();
		}
		else
		{
			if(FALSE === $this->uri->segment(4)) 
			{
				$this->_add();
			}
			else
			{
				$pid = $this->security->xss_clean($this->uri->segment(4));
				is_numeric($pid) ? $this->_edit($pid) : show_error('禁止访问：危险操作');
			}
		}	
	}
	/**
     * 添加内容
     *
     * @access private
     * @return void
     */
	private function _add()
	{
		$lunarCalendar = array();
		/** 已发布，待审核 状态表示变量 **/
		$this->_data['status'] = 'add';
		/** 干支 下拉框 **/
		$this->_data['ganzhi_arrays'] = $this->ganzhi_arrays;
		
		$this->_add_bt_validation_rules();

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('admin/add_3d', $this->_data);
		}
		else
		{
			$this->_insert();
		}
	}
	
	/**
     * 自动生成 按钮处理
     *
     * @access private
     * @return void
     */
	private function _auto_generation()
	{
		$lunarCalendar = array();
		/** 已发布，待审核 状态表示变量 **/
		$this->_data['status'] = 'add';
		/** 干支 下拉框 **/
		$this->_data['ganzhi_arrays'] = $this->ganzhi_arrays;

		$this->_auto_bt_validation_rules();
			
		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('admin/add_3d', $this->_data);
		}
		else
		{
			$lottery_number = $this->input->post('lottery_number', TRUE);
			// 分割成数组
			$lottery_number = str_split($lottery_number);
			// 对数组中的每个成员应用用户函数, 删除空格，转换int类型
			array_walk($lottery_number, array($this, '_trim_value'));

			$solar_date = $this->input->post('solar', TRUE);
			$solar_arr = explode('-', $solar_date);
			// 导入 自定义的类
			$this->load->helper('my_lunar');
			$this->load->helper('my_zhuti_fuma');
			
			// 农历，干支，五行...等
			$lunarCalendar = getLunarCalendar($solar_arr[0], $solar_arr[1], $solar_arr[2]);

			$this->_data['created']			= $this->input->post('created', TRUE);		// 开奖日期
			$this->_data['lottery_qh']		= $this->input->post('lottery_qh', TRUE);	// 年度期号
			$this->_data['lottery_ganzhi']	= $lunarCalendar['ganzhi'];					// 开奖日 干支
			$this->_data['lunar']			= $lunarCalendar['lunar'];
			$this->_data['solar']			= $this->input->post('solar', TRUE);
			$this->_data['week']			= $lunarCalendar['weekday'];
			$this->_data['lottery_number']	= $this->input->post('lottery_number', TRUE); // 中奖号码
			$this->_data['kgny']			= $lunarCalendar['sixty_wuxing'];
			$this->_data['zhuti_fuma']		= get_zhuti_fuma($lottery_number);			// 主题和副码
			$this->_data['xuzhuti']			= get_xuzhuti($lottery_number);				// 虚主题
			$this->_data['xc_suishu']		= $lunarCalendar['suishu'];
			$this->_data['xc_nayin']		= $lunarCalendar['xiangchong_nayin'];
			$this->_data['xc_ganzhi']		= $lunarCalendar['xiangchong_ganzhi'];
			$this->_data['jgx']				= $lunarCalendar['jiugongxing'];
			$this->_data['sx_year']		= $lunarCalendar['sixty_year_int'];
			
			// 添加和编辑按钮 判断变量 
			// 当显示编辑按钮时候，按下自动生成按钮，这时也要保持编辑按钮的显示状态
			$pid = $this->input->post('pid', TRUE);
			if(isset($pid) && is_numeric($pid)) 
			{
				$this->_data['pid'] = $pid;
			}

			$this->load->view('admin/add_3d', $this->_data);
		}
	}

	private function _insert()
	{
		/** 获取表单数据 */
		$content = $this->_get_form_data();
		$inserted = 0;

		$insert_struct = array(
			'created' 			=> 	$content['created'],
			'lottery_qh' 		=> 	$content['lottery_qh'],
			'lottery_ganzhi' 	=> 	$content['lottery_ganzhi'],
			'lunar' 			=> 	$content['lunar'],
			'solar' 			=>	$content['solar'],
			'week' 				=> 	$content['week'],
			'lottery_number' 	=> 	$content['lottery_number'],
			'kgny' 				=> 	$content['kgny'],
			'zhuti_fuma' 		=> 	$content['zhuti_fuma'],
			'xuzhuti' 			=>  $content['xuzhuti'],
			'xc_suishu' 		=> 	$content['xc_suishu'],
			'xc_nayin' 			=> 	$content['xc_nayin'],
			'xc_ganzhi' 		=> 	$content['xc_ganzhi'],
			'jgx' 				=> 	$content['jgx'],
			'sx_year' 		=> 	$content['sx_year']
		);

		$inserted = $this->threed_mdl->add_threed($insert_struct);

		($inserted > 0)
					?$this->session->set_flashdata('success', '成功添加 3D 内容')
					:$this->session->set_flashdata('error', '没有内容被添加');
		
		go_back();
	}

	/**
     * 编辑内容
     * 
     * @access 	private
     * @param 	$pid
     * @return 	void
     */
	private function _edit($pid = NULL)
	{
		// 我自定义的！ 有点不舒服的感觉
		$this->_data['status'] = 'add';
		/** 干支 下拉框 **/
		$this->_data['ganzhi_arrays'] = $this->ganzhi_arrays;
		// 添加信息 和 编辑信息按钮的判断变量
		$this->_data['pid'] = $pid;
		$updated = FALSE;

		$rs = $this->threed_mdl->get_threed_by_id($pid);
		if(!$rs) 
		{
			show_error('列表不存在或已经被删除');
			exit();
		}

		$this->_data['created']			= $rs['created'];
		$this->_data['lottery_qh']		= $rs['lottery_qh'];
		$this->_data['lottery_ganzhi']	= $rs['lottery_ganzhi'];
		$this->_data['lunar']			= $rs['lunar'];
		$this->_data['solar']			= $rs['solar'];
		$this->_data['week']			= $rs['week'];
		$this->_data['lottery_number']	= $rs['lottery_number'];
		$this->_data['kgny']			= $rs['kgny'];
		$this->_data['zhuti_fuma']		= $rs['zhuti_fuma'];
		$this->_data['xuzhuti']			= $rs['xuzhuti'];
		$this->_data['xc_suishu']		= $rs['xc_suishu'];
		$this->_data['xc_nayin']		= $rs['xc_nayin'];
		$this->_data['xc_ganzhi']		= $rs['xc_ganzhi'];
		$this->_data['jgx']				= $rs['jgx'];
		$this->_data['sx_year']		= $rs['sx_year'];

		$this->_edit_bt_validation_rules();

		if($this->form_validation->run() === FALSE) 
		{
			$this->load->view('admin/add_3d', $this->_data);
		}
		else
		{
			$update_struct = $this->_get_form_data();
			$updated = $this->threed_mdl->update_threed($pid, $update_struct);

			($updated)
						?$this->session->set_flashdata('success', '成功修改 3D 内容')
						:$this->session->set_flashdata('error', '没有内容被修改');
			
			go_back();
		}
		
	}	


	/**
     * 批量删除文章
     *
     * @access private
     * @return void
     */
	private function _remove()
	{
		$threeds = $this->input->post('pid',TRUE);
		$deleted = 0;
		
		if($threeds && is_array($threeds))
		{
			foreach($threeds as $threed)
			{
				if(empty($threed))
				{
					continue;
				}
			
				$this->threed_mdl->remove_threed($threed);
				$deleted++;
			}
		}
		
		($deleted > 0)
					?$this->session->set_flashdata('success', '成功删除 3D 内容')
					:$this->session->set_flashdata('error', '没有内容被删除');
		
		go_back();
	}

	/**
     * 过滤数组
     *
     * @access public
     * @return void
     */
	private function _trim_value(&$value)
	{
		$value = intval(trim($value));
	}
	
	/**
     * 管理日志
     *
     * @access public
     * @return void
     */
	public function manage($status = '3d_one')
	{
		/** 默认标题 */
		//$this->_data['page_title'] = '3D';
		// 调用处理主题和副码，数型转换方法
		$this->load->helper('my_zhuti_fuma');
		
		/** 分页的query string */
		$query = array();
		
		/** 所有年份 filter */
		$years_filter = $this->input->get('years', TRUE);
		$years_filter = (!empty($years_filter)) ? intval($years_filter) : 0;
		
		if(!empty($years_filter))
		{
			$query[] = 'years='.$years_filter;
		}
		
		/** pagination stff */
		$page = $this->input->get('p',TRUE);
		$page = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
		$limit = 10;
		$offset = ($page - 1) * $limit;
		//$cnt = 1001 + $offset; // 总期号
		
		if($offset < 0)
		{
			redirect('admin/threed/manage');
		}
		//=======================================
		$threed = $this->threed_mdl->get_threed($limit, $offset, $years_filter);
		$threed_count = $this->threed_mdl->get_threed(20000,0,$years_filter)->num_rows();
		
		//根据 $status 修改分页路径
		if($status == '3d_one') $site_url_prefix = 'admin/threed/manage?';
		elseif($status == '3d_two') $site_url_prefix = 'admin/threed/manage/3d_two?';
		
		if($threed)
		{
			$pagination = '';
			
			if($threed_count > $limit)
			{	
				$this->dpagination->currentPage($page);
				$this->dpagination->items($threed_count);
				$this->dpagination->limit($limit);
				$this->dpagination->adjacents(5);
				$this->dpagination->target(site_url($site_url_prefix.implode('&',$query)));
				$this->dpagination->parameterName('p');
				$this->dpagination->nextLabel('下一页');
				$this->dpagination->prevLabel('上一页');
				
				$pagination = $this->dpagination->getOutput();
			}
			
			$this->_data['pagination'] = $pagination;
			//$this->_data['cnt'] = $cnt; // 总期号
		}

		/** 已发布，待审核 状态表示变量 **/
		$this->_data['status'] = $status;
		/** 查询所有3D列表 **/
		$this->_data['threed'] = $threed;
		/** 所有年份 **/
		$this->_data['years'] = $this->threed_mdl->get_years();
		/**  西式星期数组 */
		$this->_data['x_week'] = array('一'=>'月','二'=>'火','三'=>'水','四'=>'木','五'=>'金','六'=>'土','日'=>'日');
		// view
		if($status == '3d_one') $this->load->view('admin/manage_3d_one',$this->_data);
		elseif($status == '3d_two') $this->load->view('admin/manage_3d_two',$this->_data);
		
	}

	/**
     * 
     * EXCEL 导出
     * 
     */
     public function excel_write($status='3d_one')
     {
		 // 加载处理主题和副码，数型转换方法类
		$this->load->helper('my_zhuti_fuma');
		/** 所有年份 filter */
		$years_filter = $this->input->get('years', TRUE);
		$years_filter = (!empty($years_filter)) ? intval($years_filter) : 0;

        $this->load->library('Classes/PHPExcel');
		$this->load->library('excel');
		$excel_data = $this->threed_mdl->get_threed('', '', $years_filter);
		$excel_data_count = $excel_data->num_rows();
		$this->excel->writer('',$excel_data->result(),$excel_data_count,$status);
		unset($excel_data);
     }
}

/* End of file Posts.php */
/* Location: ./application/controllers/admin/Posts.php */