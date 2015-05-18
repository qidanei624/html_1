<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

if ( ! function_exists('get_counteract'))
{
	// 相生相克结果处理函数
	function get_counteract($prefix='', $num='', $excel=FALSE)
	{
		if(empty($prefix) ||  ! is_numeric($num)) return;
		$suffix = '';

		$arrow_s_r = "<span class='arrow_f'>生</span><span class='arrow_m'>→</span>";
		$arrow_s_l = "<span class='arrow_m'>←</span><span class='arrow_f'>生</span>";
		$arrow_k_r = "<span class='arrow_f'>克</span><span class='arrow_m'>→</span>";
		$arrow_k_l = "<span class='arrow_m'>←</span><span class='arrow_f'>克</span>";
		$arrow	   = "<span class='arrow_f'>刑</span><span class='arrow_m'>↔</span>";

		if($excel)  // Excel 导出时使用
		{
			$arrow_s_r = '生→';
			$arrow_s_l = '←生';
			$arrow_k_r = '克→';
			$arrow_k_l = '←克';
			$arrow = '刑↔';
		}
					  
		$counteract = array(
			'水木'=>$arrow_s_r, '木火'=>$arrow_s_r, '火土'=>$arrow_s_r, '土金'=>$arrow_s_r, '金水'=>$arrow_s_r,
			'木水'=>$arrow_s_l, '火木'=>$arrow_s_l, '土火'=>$arrow_s_l, '金土'=>$arrow_s_l, '水金'=>$arrow_s_l,
			'水火'=>$arrow_k_r, '火金'=>$arrow_k_r, '金木'=>$arrow_k_r, '木土'=>$arrow_k_r, '土水'=>$arrow_k_r,
			'火水'=>$arrow_k_l, '金火'=>$arrow_k_l, '木金'=>$arrow_k_l, '土木'=>$arrow_k_l, '水土'=>$arrow_k_l,
			'水水'=>$arrow, '火火'=>$arrow, '木木'=>$arrow, '金金'=>$arrow, '土土'=>$arrow
		);
		
		if($num == '1' || $num == '6') $suffix = '水';
		elseif($num == '2' || $num == '7') $suffix = '火';
		elseif($num == '3' || $num == '8') $suffix = '木';
		elseif($num == '4' || $num == '9') $suffix = '金';
		elseif($num == '5' || $num == '0') $suffix = '土';
		
		return $counteract[$prefix.$suffix];
		
	}
}
// 生肖-天干、生肖-地支、生肖-纳音 公用的相生相克处理函数(这3个是特殊情况)
function get_sx_counteract($prefix='', $suffix='', $excel=FALSE)
{
	if(empty($prefix) ||  empty($suffix)) return;

	$arrow_s_r = "<span class='arrow_f'>生</span><span class='arrow_m'>→</span>";
	$arrow_s_l = "<span class='arrow_m'>←</span><span class='arrow_f'>生</span>";
	$arrow_k_r = "<span class='arrow_f'>克</span><span class='arrow_m'>→</span>";
	$arrow_k_l = "<span class='arrow_m'>←</span><span class='arrow_f'>克</span>";
	$arrow	   = "<span class='arrow_f'>刑</span><span class='arrow_m'>↔</span>";

	if($excel)  // Excel 导出时使用
	{
		$arrow_s_r = '生→';
		$arrow_s_l = '←生';
		$arrow_k_r = '克→';
		$arrow_k_l = '←克';
		$arrow = '刑↔';
	}
				  
	$counteract = array(
		'水木'=>$arrow_s_r, '木火'=>$arrow_s_r, '火土'=>$arrow_s_r, '土金'=>$arrow_s_r, '金水'=>$arrow_s_r,
		'木水'=>$arrow_s_l, '火木'=>$arrow_s_l, '土火'=>$arrow_s_l, '金土'=>$arrow_s_l, '水金'=>$arrow_s_l,
		'水火'=>$arrow_k_r, '火金'=>$arrow_k_r, '金木'=>$arrow_k_r, '木土'=>$arrow_k_r, '土水'=>$arrow_k_r,
		'火水'=>$arrow_k_l, '金火'=>$arrow_k_l, '木金'=>$arrow_k_l, '土木'=>$arrow_k_l, '水土'=>$arrow_k_l,
		'水水'=>$arrow, '火火'=>$arrow, '木木'=>$arrow, '金金'=>$arrow, '土土'=>$arrow
	);

	return $counteract[$prefix.$suffix];
}
// 位置号码 相生相克处理函数(仅位置号码使用，垂直关系)
function get_vertical_counteract($prefix='', $suffix='', $excel=FALSE)
{
	if(empty($prefix) ||  empty($suffix)) return;

	$arrow_s_b = "<span class='arrow_m'>↓</span><span class='arrow_f'>生</span>";
	$arrow_s_t = "<span class='arrow_m'>↑</span><span class='arrow_f'>生</span>";
	$arrow_k_b = "<span class='arrow_m'>↓</span><span class='arrow_f'>克</span>";
	$arrow_k_t = "<span class='arrow_m'>↑</span><span class='arrow_f'>克</span>";
	$arrow_x   = "<span class='arrow_m'>↕</span><span class='arrow_f'>刑</span>";

	if($excel)  // Excel 导出时使用
	{
		$arrow_s_b = '↓生';
		$arrow_s_t = '↑生';
		$arrow_k_b = '↓克';
		$arrow_k_t = '↑克';
		$arrow_x   = '↕刑';
	}
				  
	$counteract = array(
		'水木'=>$arrow_s_b, '木火'=>$arrow_s_b, '火土'=>$arrow_s_b, '土金'=>$arrow_s_b, '金水'=>$arrow_s_b,
		'木水'=>$arrow_s_t, '火木'=>$arrow_s_t, '土火'=>$arrow_s_t, '金土'=>$arrow_s_t, '水金'=>$arrow_s_t,
		'水火'=>$arrow_k_b, '火金'=>$arrow_k_b, '金木'=>$arrow_k_b, '木土'=>$arrow_k_b, '土水'=>$arrow_k_b,
		'火水'=>$arrow_k_t, '金火'=>$arrow_k_t, '木金'=>$arrow_k_t, '土木'=>$arrow_k_t, '水土'=>$arrow_k_t,
		'水水'=>$arrow_x, '火火'=>$arrow_x, '木木'=>$arrow_x, '金金'=>$arrow_x, '土土'=>$arrow_x
	);

	return $counteract[$prefix.$suffix];
}
// 位号同顺序箭头方向，把连续自然数用箭头方向来表示
function get_order_arrow($flg, $excel=FALSE)
{
	$arrow_b = "<span class='arrow_m'>↓</span>";
	$arrow_t = "<span class='arrow_m'>↑</span>";
	if($excel)  // Excel 导出时使用
	{
		$arrow_b = '↓';
		$arrow_t = '↑';
	}
	if($flg == 't') {		//top
		return $arrow_t;
	}elseif($flg == 'b') { // below
		return $arrow_b;
	}
}
// 生肖名-同顺序箭头，分为 A标准、B标准
function get_sx_order_arrow($x, $y, $flg, $excel=FALSE)
{
	// 按着顺序
	if($flg == 'A') {
		$converts = array('子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥','子');
	}elseif($flg == 'B') {
		$converts = array('子','未','寅','酉','辰','亥','午','丑','申','卯','戌','巳','子');
	}
	
	$arrow_b = "<span class='arrow_m'>↓</span>";
	$arrow_t = "<span class='arrow_m'>↑</span>";
	if($excel)  // Excel 导出时使用
	{
		$arrow_b = '↓';
		$arrow_t = '↑';
	}
	if(array_search($x,$converts) + 1 == array_search($y,$converts)) {
		return $arrow_b;
	}elseif(array_search($x,$converts) - 1 == array_search($y,$converts)) {
		return $arrow_t;
	}
}
// 生肖名-同顺序对角线箭头方向
function get_sx_order_diagonal_arrow($x, $y, $flg, $excel=FALSE)
{
	// 按着顺序
	if($flg == 'A') {
		$converts = array('子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥','子');
	}elseif($flg == 'B') {
		$converts = array('子','未','寅','酉','辰','亥','午','丑','申','卯','戌','巳','子');
	}
	
	$arrow_l = "<span class='arrow_m'>←</span>";
	$arrow_r = "<span class='arrow_m'>→</span>";
	if($excel)  // Excel 导出时使用
	{
		$arrow_l = '←';
		$arrow_r = '→';
	}
	if(array_search($x,$converts) + 1 == array_search($y,$converts)) {
		return $arrow_r;
	}elseif(array_search($x,$converts) - 1 == array_search($y,$converts)) {
		return $arrow_l;
	}
}
// 同顺序对角线箭头方向，把连续自然数用箭头方向来表示
function get_order_diagonal_arrow($x, $y, $excel=FALSE)
{
	$arrow_l = "<span class='arrow_m'>←</span>";
	$arrow_r = "<span class='arrow_m'>→</span>";
	if($excel)  // Excel 导出时使用
	{
		$arrow_l = '←';
		$arrow_r = '→';
	}
	if($x + 1 == $y) {
		return $arrow_r;
	}elseif($x - 1 == $y) {
		return $arrow_l;
	}
}

