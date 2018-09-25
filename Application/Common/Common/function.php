<?php
/*导出excel功能*/
function export_excel($expTitle, $expCellName, $expTableData, $fileName = '',$subtitle=''){
    // var_dump($expTitle);die();
    import("Org.Util.PHPExcel");
    import("Org.Util.PHPExcel.Writer.Excel5");
    import("Org.Util.PHPExcel.IOFactory.php");

    $xlsTitle = $expTitle;//文件名称
    $fileName = $fileName === '' ? date('YmdHis', time()) : $fileName;
    $cellNum = count($expCellName);
    $dataNum = count($expTableData);
    $subtitle = $subtitle=='' ? '备注信息:' :$subtitle; //副标题

    $objPHPExcel = new \PHPExcel();
    $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');//合并单元格
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", $xlsTitle);

    //设置标题字体
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(14);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setBold(true);
    //水平居中
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:' . $cellName[$cellNum - 1] . ($dataNum + 2))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //副标题设置
    $objPHPExcel->getActiveSheet(0)->mergeCells('A2:' . $cellName[$cellNum - 1] . '2');//合并单元格
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2", $subtitle);
    //副标题靠左
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    //副标题设置填充颜色
    $objPHPExcel->getActiveSheet(0)->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet(0)->getStyle('A2')->getFill()->getStartColor()->setARGB('#FFD700');
    //副标题设置行高
    $objPHPExcel->getActiveSheet(0)->getDefaultRowDimension()->setRowHeight(30);
    //边框
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:' . $cellName[$cellNum - 1] . ($dataNum + 2))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

    // 设置列名
    for ($i = 0; $i < $cellNum; $i++) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '3', $expCellName[$i][1]);
        // $objPHPExcel->getActiveSheet(0)->getColumnDimension($cellName[$i])->setAutoSize(true);
        $objPHPExcel->getActiveSheet(0)->getColumnDimension($cellName[$i])->setWidth(20);//设置列宽
    }
    // 按列导入数据
    for ($i = 0; $i < $dataNum; $i++) {
        for ($j = 0; $j < $cellNum; $j++) {
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 4), $expTableData[$i][$expCellName[$j][0]]);
        }
    }
    // var_dump($objPHPExcel);die();
    ob_end_clean();
    header("Content-Disposition:attachment;filename=$fileName.xls");
    header("Content-Type:application/octet-stream");
    header("Content-Transfer-Encoding:binary");
    header("Pragma:no-cache"); 

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
function filter_col($col_str, $tab_name){
    $col = explode(",", $col_str);
    $res = array();
    foreach ($col as $key => $value) {
        //只用tab_name中存在的数据列
        if (array_key_exists($value, $tab_name)) {
            $res[] = $value;
        }
    }
    return $res;
}