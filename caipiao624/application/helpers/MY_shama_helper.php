<?php
// (上期或者隔一期、隔二期)百位 * multiply + plus 取个位杀对应的位置
function get_shama_a($old='', $new='',$multiply=1,$plus=0,$flg='B', $excel=FALSE)
{
	$rs = array();
	if(empty($old) OR empty($new)) return;

	if($flg == 'B') {
		$rs['num'] = substr((int)substr($new, 0, 1) * $multiply + $plus, -1);
		$shama = get_shama_check($old, $rs['num'], $flg, $excel=FALSE);
		$rs['chk'] = $shama['sign'];
		$rs['error'] = $shama['error'];
	}
	elseif($flg == 'S') {
		$rs['num'] = substr((int)substr($new, 1, 1) * $multiply + $plus, -1);
		$shama = get_shama_check($old, $rs['num'], $flg, $excel=FALSE);
		$rs['chk'] = $shama['sign'];
		$rs['error'] = $shama['error'];
	}
	elseif($flg == 'G') {
		$rs['num'] = substr((int)substr($new, 2, 1) * $multiply + $plus, -1);
		$shama = get_shama_check($old, $rs['num'], $flg, $excel=FALSE);
		$rs['chk'] = $shama['sign'];
		$rs['error'] = $shama['error'];
	}
	
	return $rs;
}
// (上期或者隔一期、隔二期)百位 * multiply + plus 取个位杀对应的位置
function get_shama_b($old='', $new='',$multiply=1,$plus=0,$flg='B', $excel=FALSE)
{
	$rs = array();
	if(empty($old) OR empty($new)) return;

	$rs['num'] = substr((int)substr($new, -1) * $multiply + $plus, -1);
	$shama = get_shama_check($old, $rs['num'], $flg, $excel=FALSE);
	$rs['chk'] = $shama['sign'];
	$rs['error'] = $shama['error'];
	
	return $rs;
}
// 
function get_shama_c($old='', $new='',$multiply=1,$plus=0,$flg='', $excel=FALSE)
{
	$rs = array();
	if(empty($old)) return;

	$rs['num'] = $new;
	$shama = get_shama_check($old, $rs['num'], $flg, $excel=FALSE);
	$rs['chk'] = $shama['sign'];
	$rs['error'] = $shama['error'];
	
	return $rs;
}
//
function get_sk_a($old='', $new='',$multiply=1,$plus=0,$flg='B', $excel=FALSE)
{
	$rs = array();
	if(empty($old) OR empty($new)) return;

	if($flg == 'B') {
		$rs['num'] = substr((int)substr($new, 0, 1) * $multiply + $plus, -1);
		if($old != '000') {
			$rs['sk'] = get_prefix_nums(substr($old,0,1), $rs['num'], $excel);
		}
		$shama = get_shama_check($old, $rs['num'], $flg, 'sk', $excel);
		$rs['chk'] = $shama['sign'];
		$rs['error'] = $shama['error'];
	}
	elseif($flg == 'S') {
		$rs['num'] = substr((int)substr($new, 1, 1) * $multiply + $plus, -1);
		if($old != '000') {
			$rs['sk'] = get_prefix_nums(substr($old,1,1), $rs['num'], $excel);
		}
		$shama = get_shama_check($old, $rs['num'], $flg, 'sk', $excel);
		$rs['chk'] = $shama['sign'];
		$rs['error'] = $shama['error'];
	}
	elseif($flg == 'G') {
		$rs['num'] = substr((int)substr($new, 2, 1) * $multiply + $plus, -1);
		if($old != '000') {
			$rs['sk'] = get_prefix_nums(substr($old,2,1), $rs['num'], $excel);
		}
		$shama = get_shama_check($old, $rs['num'], $flg, 'sk', $excel);
		$rs['chk'] = $shama['sign'];
		$rs['error'] = $shama['error'];
	}
	
	return $rs;
}

// $sk 是 生克的简称
function get_shama_check($old, $num, $flg, $sk, $excel=FALSE)
{
	if($old == '000') return;

	$error = FALSE;
	$rs = array();

	if($excel) {
		$sign = '○';
	}else{
		$sign = "<span style='font-size:14px;font-weight:bold;color: green;'>○</span>";
	}
	
	if($flg == 'B') {
		$v1 = substr($old, 0, 1);
		$v2 = $num;

		if($sk == 'sk') {
			$v1 = get_wx(substr($old, 0, 1));
			$v2 = get_wx($num);
		}
	}elseif($flg == 'S') {
		$v1 = substr($old, 1, 1);
		$v2 = $num;

		if($sk == 'sk') {
			$v1 = get_wx(substr($old, 1, 1));
			$v2 = get_wx($num);
		}
	}elseif($flg == 'G') {
		$v1 = substr($old, 2, 1);
		$v2 = $num;

		if($sk == 'sk') {
			$v1 = get_wx(substr($old, 2, 1));
			$v2 = get_wx($num);
		}
	}
	
	if($flg == 'B' OR $flg == 'S' OR $flg == 'G') {
		if($v1 == $v2) {
			if($excel) {
				$sign = '×';
			}else{
				$sign = "<span style='font-size:14px;font-weight:bold;color: red;'>×</span>";
				$error = TRUE;
			}
		}
	}
	else {
		if(substr($old, 0, 1) == $num OR substr($old, 1, 1) == $num OR substr($old, 2, 1) == $num) {
			if($excel) {
				$sign = '×';
			}else{
				$sign = "<span style='font-size:14px;font-weight:bold;color: red;'>×</span>";
				$error = TRUE;
			}
		}
	}

	$rs['sign'] = $sign;
	$rs['error'] = $error;

	return $rs;
}

//======================生 克========================================
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
// 获取尾数为数字的五行
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

// 尾数为数字的相生相克表处理方式
function get_prefix_nums($str=NULL, $num=NULL,$excel=FALSE)
{
	if(is_null($str) || is_null($num)) return;

	$wuxing = get_wx($str);
	return get_counteract($wuxing, $num, $excel);
}

?>