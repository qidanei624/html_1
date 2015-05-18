<?php

/** Error reporting */
//error_reporting(E_ALL);

//date_default_timezone_set ('Asia/Shanghai');

/** PHPExcel */
//require_once 'Classes/PHPExcel.php';
/**
 * 输出到页面上的EXCEL
 * 
 * 
 */ 
class CI_Excel
{   
   private $cellArray = array(
                        1=>'A', 2=>'B', 3=>'C', 4=>'D', 5=>'E',
                        6=>'F', 7=>'G', 8=>'H', 9=>'I',10=>'J',
                        11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',
                        16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',
                        21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',
                        26=>'Z',
                        27=>'AA', 28=>'AB', 29=>'AC', 30=>'AD', 31=>'AE',
                        32=>'AF', 33=>'AG', 34=>'AH', 35=>'AI',36=>'AJ',
                        37=>'AK',38=>'AL',39=>'AM',40=>'AN',41=>'AO',
                        42=>'AP',43=>'AQ',44=>'AR',45=>'AS',46=>'AT',
                        47=>'AU',48=>'AV',49=>'AW',50=>'AX',51=>'AY',
                        52=>'AZ', 53=>'BA', 54=>'BB', 55=>'BC', 56=>'BD', 57=>'BE',
                        58=>'BF', 59=>'BG', 60=>'BH', 61=>'BI', 62=>'BJ', 63=>'BK', 64=>'BL');
    /**
     *生成Excel 2007 并输出到浏览器上 
     *@param 表头内容
     *@data  输出数据
	 *@data_count  总行数
	 *@cnt  编号起始变量
     */
    function writer($title,$data,$data_count,$status)
    {   
		$rownum = 1;
		$data_count = $data_count + 2;
		/**  西式星期数组 */
		$x_week = array('一'=>'月','二'=>'火','三'=>'水','四'=>'木','五'=>'金','六'=>'土','日'=>'日');
		//数型数组
		$shuxing = array();
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

		$setActiveSheetIndex = $objPHPExcel->setActiveSheetIndex(0);
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        							 ->setLastModifiedBy("Maarten Balliauw")
        							 ->setTitle("Office 2007 XLSX Test Document")
        							 ->setSubject("Office 2007 XLSX Test Document")
        							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        							 ->setKeywords("office 2007 openxml php")
        							 ->setCategory("Test result file");
        
        //表头循环
		if(!empty($title)) 
		{
			foreach ($title as $tkey => $tvalue)
			{
				$tkey = $tkey+1;   
				$row  = $this->cellArray[$tkey].'1';     //组合行数（开始是第一行）
				// Add some data  //表头
				$setActiveSheetIndex->setCellValue($row, $tvalue);
			}
		}

        //内容循环
		if(is_array($data) && count($data) > 0) 
		{
			$i = 3;
			if($status == '3d_one') {
				foreach($data as $key =>$value) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $value->pid);
					$setActiveSheetIndex->setCellValue('B'.$i, $value->created);
					$setActiveSheetIndex->setCellValue('C'.$i, $value->lottery_qh);
					$setActiveSheetIndex->setCellValue('D'.$i, $value->lottery_ganzhi);
					$setActiveSheetIndex->setCellValue('E'.$i, $value->lunar);
					$setActiveSheetIndex->setCellValue('F'.$i, $value->solar);
					$setActiveSheetIndex->setCellValue('G'.$i, $value->week);
					$setActiveSheetIndex->setCellValue('H'.$i, $x_week[$value->week]);
					$setActiveSheetIndex->setCellValue('I'.$i, $value->lottery_number);
					$setActiveSheetIndex->setCellValue('J'.$i, $value->kgny);
					$setActiveSheetIndex->setCellValue('K'.$i, $value->zhuti_fuma);
					$setActiveSheetIndex->setCellValue('L'.$i, (strpos($value->xuzhuti, '[1.9]') !== FALSE) ? 'ⓧ[1.9]' : '');
					$setActiveSheetIndex->setCellValue('M'.$i, (strpos($value->xuzhuti, '[2.8]') !== FALSE) ? 'ⓧ[2.8]' : '');
					$setActiveSheetIndex->setCellValue('N'.$i, (strpos($value->xuzhuti, '[3.7]') !== FALSE) ? 'ⓧ[3.7]' : '');
					$setActiveSheetIndex->setCellValue('O'.$i, (strpos($value->xuzhuti, '[4.6]') !== FALSE) ? 'ⓧ[4.6]' : '');
					$setActiveSheetIndex->setCellValue('P'.$i, (strpos($value->xuzhuti, '[5.0]') !== FALSE) ? 'ⓧ[5.0]' : '');
					$setActiveSheetIndex->setCellValue('Q'.$i, $value->xc_suishu);
					$setActiveSheetIndex->setCellValue('R'.$i, $value->xc_nayin);
					$setActiveSheetIndex->setCellValue('S'.$i, $value->xc_ganzhi);
					$setActiveSheetIndex->setCellValue('T'.$i, $value->jgx);
					$setActiveSheetIndex->setCellValue('U'.$i, $value->sx_year);

					$i++;
				}
			}
			elseif($status == '3d_two') {
				foreach($data as $key =>$value) 
				{
					$shuxing = get_shuxing($value->lottery_number); //获取数型数据

					$setActiveSheetIndex->setCellValue('A'.$i, $value->pid);
					$setActiveSheetIndex->setCellValue('B'.$i, $value->created);
					$setActiveSheetIndex->setCellValue('C'.$i, $value->lottery_qh);
					$setActiveSheetIndex->setCellValue('D'.$i, $value->lottery_ganzhi);
					$setActiveSheetIndex->setCellValue('E'.$i, $value->lunar);
					$setActiveSheetIndex->setCellValue('F'.$i, $value->solar);
					$setActiveSheetIndex->setCellValue('G'.$i, $value->week);
					$setActiveSheetIndex->setCellValue('H'.$i, $x_week[$value->week]);
					$setActiveSheetIndex->setCellValue('I'.$i, $value->lottery_number);
					$setActiveSheetIndex->setCellValue('J'.$i, $value->kgny);
					$setActiveSheetIndex->setCellValue('K'.$i, $shuxing['sx']);
					$setActiveSheetIndex->setCellValue('L'.$i, $shuxing['sum']);
					$setActiveSheetIndex->setCellValue('M'.$i, (in_array(0, $shuxing['xu'])) ? 'ⓧ0' : '');
					$setActiveSheetIndex->setCellValue('N'.$i, (in_array(1, $shuxing['xu'])) ? 'ⓧ1' : '');
					$setActiveSheetIndex->setCellValue('O'.$i, (in_array(2, $shuxing['xu'])) ? 'ⓧ2' : '');
					$setActiveSheetIndex->setCellValue('P'.$i, (in_array(3, $shuxing['xu'])) ? 'ⓧ3' : '');
					$setActiveSheetIndex->setCellValue('Q'.$i, (in_array(4, $shuxing['xu'])) ? 'ⓧ4' : '');
					$setActiveSheetIndex->setCellValue('R'.$i, $value->xc_suishu);
					$setActiveSheetIndex->setCellValue('S'.$i, $value->xc_nayin);
					$setActiveSheetIndex->setCellValue('T'.$i, $value->xc_ganzhi);
					$setActiveSheetIndex->setCellValue('U'.$i, $value->jgx);
					$setActiveSheetIndex->setCellValue('V'.$i, $value->sx_year);

					$i++;
				}
			}
			
		}
		/** 读取后在指定位置写入内容 30template.php */

		/**  设置 边框线条  23sharedstyles.php */
		$sharedStyle = new PHPExcel_Style();
		$sharedStyle->applyFromArray(
			array(
					'borders' => array(
						'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
			)
		);
		$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle, 'A1:V'.$data_count);

		/** 设置页面方向和大小 */
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		/** 设置页脚 */
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&CPage &P of &N');
		/** 设置对齐(水平对齐， 垂直对齐) */
		//$objPHPExcel->getActiveSheet()->getStyle('C1:C100')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//$objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//$objPHPExcel->getActiveSheet()->getStyle('A18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		/** 设置列宽 */
		//$objPHPExcel->getActiveSheet()->getColumnDimension($this->cellArray[$i])->setAutoSize(true);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
		/** 设置字体 */
		//$objPHPExcel->getActiveSheet()->getStyle('A1:T3000')->getFont()->setName('宋体');
		//$objPHPExcel->getActiveSheet()->getStyle('A1:T3000')->getFont()->setSize(12);
		//$objPHPExcel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);
		/** 格式化单元格的数字格式 */
		//$objPHPExcel->getActiveSheet()->getStyle('F3:F12')->getNumberFormat()->setFormatCode('#,##0.00');
		$objPHPExcel->getActiveSheet()->getStyle('F3:F'.$data_count)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		$objPHPExcel->getActiveSheet()->getStyle('I3:I'.$data_count)->getNumberFormat()->setFormatCode('000');
		if($status == '3d_two') {
			// 格式化数型
			$objPHPExcel->getActiveSheet()->getStyle('K3:K'.$data_count)->getNumberFormat()->setFormatCode('000');
		}
		
		/** 合并单元格 */
		//$objPHPExcel->getActiveSheet()->mergeCells('A18:E22');
		/** 设置打印时候 每页显示10行
		for ($i = 2; $i <= 50; $i++) {
			if ($i % 10 == 0) {
				// Add a page break
				$objPHPExcel->getActiveSheet()->setBreak( 'A' . $i, PHPExcel_Worksheet::BREAK_ROW );
			}
		}*/
		/** 设置打印时候 列的范围 */
 		//$objPHPExcel->getActiveSheet()->setBreak('P12', PHPExcel_Worksheet::BREAK_COLUMN );
		/** 想要在每页重复显示的头部设置（TITLE）,打印使用 */
		$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);

		
       // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$objPHPExcel->setActiveSheetIndex(0);
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MyExcel.xlsx"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    
    /**
     * 读取Excel
     * @param  Excel文件名称 
     * @param  返回数据的键名 
     * @return data   
     */ 
    function read($fileName,$rows='')
    {   
        //$fileName      = "ExcelFile/MyExcel.xlsx";
        $objReader     = new PHPExcel_Reader_Excel2007();
        $objPHPExcel   = $objReader->load("$fileName");
        $sheet         = $objPHPExcel->getActiveSheet();
        $highestRow    = $sheet->getHighestRow();           // 取得总行数  
        $highestColumn = $sheet->getHighestColumn();       // 取得总列数D
        
        $rowMin = array_search($highestColumn,$this->cellArray); //根据返回的总列数D 返回对用的KEY
        
        for($i = 2;$i<=$highestRow;$i++)                   //循环总行数
        {   
            for($a = 1;$a<=$rowMin;$a++)                   //循环总列数 
            {     
                 if(empty($rows))
                 {
                    $data[$i][$a] = $sheet->getCell($this->cellArray[$a].$i)->getValue();      
                 }
                 else
                 {
                    $data[$i][$rows[$a-1]] = $sheet->getCell($this->cellArray[$a].$i)->getValue();         
                 }
                    
            }  
        }
        return $data;
    }
	
	 /**
     *相生相克 写入Excel2007
     *@data  输出数据
	 *@data_count  总行数
	 *@cnt  编号起始变量
     */
	function counteract_writer($status='rownum',$datas='',$data_count=0)
	{
		$data_count = $data_count + 2;
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

		$setActiveSheetIndex = $objPHPExcel->setActiveSheetIndex(0);
		//$getActiveSheet = $objPHPExcel->getActiveSheet();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        							 ->setLastModifiedBy("Maarten Balliauw")
        							 ->setTitle("Office 2007 XLSX Test Document")
        							 ->setSubject("Office 2007 XLSX Test Document")
        							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        							 ->setKeywords("office 2007 openxml php")
        							 ->setCategory("Test result file");
		//内容循环
		if(is_array($datas) && count($datas) > 0) 
		{
			$i = 3;
			$col = '';
			
			if($status == 'rownum') { // 总期号
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['pid_counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['pid_counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['pid_counteract_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['pid_cz_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['pid_cz_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['pid_cz_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);

					$i++;
				}
				$col = 'R';
			}
			elseif($status == 'tiangan') {
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['tiangan']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['tiangan_counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['tiangan_counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['tiangan_counteract_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['z_tiangan']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['z_tiangan_counteract_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['z_tiangan_counteract_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['z_tiangan_counteract_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('S'.$i, $data['c_tiangan']);
					$setActiveSheetIndex->setCellValue('T'.$i, $data['c_tiangan_counteract_1']);
					$setActiveSheetIndex->setCellValue('U'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('V'.$i, $data['c_tiangan_counteract_2']);
					$setActiveSheetIndex->setCellValue('W'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('X'.$i, $data['c_tiangan_counteract_3']);
					$setActiveSheetIndex->setCellValue('Y'.$i, $data['num3']);

					$i++;
				}
				$col = 'Y';
			}
			elseif($status == 'dizhi') { // 地支
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['dizhi']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['dizhi_counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['dizhi_counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['dizhi_counteract_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['z_dizhi']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['z_dizhi_counteract_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['z_dizhi_counteract_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['z_dizhi_counteract_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('S'.$i, $data['c_dizhi']);
					$setActiveSheetIndex->setCellValue('T'.$i, $data['c_dizhi_counteract_1']);
					$setActiveSheetIndex->setCellValue('U'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('V'.$i, $data['c_dizhi_counteract_2']);
					$setActiveSheetIndex->setCellValue('W'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('X'.$i, $data['c_dizhi_counteract_3']);
					$setActiveSheetIndex->setCellValue('Y'.$i, $data['num3']);

					$i++;
				}
				$col = 'Y';
			}
			elseif($status == 'nayin') { // 纳音
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['nayin_counteract_1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['nayin_counteract_2']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['nayin_counteract_3']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['z_nayin']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['z_nayin_counteract_1']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['z_nayin_counteract_2']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['z_nayin_counteract_3']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['c_nayin']);
					$setActiveSheetIndex->setCellValue('S'.$i, $data['c_nayin_counteract_1']);
					$setActiveSheetIndex->setCellValue('T'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('U'.$i, $data['c_nayin_counteract_2']);
					$setActiveSheetIndex->setCellValue('V'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('W'.$i, $data['c_nayin_counteract_3']);
					$setActiveSheetIndex->setCellValue('X'.$i, $data['num3']);

					$i++;
				}
				$col = 'X';
			}
			elseif($status == 'week') { // 中式星期
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['week']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['week_counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['week_counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['week_counteract_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['week']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['week_cz_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['week_cz_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['week_cz_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);

					$i++;
				}
				$col = 'R';
			}
			elseif($status == 'x_week') { // 西式星期
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['x_week']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['week_counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['week_counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['week_counteract_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);

					$i++;
				}
				$col = 'K';
			}
			elseif($status == 'lottery_qh') { // 年度期号
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['qh_counteract_1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['qh_counteract_2']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['qh_counteract_3']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['qh_cz_1']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['qh_cz_2']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['qh_cz_3']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['num3']);

					$i++;
				}
				$col = 'Q';
			}
			elseif($status == 'pi') { // 圆周率
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['pi']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['counteract_1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['counteract_2']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['counteract_3']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('K'.$i);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['pi']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['cz_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['cz_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['cz_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);

					$i++;
				}
				$col = 'R';
			}
			elseif($status == 'suishu') { // 相冲岁数
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['xc_suishu']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['suishu_counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['suishu_counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['suishu_counteract_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['xc_suishu']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['suishu_cz_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['suishu_cz_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['suishu_cz_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);

					$i++;
				}
				$col = 'R';
			}
			elseif($status == 'jgx') { // 九宫星
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['jgx']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['jgx_counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['jgx_counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['jgx_counteract_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['jgx']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['jgx_cz_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['jgx_cz_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['jgx_cz_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);

					$i++;
				}
				$col = 'R';
			}
			elseif($status == 'lunar') { // 农历
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['lunar']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['lunar_counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['lunar_counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['lunar_counteract_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['lunar']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['lunar_cz_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['lunar_cz_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['lunar_cz_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);

					$i++;
				}
				$col = 'R';
			}
			elseif($status == 'solar') { // 新历
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['solar']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['solar_counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['solar_counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['solar_counteract_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['solar']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['solar_cz_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['solar_cz_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['solar_cz_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);

					$i++;
				}
				$col = 'R';
			}
			elseif($status == 'sx_tiangan') {
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['tiangan']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['sx_tiangan_c_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['sx_tiangan_c_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['sx_tiangan_c_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['z_tiangan']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['sx_z_tiangan_c_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['sx_z_tiangan_c_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['sx_z_tiangan_c_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('S'.$i, $data['c_tiangan']);
					$setActiveSheetIndex->setCellValue('T'.$i, $data['sx_c_tiangan_c_1']);
					$setActiveSheetIndex->setCellValue('U'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('V'.$i, $data['sx_c_tiangan_c_2']);
					$setActiveSheetIndex->setCellValue('W'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('X'.$i, $data['sx_c_tiangan_c_3']);
					$setActiveSheetIndex->setCellValue('Y'.$i, $data['num3']);

					$i++;
				}
				$col = 'Y';
			}
			elseif($status == 'sx_dizhi') { // 生肖 - 地支
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['dizhi']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['sx_dizhi_c_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['sx_dizhi_c_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['sx_dizhi_c_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['z_dizhi']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['sx_z_dizhi_c_1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['sx_z_dizhi_c_2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['sx_z_dizhi_c_3']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('S'.$i, $data['c_dizhi']);
					$setActiveSheetIndex->setCellValue('T'.$i, $data['sx_c_dizhi_c_1']);
					$setActiveSheetIndex->setCellValue('U'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('V'.$i, $data['sx_c_dizhi_c_2']);
					$setActiveSheetIndex->setCellValue('W'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('X'.$i, $data['sx_c_dizhi_c_3']);
					$setActiveSheetIndex->setCellValue('Y'.$i, $data['num3']);

					$i++;
				}
				$col = 'Y';
			}
			elseif($status == 'sx_nayin') { // 生肖 - 纳音
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['sx_nayin_c_1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['sx_nayin_c_2']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['sx_nayin_c_3']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['num3']);

					$setActiveSheetIndex->setCellValue('K'.$i, $data['z_nayin']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['sx_z_nayin_c_1']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['sx_z_nayin_c_2']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['sx_z_nayin_c_3']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['num3']);

					$setActiveSheetIndex->setCellValue('R'.$i, $data['c_nayin']);
					$setActiveSheetIndex->setCellValue('S'.$i, $data['sx_c_nayin_c_1']);
					$setActiveSheetIndex->setCellValue('T'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('U'.$i, $data['sx_c_nayin_c_2']);
					$setActiveSheetIndex->setCellValue('V'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('W'.$i, $data['sx_c_nayin_c_3']);
					$setActiveSheetIndex->setCellValue('X'.$i, $data['num3']);

					$i++;
				}
				$col = 'X';
			}
			elseif($status == 'weihao_vertical') { // 位置号码 - 垂直
				$data_count = 2*($data_count-1);
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num3']);
					$i++;
					if ($i >= $data_count-1) break;
					$setActiveSheetIndex->setCellValue('A'.$i);
					$setActiveSheetIndex->setCellValue('B'.$i);
					$setActiveSheetIndex->setCellValue('C'.$i);
					$setActiveSheetIndex->setCellValue('D'.$i);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['counteract_1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['counteract_2']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['counteract_3']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['cz_1']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['cz_2']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['cz_3']);

					$i++;
				}
				$col = 'K';
			}
			elseif($status == 'weihao_zy') { // 位置号码 - 左右斜
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);

					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['counteract_2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['num4']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num5']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['counteract_3']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['num6']);
					$setActiveSheetIndex->setCellValue('N'.$i);

					$setActiveSheetIndex->setCellValue('O'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['cz_1']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('R'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('S'.$i, $data['cz_2']);
					$setActiveSheetIndex->setCellValue('T'.$i, $data['num4']);
					$setActiveSheetIndex->setCellValue('U'.$i, $data['num5']);
					$setActiveSheetIndex->setCellValue('V'.$i, $data['cz_3']);
					$setActiveSheetIndex->setCellValue('W'.$i, $data['num6']);

					$i++;
				}
				$col = 'W';
			}
			elseif($status == 'weihao_diagonal_zy') { // 位置号码 - 对角线左右斜
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);

					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num3']);

					$setActiveSheetIndex->setCellValue('K'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['cz_1']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['cz_2']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['num3']);

					$i++;
				}
				$col = 'O';
			}
			elseif($status == 'weihao_order') { // 位置号码 - 同顺序
				$data_count = 2*($data_count-1);
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num3']);
					$i++;
					if ($i >= $data_count-1) break;
					$setActiveSheetIndex->setCellValue('A'.$i);
					$setActiveSheetIndex->setCellValue('B'.$i);
					$setActiveSheetIndex->setCellValue('C'.$i);
					$setActiveSheetIndex->setCellValue('D'.$i);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['arrow1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['arrow2']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['arrow3']);

					$i++;
				}
				$col = 'G';
			}
			// 位置号码-同顺序对角线, 生肖名-同顺序对角线
			elseif($status == 'weihao_order_diagonal' OR $status == 'sx_order_diagonal') {
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);

					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['arrow1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['arrow2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num3']);

					$i++;
				}
				$col = 'I';
			}
			elseif($status == 'sx_vertical') { // 生肖名 - 垂直生克行
				$data_count = 2*($data_count-1);
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num3']);
					$i++;
					if ($i >= $data_count-1) break;
					$setActiveSheetIndex->setCellValue('A'.$i);
					$setActiveSheetIndex->setCellValue('B'.$i);
					$setActiveSheetIndex->setCellValue('C'.$i);
					$setActiveSheetIndex->setCellValue('D'.$i);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['counteract_1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['counteract_2']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['counteract_3']);

					$i++;
				}
				$col = 'G';
			}
			elseif($status == 'sx_zy') { // 生肖名 - 左右斜
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);

					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['counteract_2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['num4']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['num5']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['counteract_3']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['num6']);

					$i++;
				}
				$col = 'M';
			}
			elseif($status == 'sx_diagonal_zy') { // 生肖名 - 对角线左右斜
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);

					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num3']);

					$i++;
				}
				$col = 'I';
			}
			elseif($status == 'sx_order') { // 生肖名 - 同顺序
				$data_count = 2*($data_count-1);
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['num2']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['num3']);
					$i++;
					if ($i >= $data_count-1) break;
					$setActiveSheetIndex->setCellValue('A'.$i);
					$setActiveSheetIndex->setCellValue('B'.$i);
					$setActiveSheetIndex->setCellValue('C'.$i);
					$setActiveSheetIndex->setCellValue('D'.$i);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['arrow1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['arrow2']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['arrow3']);

					$i++;
				}
				$col = 'G';
			}
			elseif($status == 'plus_lunar') { // 等距离加法系统 - 农历
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['lunar']);
					$setActiveSheetIndex->setCellValue('F'.$i, '+');
					$setActiveSheetIndex->setCellValue('G'.$i, $data['plus_result_1']);
					$setActiveSheetIndex->setCellValue('H'.$i, '+');
					$setActiveSheetIndex->setCellValue('I'.$i, $data['plus_result_2']);
					$setActiveSheetIndex->setCellValue('J'.$i, '+');
					$setActiveSheetIndex->setCellValue('K'.$i, $data['plus_result_3']);

					$i++;
				}
				$col = 'K';
			}
			elseif($status == 'zhuti_fuma') { // 主题副码系统
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['ganzhi']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['lottery_number']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['zhuti_fuma']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['jgx']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['x_week']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['week']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['t']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['d']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['n']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['j']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['x']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['z']);

					$i++;
				}
				$col = 'P';
			}
			elseif($status == 'zhuti_fuma_2') { // 主题副码系统2
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['lunar']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['solar']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['suishu']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['lottery_number']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['zhuti_fuma']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['mark_pid']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['mark_qh']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['mark_lunar']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['mark_solar']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['mark_suishu']);

					$i++;
				}
				$col = 'M';
			}
			elseif($status == 'zhuti_fuma_3') { // 主题副码系统 - 位号右斜系统
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['lottery_number']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['zhuti_fuma']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['a']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['b']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['c']);

					$i++;
				}
				$col = 'I';
			}
			
		}

		unset($datas);

		/**  设置 边框线条  23sharedstyles.php */
		$sharedStyle = new PHPExcel_Style();
		$sharedStyle->applyFromArray(
			array(
					'borders' => array(
						'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
			)
		);
		$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle, 'A1:'.$col.$data_count);
		// 格式化数型
		if($status == 'solar') {
			$objPHPExcel->getActiveSheet()->getStyle('E3:E'.$data_count)->getNumberFormat()->setFormatCode('0.00');
			$objPHPExcel->getActiveSheet()->getStyle('L3:L'.$data_count)->getNumberFormat()->setFormatCode('0.00');
		}elseif($status == 'zhuti_fuma') {
			$objPHPExcel->getActiveSheet()->getStyle('F3:F'.$data_count)->getNumberFormat()->setFormatCode('000');
		}elseif($status == 'zhuti_fuma_2') {
			$objPHPExcel->getActiveSheet()->getStyle('E3:E'.$data_count)->getNumberFormat()->setFormatCode('0.00');
			$objPHPExcel->getActiveSheet()->getStyle('G3:G'.$data_count)->getNumberFormat()->setFormatCode('000');
		}elseif($status == 'zhuti_fuma_3') {
			$objPHPExcel->getActiveSheet()->getStyle('E3:E'.$data_count)->getNumberFormat()->setFormatCode('000');
		}

		/** 设置页面方向和大小 */
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		/** 设置页脚 */
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&CPage &P of &N');
		/** 想要在每页重复显示的头部设置（TITLE）,打印使用 */
		$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MyExcel.xlsx"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
		//echo '<script>';
		//echo "alert(".(memory_get_peak_usage(true) / 1024 / 1024).")";
		//echo '</script>';
        exit;
	}

	/**=======================		等距离加法和乘法系统 excel导出		=========================*/
	function plus_multiplication_writer($status_suffix,$datas='',$data_count=0)
	{
		$data_count = $data_count + 2;
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

		$setActiveSheetIndex = $objPHPExcel->setActiveSheetIndex(0);
		//$getActiveSheet = $objPHPExcel->getActiveSheet();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        							 ->setLastModifiedBy("Maarten Balliauw")
        							 ->setTitle("Office 2007 XLSX Test Document")
        							 ->setSubject("Office 2007 XLSX Test Document")
        							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        							 ->setKeywords("office 2007 openxml php")
        							 ->setCategory("Test result file");
		//循环
		if(is_array($datas) && count($datas) > 0) 
		{
			$i = 3;
			$col = '';
			if($status_suffix == 'weihao_vertical') {// 位号垂直
				$data_count = 2*($data_count-1);
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['result_1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['result_2']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['result_3']);
					$i++;
					if ($i >= $data_count-1) break;
					$setActiveSheetIndex->setCellValue('A'.$i);
					$setActiveSheetIndex->setCellValue('B'.$i);
					$setActiveSheetIndex->setCellValue('C'.$i);
					$setActiveSheetIndex->setCellValue('D'.$i);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['sign']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['sign']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['sign']);

					$i++;
				}
				$col = 'G';
			} elseif($status_suffix == 'weihao_zy') {// 位号左右斜
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['sign']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['result_1']);
					$setActiveSheetIndex->setCellValue('H'.$i);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['num3']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['sign']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['result_2']);
					$setActiveSheetIndex->setCellValue('L'.$i);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['num5']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['sign']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['result_3']);

					$i++;
				}
				$col = 'O';
			}elseif($status_suffix == 'weihao_diagonal_zy') {// 位号对角线左右斜
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['num1']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['sign']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['result_1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['sign']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['result_2']);

					$i++;
				}
				$col = 'I';
			}
			else { // Oter
				foreach($datas as $data) 
				{
					/*
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data[$status_suffix]);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['sign']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['result_1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['sign']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['result_2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['sign']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['result_3']);
					*/
					$setActiveSheetIndex->setCellValue('A'.$i, $data['pid']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data[$status_suffix]);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['counteract_1']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['result_1']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['counteract_2']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['result_2']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['counteract_3']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['result_3']);

					$i++;
				}
				$col = 'K';
			}
		}

		unset($datas);

		/**  设置 边框线条  23sharedstyles.php */
		$sharedStyle = new PHPExcel_Style();
		$sharedStyle->applyFromArray(
			array(
					'borders' => array(
						'top'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
					)
			)
		);
		$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle, 'A1:'.$col.$data_count);
		/** 设置页面方向和大小 */
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		/** 设置页脚 */
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&CPage &P of &N');
		/** 想要在每页重复显示的头部设置（TITLE）,打印使用 */
		$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MyExcel.xlsx"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
	}
}

//$Excel = new Excel();
//$Excel->read();
//$Excel->writer();
