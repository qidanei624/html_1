<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * STBlog Blogging System
 *
 * 基于Codeigniter的单用户多权限开源博客系统
 * 
 * STBlog is an open source multi-privilege blogging System built on the 
 * well-known PHP framework Codeigniter.
 *
 * @package		STBLOG
 * @author		Saturn <huyanggang@gmail.com>
 * @copyright	Copyright (c) 2009 - 2010, cnsaturn.com.
 * @license		GNU General Public License 2.0
 * @link		http://code.google.com/p/stblog/
 * @version		0.1.0
 */
 
// ------------------------------------------------------------------------

/**
 * STBLOG Posts Model Class
 *
 * 3D 操作Model
 *
 * @package		STBLOG
 * @subpackage	Models
 * @category	Models
 * @author		Saturn <huyanggang@gmail.com>
 * @link		http://code.google.com/p/stblog/
 */
class Threed_mdl extends CI_Model {

	const TBL_THREED = 'threed';
	
	/**
     * 列表状态 已发布或者待审核状态
     * 
     * @access private
     * @var array
     */
	private $_threed_status = array('publish', 'waiting');
	
	/**
     * 标识唯一键：{"开奖日期"|"年度期号"|"新历"}
     * 
     * @access private
     * @var array
     */
	private $_unique_key = array('created', 'lottery_qh');

	/**
     * 构造函数
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
		parent::__construct();
		
		log_message('debug', "STBLOG: Posts Model Class Initialized");
    }
	
	/**
     * 获取 3D 列表
     * 
     * @access public
	 * @param string  $type  			内容类型
	 * @param string  $status 			内容状态
	 * @param int 	  $author_id 		作者ID (optional)
	 * @param int 	  $limit 			条数	  (optional)
	 * @param int 	  $offset 			偏移量 (optional)
	 * @param int 	  $category_filter 	需要过滤的栏目ID (optional)
	 * @param int 	  $title_filter 	需要过滤的标题关键字 (optional)
	 * @param bool    $feed_filter		是否显示在feed里面 (optional)
     * @return array  内容列表信息
     */
	public function get_threed($limit = NULL,$offset = NULL,$years_filter = 0)
	{
		$sql = "SELECT  pid,DATE_FORMAT(FROM_UNIXTIME(created), '%Y-%m-%d') as created,lottery_qh,
					lottery_ganzhi,lunar,DATE_FORMAT(FROM_UNIXTIME(solar), '%c.%d') as solar,week,
					lottery_number,kgny,zhuti_fuma,xuzhuti,xc_suishu,xc_nayin,xc_ganzhi,jgx,sx_year 
				FROM ".self::TBL_THREED;

		$where = '';
		if(!empty($years_filter)) 
		{
			$where = "DATE_FORMAT(FROM_UNIXTIME(created), '%Y') = ".$years_filter;
		}

		if(!empty($where))
		{
			$sql .= ' WHERE '.$where;
		}
		
		//order by
		$sql .= ' order by created ASC ';

		//offset
		if(is_numeric($offset))
		{
			$sql .= ' LIMIT '.$offset;
		}

		//limit
		if(is_numeric($limit))
		{
			$sql .= ','.$limit;
		}

		return $this->db->query($sql);
	}
	
	/**
     * 获取DB里所有年份
     * 
     * @access public
	 * @return int 
     */
	public function get_years()
	{
		$sql = "SELECT DISTINCT DATE_FORMAT(FROM_UNIXTIME(created), '%Y') as created 
				FROM ".self::TBL_THREED;

		return $this->db->query($sql);
	}
	
