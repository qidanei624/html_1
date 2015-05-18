<?php

/**
 * 获取 虚主题
 *
 * @access public
 * @param string $nums 开奖号码
 * @return string
 */
if ( ! function_exists('get_xuzhuti')) 
{
	function get_xuzhuti($nums)
	{
		$xuzhuti = NULL;
		$scope	 = range(0, 9);
		$x		 = array_map("xzt_convert", $nums);
		$merge   = array_merge($nums, $x);
		$rs		 = array_diff($scope, $merge);
		$rs		 = array_values($rs);
		
		// 没有虚主题，则记作 [齐]
		if (count($rs) <= 1 && $rs[0] == 0) {
			return '[齐]';
		}

		for ($i = 0; $i < count($rs); $i++) 
		{
			for ($j = 0; $j <= 9; $j++) 
			{	
				if ( $rs[$i] + $j == 10 && $rs[$i] < $j ) {
					$xuzhuti .= '['. $rs[$i] .'.'. $j.']';
				}
			}
		}
		// 0 和 5 同时存在的话 [5.0]
		if ( array_search(0, $rs) !== FALSE && array_search(5, $rs) !== FALSE ) {
			$xuzhuti .= '[5.0]';
		}
		
		return 'ⓧ ' . $xuzhuti;

	}
}

if ( ! function_exists('xzt_convert')) 
{
	function xzt_convert($num)
	{
		return 10-$num;
	}
}

/**
 * 获取 双色球 主题和副码
 *
 * @access public
 * @param string $nums 开奖号码
 * @return string
 */
if ( ! function_exists('get_zhuti_fuma')) 
{
	function get_zhuti_fuma($nums)
	{
		//sort($nums);
		$rs = array();
		$spare = NULL;
		
		// 统计数组中所有的值出现的次数
		$keys = array_count_values($nums);
		// 把数组分割成多个
		$keys = array_chunk($keys, 1, true);
		// 重新整合数组 (关键)
		for ($i = 0; $i < count($keys); $i++) 
		{
			$v = array_keys($keys[$i]);
			$n = array_values($keys[$i]);

			for ($j = 0; $j < $n[0]; $j++) 
			{
				$arr[] = $v[0];
			}
			$rs[$v[0]] = $arr;
			unset($arr);
		}
		
		// 主题的处理
		$result = get_zt_result($rs);
		
		// 副码的处理
		if ( count($rs) > 0 ) 
		{
			foreach($rs as $value)
			{
				foreach($value as $v)
				{
					$spare .= $v . '+';
				}
			}

			$spare = '[' . substr($spare, 0, -1) . ']';
			$result .= $spare;
		}
		
		return $result;
		
	}
}

/**
 * 主题和副码 结果集
 *
 * @access public
 * @param array $rs 重新整合的数组
 * @return string
 */
if ( ! function_exists('get_zt_result')) 
{
	function get_zt_result(&$rs)
	{
		$result = get_check(&$rs, 1, 9);
		$result .= get_check(&$rs, 2, 8);
		$result .= get_check(&$rs, 3, 7);
		$result .= get_check(&$rs, 4, 6);
		$result .= get_check(&$rs, 5, 0);

		return $result;
	}
}

/**
 * 主题和副码 筛选
 *
 * @access public
 * @param array $rs 重新整合的数组
 * @param integer $x 主题类型
 * @param integer $y 主题类型
 * @return string
 */
if ( ! function_exists('get_check') ) 
{
	function get_check($rs, $x, $y)
	{
		$zhuti_fuma = '';

		if ( array_key_exists($x, $rs) || array_key_exists($y, $rs) ) 
		{
			if ( array_key_exists($x, $rs) && array_key_exists($y, $rs) ) 
			{
				if ( count($rs[$x]) >= count($rs[$y])) 
				{
					$zhuti_fuma = '[' . join('+', array_merge($rs[$x], $rs[$y])) . ']';
				} 
				else 
				{
					$zhuti_fuma = '[' . join('+', array_merge($rs[$y], $rs[$x])) . ']';
				}
				
				unset($rs[$x], $rs[$y]);
			}
			// 不存在 19,28...等 主题，只存在单个重复数的判断
			else 
			{
				if ( array_key_exists($x, $rs) && count($rs[$x]) > 1 ) 
				{
					$zhuti_fuma = '[' . join('+', $rs[$x]) . ']';
					unset($rs[$x]);
				}
				else if ( array_key_exists($y, $rs) && count($rs[$y]) > 1 ) 
				{
					$zhuti_fuma = '[' . join('+', $rs[$y]) . ']';
					unset($rs[$y]);
				}
			}
		}

		return $zhuti_fuma;
	}
}

/**
 * 彩票号码的数型转换处理函数
 */
if ( ! function_exists('get_shuxing') ) 
{
	function get_shuxing($number=''){
		if(empty($number)) return;

		$numbers = str_split($number);
		$sx = array_map('shuxing_convert', $numbers);
		//数型号码和值，也成为配伍
		$shuxing['sum'] = (array_sum($sx) >= 10) ? '('.(array_sum($sx)-10).')' : '('.array_sum($sx).')';
		$shuxing['sx'] = implode('',$sx);//数型号码
		$shuxing['xu'] = array_diff(range(0,4), $sx); //虚数型

		return $shuxing;
	}
}
/**
 * 数型转换补助函数
 */
function shuxing_convert($var)
{
	$var = intval($var);
	if($var == 0 OR $var == 1) return 0;
	elseif($var == 2 OR $var == 3) return 1;
	elseif($var == 4 OR $var == 5) return 2;
	elseif($var == 6 OR $var == 7) return 3;
	elseif($var == 8 OR $var == 9) return 4;
}

//$nums  = array(0,9,0,6,9,1);
//$nums  = array(5,5,5);
//echo get_shuangseqiu_zt($nums);
//echo '<br>';
//echo get_xuzhuti($nums);


/* End of file MY_zhuti_fuma_helper.php */
/* Location: ./application/helpers/MY_zhuti_fuma_helper.php */