// 获取尾数为数字的五行,例如 总期号，年度期号
if ( ! function_exists('get_wx'))
{
	function get_wx($str = '')
	{
		if(!is_numeric($str)) return;

		$str_suffix = substr($str, -1); //尾数
		if($str_suffix == '1' || $str_suffix == '6')
		{
			$wuxing = '水';
		}
		elseif($str_suffix == '2' || $str_suffix == '7') 
		{
			$wuxing = '火';
		}
		elseif($str_suffix == '3' || $str_suffix == '8') 
		{
			$wuxing = '木';
		}
		elseif($str_suffix == '4' || $str_suffix == '9') 
		{
			$wuxing = '金';
		}
		elseif($str_suffix == '5' || $str_suffix == '0') 
		{
			$wuxing = '土';
		}

		return $wuxing;
	}
}

// 尾数为数字的相生相克表处理方式
if ( ! function_exists('get_prefix_nums'))
{
	function get_prefix_nums($str=NULL, $num=NULL,$excel=FALSE)
	{
		if(is_null($str) || is_null($num)) return;

		$wuxing = get_wx($str);
		return get_counteract($wuxing, $num, $excel);
	}
}

// 获取天干地支的尾数重新组合函数
if ( ! function_exists('get_ganzhi_ws'))
{
	function get_ganzhi_ws($str='', $num='')
	{
		$rs = '';
		if($str == '壬' || $str == '癸') $rs = $str . '[1,6]';
		else if($str == '丙' || $str == '丁') $rs = $str . '[2,7]';
		else if($str == '甲' || $str == '乙') $rs = $str . '[3,8]';
		else if($str == '庚' || $str == '辛') $rs = $str . '[4,9]';
		else if($str == '戊' || $str == '己') $rs = $str . '[5,0]';

		else if($str == '子' || $str == '亥') $rs = $str . '[1,6]';
		else if($str == '巳' || $str == '午') $rs = $str . '[2,7]';
		else if($str == '寅' || $str == '卯') $rs = $str . '[3,8]';
		else if($str == '申' || $str == '酉') $rs = $str . '[4,9]';
		else if($str == '丑' || $str == '辰' || $str == '未' || $str == '戌') $rs = $str . '[5,0]';
		
		return $rs;
	}
}