	/**
    * 检查是否存在相同{开奖日期/年度期号/新历}
    * 
    * @access public
	* @param int - $key {start_date, lottery_qh, solar_date}
	* @param int - $value {开奖日期/年度期号/新历}的值
    * @return boolean - success/failure
    */	
	public function check_exist($key = 'created',$value = '')
	{
		if(in_array($key, $this->_unique_key) && !empty($value))
		{
			if ($key == 'created') 
			{
				$where = "$key = UNIX_TIMESTAMP('".$value."')";
			}
			else
			{
				$where = "$key = $value";
			}
			$sql = "SELECT * 
				FROM ".self::TBL_THREED." where $where";

			$query =  $this->db->query($sql);
			$num = $query->num_rows();
			
			$query->free_result();
			
			return ($num > 0) ? TRUE : FALSE;
		}
		
		return FALSE;		
	}
	/**
     * 根据唯一键 获取单个内容信息
     * 
     * @access public
	 * @return int 
     */
	public function get_threed_by_id($pid)
	{
		$data = array();

		$sql = "SELECT FROM_UNIXTIME(created, '%Y-%m-%d') as created,lottery_qh,lottery_ganzhi,lunar,
					FROM_UNIXTIME(solar, '%Y-%m-%d') as solar,week,lottery_number,kgny,zhuti_fuma,
					xuzhuti,xc_suishu,xc_nayin,xc_ganzhi,jgx,sx_year,status 
				FROM ".self::TBL_THREED." WHERE pid = $pid limit 1";
		$query = $this->db->query($sql);

		if($query->num_rows() == 1) 
		{
			$data = $query->row_array();
		}
		$query->free_result();

		return $data;
	}
//----------------------CRUD-------------------------------------------------
	
	/**
     * 添加一个内容
     * 
     * @access public
	 * @param array $content_data  内容
     * @return mixed {post_id | FALSE} 
     */
	public function add_threed($content)
	{
		//$this->db->insert(self::TBL_POSTS, $content_data);
		$sql = "INSERT INTO ".self::TBL_THREED." (`created`, `lottery_qh`, `lottery_ganzhi`, `lunar`, `solar`, `week`, `lottery_number`, `kgny`, `zhuti_fuma`, `xuzhuti`, `xc_suishu`, `xc_nayin`, `xc_ganzhi`, `jgx`, sx_year) VALUES (".
		
		"UNIX_TIMESTAMP('".$content['created']."'),
		{$content['lottery_qh']},    
		'".$content['lottery_ganzhi']."',
		'".$content['lunar']."',         
		UNIX_TIMESTAMP('".$content['solar']."'),         
		'".$content['week']."',          
		'".$content['lottery_number']."',
		'".$content['kgny']."',
		'".$content['zhuti_fuma']."',
		'".$content['xuzhuti']."',   
		{$content['xc_suishu']},     
		'".$content['xc_nayin']."',     
		'".$content['xc_ganzhi']."',    
		'".$content['jgx']."',  
		{$content['sx_year']})";

		$this->db->query($sql);
		return ($this->db->affected_rows() ==1) ? $this->db->insert_id() : FALSE;
	}
	/**
     * 更新内容
     * 
     * @access public
	 * @param array $content_data  内容
     * @return mixed {post_id | FALSE} 
     */
	public function update_threed($pid, $data)
	{
		$sql = "UPDATE ".self::TBL_THREED." SET 
					created = UNIX_TIMESTAMP('".$data['created']."'),
					lottery_qh = {$data['lottery_qh']},
					lottery_ganzhi = '".$data['lottery_ganzhi']."',
					lunar = '".$data['lunar']."',
					solar = UNIX_TIMESTAMP('".$data['solar']."'),
					week = '".$data['week']."',
					lottery_number = '".$data['lottery_number']."',
					kgny = '".$data['kgny']."',
					zhuti_fuma = '".$data['zhuti_fuma']."',
					xuzhuti = '".$data['xuzhuti']."',
					xc_suishu = {$data['xc_suishu']},
					xc_nayin = '".$data['xc_nayin']."',
					xc_ganzhi = '".$data['xc_ganzhi']."',
					jgx = '".$data['jgx']."',
					sx_year = '".$data['sx_year']."'  
				WHERE pid = $pid;";

		$this->db->query($sql);

		return ($this->db->affected_rows() > 0)?TRUE:FALSE;

	}

	/**
    * 修改一个内容状态
    * 
    * @access public
	* @param int $pid 内容ID
	* @param array   $data 内容数组
    * @return boolean 成功或失败
    */	
	public function update_status($pid,$data)
	{
		$sql = "UPDATE ".self::TBL_THREED." SET status='".$data['status']."' WHERE pid = ?" ;
		$this->db->query($sql, array($pid));

		return ($this->db->affected_rows() == 1)?TRUE:FALSE;
	}
	
	/**
     * 删除一个内容
     * 
     * @access public
	 * @param int $pid 内容id
     * @return boolean 成功或失败
     */
	public function remove_threed($pid)
	{
		$sql = "delete from ".self::TBL_THREED." where pid = ?";
		$this->db->query($sql, array(intval($pid)));

		return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
	}
	

}

/* End of file posts_mdl.php */
/* Location: ./application/models/posts_mdl.php */