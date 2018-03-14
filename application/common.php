<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
error_reporting(E_ERROR | E_PARSE );
// 应用公共文件
/**
 * 接口返回
 * @param $code
 * @param $msg
 * @param $data
 */
function callBack($code,$msg,$data=[]){
    header('Content-Type:application/json; charset=utf-8');
    $back['code'] = $code;
    if($msg){$back['msg'] = $msg;}
    if($data){$back['data'] = $data;}
    die(json_encode($back,JSON_UNESCAPED_UNICODE));
}
//p方法断点调试
function p($array){
    echo "<pre>".print_r($array,true)."</pre>";exit;
}

/**
 * Log日志
 * @param $message  日志信息
 * @param string $logname   日志名称
 * @param string $logPath   日志地址
 */
function wlog($message,$logname = "",$logPath='./log/'){
    if($logname == ""){
        $path = $logPath.$_SERVER['REQUEST_URI']."/";
    }else{
        $path = $logPath.$logname."/";
    }
    $now = date('[ c ]')."[".$_SERVER['REQUEST_URI']."]";
    if (!is_dir($path)) mkdir($path,0755,true);
    $destination = $path.date('y_m_d').'.log';
    //检测日志文件大小，超过配置大小则备份日志文件重新生成
    if(is_file($destination)){
        rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
    }
    if(is_array($message)){
        array_walk_recursive($message,function(&$item,$key){
            $item = urlencode($item);
        });
        $message = urldecode(json_encode($message));
    }
//     error_log($now."\n".$message."\n",3,$destination,$extra);
    error_log($now."\n".$message."\n",3,$destination);
}

/**

 * excel表格导出
 * @param string $fileName 文件名称
 * @param array $headArr 表头名称
 * @param array $data 要导出的数据
 * @author static7  */
function excelExport($fileName = '', $headArr = [], $data = []) {
    $fileName .= "_" . date("Y_m_d", time()) . ".xls";
    $objPHPExcel = new \PHPExcel();
    $objPHPExcel->getProperties();
    $key = ord("A"); // 设置表头
    foreach ($headArr as $v) {
        $colum = chr($key);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $key += 1;
    }
    $column = 2;
    $objActSheet = $objPHPExcel->getActiveSheet();
    foreach ($data as $key => $rows) { // 行写入
        $span = ord("A");
        foreach ($rows as $keyName => $value) { // 列写入
            $objActSheet->setCellValue(chr($span) . $column, $value);
            $span++;
        }
        $column++;
    }
    $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表
    $objPHPExcel->setActiveSheetIndex(0); // 设置活动单指数到第一个表,所以Excel打开这是第一个表
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename='$fileName'");
    header('Cache-Control: max-age=0');
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); // 文件通过浏览器下载
    exit();
}