// 天干地支的 相生相克表获取函数
if ( ! function_exists('get_ganzhi_counteract'))
{
	function get_ganzhi_counteract($str='', $num='', $excel=FALSE)
	{
		$rs = '';
		// 天干 10个
		if($str == '壬' || $str == '癸') $rs = '水';
		else if($str == '丙' || $str == '丁') $rs = '火';
		else if($str == '甲' || $str == '乙') $rs = '木';
		else if($str == '庚' || $str == '辛') $rs = '金';
		else if($str == '戊' || $str == '己') $rs = '土';
		// 地支 12个
		else if($str == '子' || $str == '亥') $rs = '水';
		else if($str == '巳' || $str == '午') $rs = '火';
		else if($str == '寅' || $str == '卯') $rs = '木';
		else if($str == '申' || $str == '酉') $rs = '金';
		else if($str == '丑' || $str == '辰' || $str == '未' || $str == '戌') $rs = '土';
		
		$rs = get_counteract($rs, $num, $excel);
		return $rs;
	}
}

// 纳音 相生相克关系获取函数
if ( ! function_exists('get_nayin_counteract'))
{
	function get_nayin_counteract($nayin, $num, $excel=FALSE)
	{
		return get_counteract($nayin, $num, $excel);
	}
}
// 中式星期 相生相克关系获取函数
if ( ! function_exists('get_week_counteract')) {
	function get_week_counteract($week, $num, $excel=FALSE) {
		//中式星期数组
		$converts = array('一'=>'水','二'=>'火','三'=>'木','四'=>'金','五'=>'土','六'=>'水','日'=>'火');
		return get_counteract($converts[$week], $num, $excel);
	}
}
// 西式星期 相生相克关系获取函数，为了减少get_counteract()函数的负担，所以重新做了一个,因为西式星期是特殊情况
if ( ! function_exists('get_x_week_counteract'))
{
	function get_x_week_counteract($week, $num, $excel=FALSE)
	{
		if(empty($week) ||  ! is_numeric($num)) return;
		$suffix = '';

		$arrow_s_r = "<span class='arrow_f'>生</span><span class='arrow_m'>→</span>";
		$arrow_s_l = "<span class='arrow_m'>←</span><span class='arrow_f'>生</span>";
		$arrow_k_r = "<span class='arrow_f'>克</span><span class='arrow_m'>→</span>";
		$arrow_k_l = "<span class='arrow_m'>←</span><span class='arrow_f'>克</span>";
		$arrow	   = "<span class='arrow_f'>刑</span><span class='arrow_m'>↔</span>";

		if($excel)  // Excel 导出时使用
		{
			$arrow_s_r = '生→';
			$arrow_s_l = '←生';
			$arrow_k_r = '克→';
			$arrow_k_l = '←克';
			$arrow = '刑↔';
		}

		$counteract = array(
			'水木'=>$arrow_s_r, '木火'=>$arrow_s_r, '火土'=>$arrow_s_r, '土金'=>$arrow_s_r, '金水'=>$arrow_s_r,
			'木水'=>$arrow_s_l, '火木'=>$arrow_s_l, '土火'=>$arrow_s_l, '金土'=>$arrow_s_l, '水金'=>$arrow_s_l,
			'水火'=>$arrow_k_r, '火金'=>$arrow_k_r, '金木'=>$arrow_k_r, '木土'=>$arrow_k_r, '土水'=>$arrow_k_r,
			'火水'=>$arrow_k_l, '金火'=>$arrow_k_l, '木金'=>$arrow_k_l, '土木'=>$arrow_k_l, '水土'=>$arrow_k_l,
			'水水'=>$arrow, '火火'=>$arrow, '木木'=>$arrow, '金金'=>$arrow, '土土'=>$arrow,
			'月木'=>$arrow_s_r, '月火'=>$arrow_k_r, '月水'=>$arrow, '月金'=>$arrow_s_l, '月土'=>$arrow_k_l,
			'日木'=>$arrow_s_l, '日火'=>$arrow, '日水'=>$arrow_k_l, '日金'=>$arrow_k_r, '日土'=>$arrow_s_r,
		);
		
		if($num == '1' || $num == '6') $suffix = '水';
		elseif($num == '2' || $num == '7') $suffix = '火';
		elseif($num == '3' || $num == '8') $suffix = '木';
		elseif($num == '4' || $num == '9') $suffix = '金';
		elseif($num == '5' || $num == '0') $suffix = '土';
		
		return $counteract[$week.$suffix];
	}
}

