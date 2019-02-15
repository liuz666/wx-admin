<?php
/*导出excel模板功能*/
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
    // var_dump($objWriter);die();
    $objWriter->save('php://output');
    exit;
}
/**
 * @method 把excel文件的内容对人数组
 * @staticvar $filePath 文件路径
 * @return type array $data 数组
 */
function format_excel2array($filePath = '', $sheet = 0){
    import("Org.Util.PHPExcel");
    import("Org.Util.PHPExcel.Writer.Excel5");
    import("Org.Util.PHPExcel.IOFactory.php");
    if (empty($filePath) or !file_exists($filePath)) {
        die('file not exists');
    }
    $PHPReader = new PHPExcel_Reader_Excel2007();        //建立reader对象
    if (!$PHPReader->canRead($filePath)) {
        $PHPReader = new PHPExcel_Reader_Excel5();
        if (!$PHPReader->canRead($filePath)) {
            return 'no Excel';
        }
    }
    $PHPExcel = $PHPReader->load($filePath);        //建立excel对象
    $currentSheet = $PHPExcel->getSheet($sheet);        //**读取excel文件中的指定工作表*/
    $allColumn = $currentSheet->getHighestColumn();        //**取得最大的列号*/
    $allRow = $currentSheet->getHighestRow();        //**取得一共有多少行*/
    $data = array();
    for ($rowIndex = 1; $rowIndex <= $allRow; $rowIndex++) {        //循环读取每个单元格的内容。注意行从1开始，列从A开始
        for ($colIndex = 'A'; $colIndex <= $allColumn; $colIndex++) {
            $addr = $colIndex . $rowIndex;
            $cell = $currentSheet->getCell($addr)->getValue();
            if ($cell instanceof PHPExcel_RichText) { //富文本转换字符串
                $cell = $cell->__toString();
            }
            $data[$rowIndex][$colIndex] = $cell;
        }
    }
    return $data;
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
/**
 * @method 去除字符串首尾处的
 * @staticvar array/string $array
 * @return type array/string $array
 */
function trim_array($array)
{
    if (is_array($array)) {
        array_walk_recursive($array, '_trim_');
    } else {
        $array = trim($array);
    }
    return $array;
}
/**
 * @method 去除数组的空值
 * @staticvar array/string $array
 * @return type array/string $array
 */
function array_del_empty($array)
{
    if (is_array($array)) {
        foreach ($array as $k => $v) {
            if (empty($v)) unset($array[$k]);
            elseif (is_array($v)) {
                $array[$k] = array_del_empty($v);
            }
        }
    }
    return $array;
}
//创建目录
function mkdirs($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!mkdirs(dirname($dir), $mode)) return FALSE;
    return @mkdir($dir, $mode);
}
//搜索字符串解码
function search_decode($search) {
    $str=str_replace(" ", "+",$search);
    $json_data = json_decode(base64_decode($str),true );
    return $json_data;
}
function create_xls($data=array(),$filename='score.xls'){
    ini_set('max_execution_time', '0');
    // Vendor('PHPExcel.PHPExcel');
    import("Org.Util.PHPExcel");
    import("Org.Util.PHPExcel.Writer.Excel5");
    import("Org.Util.PHPExcel.IOFactory.php");
    $filename=str_replace('.xls', '', $filename).'.xls';
    $phpexcel = new \PHPExcel();
    $phpexcel->getProperties()
             ->setCreator("Maarten Balliauw")
             ->setLastModifiedBy("Maarten Balliauw")
             ->setTitle("Office 2007 XLSX Test Document")
             ->setSubject("Office 2007 XLSX Test Document")
             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
             ->setKeywords("office 2007 openxml php")
             ->setCategory("Test result file");
    $phpexcel->getActiveSheet()->fromArray($data);
    $phpexcel->getActiveSheet()->setTitle('Sheet1');
    $phpexcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=$filename");
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
    $objwriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
    $objwriter->save('php://output');
    exit;
}