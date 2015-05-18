<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Shama_mdl extends CI_Model
{
	const TBL_THREED = 'threed';

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
	 * @param int 	  $limit 			条数 (optional)
	 * @param int 	  $offset 			偏移量 (optional)
	 * @param string  $years_filter 	根据年份筛选数据的参数
	 * @param string  $col_filter 		尾数列
	 * @param int     $weishu_filter	尾数 0 - 9 , 尾数列 和 尾数要同时选择才能筛选
     * @param string  $nayins_filter	根据纳音筛选数据的参数
	 * @param string  $zhutis_filter	根据主题筛选数据的参数
     */
	public function get_threed($limit = NULL,$offset = NULL,$order='ASC',$years_s_filter='',$years_e_filter='',$month_s_filter='',$month_e_filter='',$nayins_filter=0)
	{
		$sql = "SELECT  pid,
						DATE_FORMAT(FROM_UNIXTIME(created), '%Y-%m-%d') as created,
						lottery_qh,
						lottery_ganzhi,
						lunar,
						DATE_FORMAT(FROM_UNIXTIME(solar), '%c.%d') as solar,
						week,
						lottery_number,
						SUBSTRING(lottery_number,1,1) AS num1,
						SUBSTRING(lottery_number,2,1) AS num2,
						SUBSTRING(lottery_number,3,1) AS num3,
						kgny,
						zhuti_fuma,
						xuzhuti,
						xc_suishu,
						xc_nayin,
						xc_ganzhi,
						jgx,
						sx_year 
				FROM ".self::TBL_THREED;

		$where = '';
		// 只有开始年份时，
		if(!empty($years_s_filter) AND empty($years_e_filter) AND empty($month_s_filter) AND empty($month_e_filter))
		{
			$where = "created >= ".mktime(0, 0, 0, 1, 0, intval($years_s_filter));
			$limit = 20000;
			$offset = 0;
		}
		// 开始年份 和 开始月份时，
		elseif(!empty($years_s_filter) AND !empty($month_s_filter) AND empty($years_e_filter)  AND empty($month_e_filter))
		{
			$where = "created >= ".mktime(0, 0, 0, intval($month_s_filter), 0, intval($years_s_filter));
			$limit = 20000;
			$offset = 0;
		}
		// 开始年份 和 开始月份 和 结束年份时，
		elseif(!empty($years_s_filter) AND !empty($month_s_filter) AND !empty($years_e_filter)  AND empty($month_e_filter))
		{
			$where = "created >= ".mktime(0, 0, 0, intval($month_s_filter), 0, intval($years_s_filter));
			$where .= " AND created < ".mktime(0, 0, 0, 0, 0, intval($years_e_filter)+1);
			$limit = 20000;
			$offset = 0;
		}
		// 开始年份 和 开始月份 和 结束年份 和 结束月份时，
		elseif(!empty($years_s_filter) AND !empty($month_s_filter) AND !empty($years_e_filter)  AND !empty($month_e_filter))
		{
			$where = "created >= ".mktime(0, 0, 0, intval($month_s_filter), 0, intval($years_s_filter));
			$where .= " AND created < ".mktime(0, 0, 0, intval($month_e_filter)+1, 0, intval($years_e_filter));
			$limit = 20000;
			$offset = 0;
		}
		
		if(!empty($nayins_filter)) 
		{
			if(!empty($where)) $where .= ' AND ';
			$where .= " kgny = '".$nayins_filter."'";
		}		
		if(!empty($where))
		{
			$sql .= ' WHERE '.$where;
		}
		
		//order by
		$order = 'desc';
		$sql .= ' order by pid '.$order;

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
//echo $sql.'<br>';exit;
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
     * 获取 全部 pid
     */
	public function get_pids()
	{
		$sql = "SELECT pid FROM ".self::TBL_THREED." order by pid DESC";

		return $this->db->query($sql);
	}

	/**
     * 隔行查询
     */
	public function get_gehang_result($pid_str='', $limit=NULL,$offset=NULL,$order='ASC',$years_filter=0,$col_filter='',$weishu_filter='',$nayins_filter=0,$zhutis_filter=0)
	{
		$sql = "SELECT  pid,
						DATE_FORMAT(FROM_UNIXTIME(created), '%Y-%m-%d') as created,
						lottery_qh,
						lottery_ganzhi,
						lunar,
						DATE_FORMAT(FROM_UNIXTIME(solar), '%c.%d') as solar,
						week,
						lottery_number,
						kgny,
						zhuti_fuma,
						xuzhuti,
						xc_suishu,
						xc_nayin,
						xc_ganzhi,
						jgx,
						sx_year 
				FROM ".self::TBL_THREED;
		
		$where = '';
		if(!empty($pid_str)) {$where = " pid IN ({$pid_str}) ";}
		else {return ;}

		if(!empty($years_filter)) 
		{
			$where .= " AND DATE_FORMAT(FROM_UNIXTIME(created), '%Y') = ".$years_filter;
		}
		if(!empty($col_filter) && is_numeric($weishu_filter)) { // 列+列尾数
			if(!empty($where)) $where .= ' AND ';

			if($col_filter == '总期号') {
				$where .= " right(pid,1) = '".$weishu_filter."'";
			}elseif ($col_filter == '年度期号') {
				$where .= " right(lottery_qh ,1) = '".$weishu_filter."'";
			}elseif ($col_filter == '岁数') {
				$where .= " right(xc_suishu ,1) = '".$weishu_filter."'";
			}
			elseif ($col_filter == '农历') {
				$converts = array('十','一','二','三','四','五','六','七','八','九');
				$where .= " right(lunar,1) = '".$converts[$weishu_filter]."'";
			}
			elseif ($col_filter == '新历') {
				$where .= " right(DATE_FORMAT(FROM_UNIXTIME(solar), '%d'),1) = '".$weishu_filter."'";
			}
			elseif ($col_filter == '九宫星') {
				$converts = array('','一白','二黑','三碧','四绿','五黄','六白','七赤','八白','九紫');
				if(!empty($converts[$weishu_filter])) $where .= " jgx = '".$converts[$weishu_filter]."'";
			}
			elseif ($col_filter == '中式星期') {
				$converts = array('','一','二','三','四','五','六','日');
				if(!empty($converts[$weishu_filter])) $where .= " week = '".$converts[$weishu_filter]."'";
			}
		}
		elseif(!empty($col_filter) && is_numeric($zhutis_filter)) // 列+列主题
		{
			$zhutis = array();
			$zhutis = str_split($zhutis_filter);
			if(!empty($where)) $where .= ' AND ';
			if($col_filter == '总期号') {
				$where .= ' (right(pid,1) =' .$zhutis[0] . ' OR right(pid,1) = '.$zhutis[1].')';
			}elseif ($col_filter == '年度期号') {
				$where .= ' (right(lottery_qh ,1) = '.$zhutis[0] . ' OR right(lottery_qh,1) = '.$zhutis[1].')';
			}elseif ($col_filter == '岁数') {
				$where .= ' (right(xc_suishu ,1) = '.$zhutis[0] . ' OR right(xc_suishu,1) = '.$zhutis[1].')';
			}elseif ($col_filter == '农历') {
				$converts = array('十','一','二','三','四','五','六','七','八','九');
				$where .= ' (right(lunar,1) = '."'".$converts[$zhutis[0]]."'" . ' OR right(lunar,1) = '."'".$converts[$zhutis[1]]."'".')';
			}elseif ($col_filter == '新历') {
				$where .= " (right(DATE_FORMAT(FROM_UNIXTIME(solar), '%d'),1) = '".$zhutis[0]."' OR right(DATE_FORMAT(FROM_UNIXTIME(solar), '%d'),1) = '".$zhutis[1]."')";
			}elseif ($col_filter == '九宫星') {
				$converts = array('','一白','二黑','三碧','四绿','五黄','六白','七赤','八白','九紫');
				if(!empty($converts[$zhutis[0]]) && !empty($converts[$zhutis[1]])) $where .= " (jgx = '".$converts[$zhutis[0]]."' OR jgx = '".$converts[$zhutis[1]]."')";
			}elseif ($col_filter == '中式星期') {
				$converts = array('','一','二','三','四','五','六','日');
				if(!empty($converts[$zhutis[0]]) && !empty($converts[$zhutis[1]])) $where .= " (week = '".$converts[$zhutis[0]]."' OR week = '".$converts[$zhutis[1]]."')";
			}
		}
		if(!empty($nayins_filter)) 
		{
			$where .= " AND kgny = '".$nayins_filter."'";
		}
		if(!empty($where))
		{
			$sql .= ' WHERE '.$where;
		}
		//order by
		$sql .= ' order by pid '.$order;

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


}

/* End of file counteract_mdl.php */
/* Location: ./application/models/counteract_mdl.php */