// 九宫星 相生相克关系获取函数
if ( ! function_exists('get_jgx_counteract')) {
	function get_jgx_counteract($jgx, $num, $excel=FALSE)
	{
		$converts = array(
			'一白'=>'水',
			'二黑'=>'土',
			'五黄'=>'土',
			'八白'=>'土',
			'三碧'=>'木',
			'四绿'=>'木',
			'六白'=>'金',
			'七赤'=>'金',
			'九紫'=>'火'
		);

		return get_counteract($converts[$jgx], $num, $excel);
	}
}

// 农历 相生相克关系获取函数
if ( ! function_exists('get_lunar_counteract')) {
	function get_lunar_counteract($v, $num, $excel=FALSE) {
		$converts = array('一'=>'水','二'=>'火','三'=>'木','四'=>'金','五'=>'土','六'=>'水','七'=>'火','八'=>'木','九'=>'金','十'=>'土');
		$v_suffix = cut_str($v, -1, 1); //截取尾数
		return get_counteract($converts[$v_suffix],$num,$excel);
	}
}
// 天干和地支 所对应的五行属性，生肖-天干和生肖-地支 比较时使用
function get_sx_ganzhi_wuxing($str)
{
	$rs = '';
	// 天干 10个
	if($str == '壬' || $str == '癸') $rs = '水';
	elseif($str == '丙' || $str == '丁') $rs = '火';
	elseif($str == '甲' || $str == '乙') $rs = '木';
	elseif($str == '庚' || $str == '辛') $rs = '金';
	elseif($str == '戊' || $str == '己') $rs = '土';
	// 地支 12个
	elseif($str == '子' || $str == '亥') $rs = '水';
	elseif($str == '巳' || $str == '午') $rs = '火';
	elseif($str == '寅' || $str == '卯') $rs = '木';
	elseif($str == '申' || $str == '酉') $rs = '金';
	elseif($str == '丑' || $str == '辰' || $str == '未' || $str == '戌') $rs = '土';
	
	return $rs;
}


