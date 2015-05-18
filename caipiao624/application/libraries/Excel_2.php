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
class CI_Excel_2
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
     *相生相克 写入Excel2007
     *@data  输出数据
	 *@data_count  总行数
	 *@cnt  编号起始变量
     */
	function counteract_writer($status='xsx_tiangan',$datas='',$data_count=0)
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
			
			if($status == 'xsx_tiangan' OR $status == 'xsx_dizhi' OR $status == 'xsx_nayin' OR 
			   $status == 'xsx_jgx' OR $status == 'xsx_x_week' OR $status == 'xsx_lunar' OR $status == 'xsx_solar' OR 
			   $status == 'xsx_xc_suishu' OR $status == 'xsx_pid') 
			{
				foreach($datas as $data) 
				{
					$setActiveSheetIndex->setCellValue('A'.$i, $data['created']);
					$setActiveSheetIndex->setCellValue('B'.$i, $data['lottery_qh']);
					$setActiveSheetIndex->setCellValue('C'.$i, $data['kgny']);
					$setActiveSheetIndex->setCellValue('D'.$i, $data['lottery_number']);
					$setActiveSheetIndex->setCellValue('E'.$i, $data['sx']);
					$setActiveSheetIndex->setCellValue('F'.$i, $data['sum']);
					$setActiveSheetIndex->setCellValue('G'.$i, $data['common']);
					$setActiveSheetIndex->setCellValue('H'.$i, $data['counteract_0']);
					$setActiveSheetIndex->setCellValue('I'.$i, $data['xsx_0']);
					$setActiveSheetIndex->setCellValue('J'.$i, $data['counteract_1']);
					$setActiveSheetIndex->setCellValue('K'.$i, $data['xsx_1']);
					$setActiveSheetIndex->setCellValue('L'.$i, $data['counteract_2']);
					$setActiveSheetIndex->setCellValue('M'.$i, $data['xsx_2']);
					$setActiveSheetIndex->setCellValue('N'.$i, $data['counteract_3']);
					$setActiveSheetIndex->setCellValue('O'.$i, $data['xsx_3']);
					$setActiveSheetIndex->setCellValue('P'.$i, $data['counteract_4']);
					$setActiveSheetIndex->setCellValue('Q'.$i, $data['xsx_4']);

					$i++;
				}
				$col = 'Q';
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
		$objPHPExcel->getActiveSheet()->getStyle('D3:D'.$data_count)->getNumberFormat()->setFormatCode('000');
		$objPHPExcel->getActiveSheet()->getStyle('E3:E'.$data_count)->getNumberFormat()->setFormatCode('000');

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

}

//$Excel = new Excel();
//$Excel->read();
//$Excel->writer();