//==========================================	纯杂	============================================
/**
 * 获取纯杂关系 公用函数
 * 
 * @access public
 * @param array   $chun 			纯 数组
 * @param array   $za 				杂 数组
 * @param string  $v 				获取纯杂关系的列值
 * @param string  $num 				中奖号码
 * @param boolean $excel			导出excel， TRUE为导出，默认是FALSE
 */
function get_cz($chun, $za, $v, $num, $excel=FALSE)
{
	$arrow_c   = "<span class='arrow_m'>↔</span><span class='arrow_f'>纯</span>";
	$arrow_z   = "<span class='arrow_m'>↔</span><span class='arrow_f'>杂</span>";
	// Excel 导出时使用
	if($excel)  
	{
		$arrow_c = '↔纯';
		$arrow_z = '↔杂';
	}

	if(isset($chun[$num]) && $chun[$num] == $v) { // ↔纯
		return $arrow_c;
	}
	elseif(isset($za[$num]) && $za[$num] == $v) { // ↔杂
		return $arrow_z;
	}
}
// 纯杂垂直关系处理函数
function get_vertical_cz($chun, $za, $n1, $n2, $excel=FALSE)
{
	$arrow_c   = "<span class='arrow_m'>↕</span><span class='arrow_f'>纯</span>";
	$arrow_z   = "<span class='arrow_m'>↕</span><span class='arrow_f'>杂</span>";
	// Excel 导出时使用
	if($excel)  
	{
		$arrow_c = '↕纯';
		$arrow_z = '↕杂';
	}
	if($chun[$n2] == $n1) { // ↕纯
		return $arrow_c;
	}
	elseif($za[$n2] == $n1) { // ↕杂
		return $arrow_z;
	}
}
// 九宫星 纯杂关系获取函数
function get_jgx_cz($jgx, $num, $excel=FALSE)
{
	// 纯主题数组
	$chun = array('','一白','二黑','三碧','四绿','五黄','六白','七赤','八白','九紫');
	// 杂主题数组
	$za = array('五黄','九紫','八白','七赤','六白','','四绿','三碧','二黑','一白');

	return get_cz($chun, $za, $jgx, $num, $excel);
}
// 农历 纯杂关系获取函数
function get_lunar_cz($v, $num, $excel=FALSE)
{
	$v = cut_str($v, -1, 1); //截取尾数
	// 纯主题数组
	$chun = array('十','一','二','三','四','五','六','七','八','九');
	// 杂主题数组
	$za = array('五','九','八','七','六','十','四','三','二','一');
	return get_cz($chun, $za, $v, $num, $excel);
}
// 中式星期 纯杂关系获取函数
function get_week_cz($v, $num, $excel=FALSE)
{
	$chun = array('','一','二','三','四','五','六','日');
	// 杂主题数组
	$za = array('五','','','日','六','','四','三','二','一');
	return get_cz($chun, $za, $v, $num, $excel);
}
// 总期号、年度期号、新历、岁数  纯杂关系获取函数
function get_numeric_cz($v, $num, $excel=FALSE)
{
	$v = substr($v, -1); //尾数
	// 纯主题数组
	$chun = array('0','1','2','3','4','5','6','7','8','9');
	// 杂主题数组
	$za = array('5','9','8','7','6','0','4','3','2','1');
	return get_cz($chun, $za, $v, $num, $excel);
}
// 位置号码 纯杂 垂直关系处理函数
function get_lottery_cz($n1, $n2, $excel=FALSE)
{
	// 纯主题数组
	$chun = array('0','1','2','3','4','5','6','7','8','9');
	// 杂主题数组
	$za = array('5','9','8','7','6','0','4','3','2','1');
	return get_vertical_cz($chun, $za, $n1, $n2, $excel);
}
//==========================================	等距离加法、乘法	============================================
// 等距离加法、乘法的计算处理 plus_multiply
function get_weishu_pm($v, $num, $flg)
{
	if (!is_numeric($num)) return;
	/**		加法	*/
	if($flg == 'plus_lunar') {
		$converts = array('十','一','二','三','四','五','六','七','八','九');
		$v = cut_str($v, -1, 1); //截取尾数
		//return $num.' ['.substr(array_search($v, $converts) + (int)$num, -1).']';

		return substr(array_search($v, $converts) + (int)$num, -1);

	}elseif($flg == 'plus_jgx') {
		$converts = array('','一白','二黑','三碧','四绿','五黄','六白','七赤','八白','九紫');
		return substr(array_search($v, $converts) + (int)$num, -1);

	}elseif($flg == 'plus_week') {
		$converts = array('','一','二','三','四','五','六','日');
		return substr(array_search($v, $converts) + (int)$num, -1);

	}elseif($flg == 'plus_solar' OR $flg == 'plus_xc_suishu' OR $flg == 'plus_pid' OR 
			$flg=='plus_lottery_qh' OR $flg=='plus_pi' OR $flg=='plus_weihao_vertical' OR $flg=='plus_weihao_zy' OR 
			$flg=='plus_weihao_diagonal_zy') 
	{
		$v = substr($v, -1); //尾数
		return substr((int)$v + (int)$num, -1);
	}
	/**		减法	*/
	elseif($flg == 'minus_lunar') {
		$converts = array('十','一','二','三','四','五','六','七','八','九');
		$v = cut_str($v, -1, 1); //截取尾数
		$rs = array_search($v, $converts) - (int)$num;
		if(array_search($v, $converts) < (int)$num) {
			$rs = array_search($v, $converts) + 10 - (int)$num;
		}
		return $num.' ['.$rs.']';
	}elseif($flg == 'minus_jgx') {
		$converts = array('','一白','二黑','三碧','四绿','五黄','六白','七赤','八白','九紫');
		$rs = array_search($v, $converts) - (int)$num;
		if(array_search($v, $converts) < (int)$num) {
			$rs = array_search($v, $converts) + 10 - (int)$num;
		}
		return $num.' ['.$rs.']';
	}elseif($flg == 'minus_week') {
		$converts = array('','一','二','三','四','五','六','日');
		$rs = array_search($v, $converts) - (int)$num;
		if(array_search($v, $converts) < (int)$num) {
			$rs = array_search($v, $converts) + 10 - (int)$num;
		}
		return $num.' ['.$rs.']';
	}elseif($flg == 'minus_solar' OR $flg == 'minus_xc_suishu' OR $flg == 'minus_pid' OR 
			$flg=='minus_lottery_qh' OR $flg=='minus_pi' OR $flg=='minus_weihao_vertical' OR $flg=='minus_weihao_zy' OR 
			$flg=='minus_weihao_diagonal_zy') 
	{
		$v = substr($v, -1); //尾数
		$rs = $v - (int)$num;
		if($v < (int)$num) {
			$rs = $v + 10 - (int)$num;
		}
		return $num.' ['.$rs.']';
	}
	/**		乘法	*/
	elseif($flg == 'multiply_lunar') {
		$converts = array('十','一','二','三','四','五','六','七','八','九');
		$v = cut_str($v, -1, 1); //截取尾数
		return $num.' ['.substr(array_search($v, $converts) * (int)$num, -1).']';
	}elseif($flg == 'multiply_jgx') {
		$converts = array('','一白','二黑','三碧','四绿','五黄','六白','七赤','八白','九紫');
		return $num.' ['.substr(array_search($v, $converts) * (int)$num, -1).']';
	}elseif($flg == 'multiply_week') {
		$converts = array('','一','二','三','四','五','六','日');
		return $num.' ['.substr(array_search($v, $converts) * (int)$num, -1).']';
	}elseif($flg == 'multiply_solar' OR $flg == 'multiply_xc_suishu' OR $flg == 'multiply_pid' OR 
			$flg=='multiply_lottery_qh' OR $flg=='multiply_pi' OR $flg=='multiply_weihao_vertical' OR $flg=='multiply_weihao_zy' OR 
			$flg=='multiply_weihao_diagonal_zy') 
	{
		$v = substr($v, -1); //尾数
		return $num.' ['.substr((int)$v * (int)$num, -1).']';
	}
	/**		绝对值	*/
	if($flg == 'abs_lunar') {
		$converts = array('十','一','二','三','四','五','六','七','八','九');
		$v = cut_str($v, -1, 1); //截取尾数
		return $num.' ['.abs(array_search($v, $converts) - (int)$num).']';
	}elseif($flg == 'abs_jgx') {
		$converts = array('','一白','二黑','三碧','四绿','五黄','六白','七赤','八白','九紫');
		return $num.' ['.abs(array_search($v, $converts) - (int)$num).']';
	}elseif($flg == 'abs_week') {
		$converts = array('','一','二','三','四','五','六','日');
		return $num.' ['.abs(array_search($v, $converts) - (int)$num).']';
	}elseif($flg == 'abs_solar' OR $flg == 'abs_xc_suishu' OR $flg == 'abs_pid' OR 
			$flg=='abs_lottery_qh' OR $flg=='abs_pi' OR $flg=='abs_weihao_vertical' OR $flg=='abs_weihao_zy' OR 
			$flg=='abs_weihao_diagonal_zy') 
	{
		$v = substr($v, -1); //尾数
		return $num.' ['.abs((int)$v - (int)$num).']';
	}

}
// END

//======================================	主题副码系统处理函数	===================================
// 获取 开奖日干支的 天干和地支的 五行(主题副码系统使用这个函数)
if ( ! function_exists('get_ganzhi_wuxing'))
{
	function get_ganzhi_wuxing($v){
		$rs = '';
		// 天干 10个
		if($v == '壬' || $v == '癸') $rs = '水';
		elseif($v == '丙' || $v == '丁') $rs = '火';
		elseif($v == '甲' || $v == '乙') $rs = '木';
		elseif($v == '庚' || $v == '辛') $rs = '金';
		elseif($v == '戊' || $v == '己') $rs = '土';
		// 地支 12个
		elseif($v == '子' || $v == '亥') $rs = '水';
		elseif($v == '巳' || $v == '午') $rs = '火';
		elseif($v == '寅' || $v == '卯') $rs = '木';
		elseif($v == '申' || $v == '酉') $rs = '金';
		elseif($v == '丑' || $v == '辰' || $v == '未' || $v == '戌') $rs = '土';

		return $rs;
	}
}
// 获取九宫星 五行属性
if ( ! function_exists('get_jgx_wuxing')) {
	function get_jgx_wuxing($jgx)
	{
		$converts = array(
			'一白'=>'水',
			'二黑'=>'土',
			'五黄'=>'土',
			'八白'=>'土',
			'三碧'=>'木',
			'四绿'=>'木',
			'六白'=>'金',
			'七赤'=>'金',
			'九紫'=>'火'
		);

		return $converts[$jgx];
	}
}
// 获取主题五行属性
function get_zhuti_wuxing($v){
	$rs = '';
	if($v == '1' || $v == '9') $rs = '水';
	elseif($v == '2' || $v == '8') $rs = '火';
	elseif($v == '3' || $v == '7') $rs = '木';
	elseif($v == '4' || $v == '6') $rs = '金';
	elseif($v == '5' || $v == '0') $rs = '土';
	return $rs;
}
// 获取西式星期五行属性
function get_x_week_wuxing($v){
	$rs = '';
	if($v == '月' || $v == '水') $rs = '水';
	elseif($v == '火' || $v == '日') $rs = '火';
	elseif($v == '木') $rs = '木';
	elseif($v == '金') $rs = '金';
	elseif($v == '土') $rs = '土';
	return $rs;
}
// 获取中式星期五行属性
function get_week_wuxing($v){
	$rs = '';
	if($v == '一' || $v == '六') $rs = '水';
	elseif($v == '二' || $v == '日') $rs = '火';
	elseif($v == '三') $rs = '木';
	elseif($v == '四') $rs = '金';
	elseif($v == '五') $rs = '土';
	return $rs;
}
// 判断主题和（天干、地支、纳音、九宫星）是否有相同的五行属性
function get_zf_check($nums, $v){
	for($i=0; $i<strlen($nums); $i++) {
		$wx = get_zhuti_wuxing($nums[$i]);
		if($wx == $v) return '○';	
	}
	
	return '×';
}
// END

//=================  主题副码系统-2 和 主题副码系统-3(位号右斜) 处理函数	==============
// 判断主题和（总期号、年度期号、农历、新历、相岁）是否有相同的五行属性
function get_zf_check2($nums, $v){
	if (empty($nums)) return;
	$rs = '';
	for($i=0; $i<strlen($nums); $i++) {
		if(intval($v)+intval($nums[$i]) == 10) {
			$rs .= '反+';
		}elseif( intval($v) == 5 OR intval($nums[$i]) == 5 ){
			if(intval($v) + intval($nums[$i]) == 5) {
				$rs .= '反+';
			}
		}
		elseif(intval($v) == intval($nums[$i])){
			$rs .= '正+';
		}
	}
	if(!empty($rs)){
		$rs = rtrim($rs, '+');
		// 为了要和主题的顺序一样，所以重新排列顺序
		if ($rs == '反+正+反' OR $rs == '正+反+反') return '反+反+正';
		elseif ($rs == '反+正+正' OR $rs == '正+反+正') return '正+正+反';

		return $rs;
	}else{
		return '×';
	}
}
// END

//=================  虚数型 处理函数  ==============
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
		//$shuxing['xu'] = array_diff(range(0,4), $sx); //虚数型

		return $shuxing;
	}
}
/**
 * 虚数型获取函数
 */
function get_xuShuXing($number=''){
	if(empty($number)) return;

	$numbers = str_split($number);
	$sx = array_map('shuxing_convert', $numbers);
	//数型号码和值，也成为配伍
	$xu_shuxing = array_diff(range(0,4), $sx); //虚数型

	return $xu_shuxing;
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
// END


//==========================================	Oter	============================================
/* 
Utf-8、gb2312都支持的汉字截取函数 
cut_str(字符串, 开始长度,截取长度, 编码); 
编码默认为 utf-8 
开始长度默认为 0 
*/ 
function cut_str($string, $start = 0, $sublen, $code = 'UTF-8') 
{ 
    if($code == 'UTF-8') 
    { 
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
        preg_match_all($pa, $string, $t_string); 
 
        if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)); 
        return join('', array_slice($t_string[0], $start, $sublen)); 
    } 
    else 
    { 
        $start = $start*2; 
        $sublen = $sublen*2; 
        $strlen = strlen($string); 
        $tmpstr = ''; 
 
        for($i=0; $i< $strlen; $i++) 
        { 
            if($i>=$start && $i< ($start+$sublen)) 
            { 
                if(ord(substr($string, $i, 1))>129) 
                { 
                    $tmpstr.= substr($string, $i, 2); 
                } 
                else 
                { 
                    $tmpstr.= substr($string, $i, 1); 
                } 
            } 
            if(ord(substr($string, $i, 1))>129) $i++; 
        } 
        //if(strlen($tmpstr)< $strlen ) $tmpstr.= "..."; 
        return $tmpstr; 
    } 
}

/**
 * 十二生肖码 获取函数
 */
if ( ! function_exists('get_shengxiaoma')) {
	function get_shengxiaoma($year, $num) {
		if (! is_numeric($num)) return ;
		$num = (int)$num; // 中奖号码
		// 2000 - 2023年  3D - 十二生肖码
		$years['2000'] = array('未','辰','卯','寅','丑','子','亥','戌','酉','申');
		$years['2012'] = array('未','辰','卯','寅','丑','子','亥','戌','酉','申');
		$years['2001'] = array('申','巳','辰','卯','寅','丑','子','亥','戌','酉');
		$years['2013'] = array('申','巳','辰','卯','寅','丑','子','亥','戌','酉');
		$years['2002'] = array('酉','午','巳','辰','卯','寅','丑','子','亥','戌');
		$years['2014'] = array('酉','午','巳','辰','卯','寅','丑','子','亥','戌');
		$years['2003'] = array('戌','未','午','巳','辰','卯','寅','丑','子','亥');
		$years['2015'] = array('戌','未','午','巳','辰','卯','寅','丑','子','亥');
		$years['2004'] = array('亥','申','未','午','巳','辰','卯','寅','丑','子');
		$years['2016'] = array('亥','申','未','午','巳','辰','卯','寅','丑','子');
		$years['2005'] = array('子','酉','申','未','午','巳','辰','卯','寅','丑');
		$years['2017'] = array('子','酉','申','未','午','巳','辰','卯','寅','丑');
		$years['2006'] = array('丑','戌','酉','申','未','午','巳','辰','卯','寅');
		$years['2018'] = array('丑','戌','酉','申','未','午','巳','辰','卯','寅');
		$years['2007'] = array('寅','亥','戌','酉','申','未','午','巳','辰','卯');
		$years['2019'] = array('寅','亥','戌','酉','申','未','午','巳','辰','卯');
		$years['2008'] = array('卯','子','亥','戌','酉','申','未','午','巳','辰');
		$years['2020'] = array('卯','子','亥','戌','酉','申','未','午','巳','辰');
		$years['2009'] = array('辰','丑','子','亥','戌','酉','申','未','午','巳');
		$years['2021'] = array('辰','丑','子','亥','戌','酉','申','未','午','巳');
		$years['2010'] = array('巳','寅','丑','子','亥','戌','酉','申','未','午');
		$years['2022'] = array('巳','寅','丑','子','亥','戌','酉','申','未','午');
		$years['2011'] = array('午','卯','寅','丑','子','亥','戌','酉','申','未');
		$years['2023'] = array('午','卯','寅','丑','子','亥','戌','酉','申','未');

		return $years[$year][$num];
	}
}



