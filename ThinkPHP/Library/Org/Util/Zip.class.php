<?php
namespace Org\Util;

/**
*文件压缩解压类
*/
class Zip 
{
/**
 * 压缩文件
 * @param string $path 需要压缩的文件[夹]路径
 * @param string $savedir 压缩文件所保存的目录
 * @return array zip文件路径
 */
function zip($path) {
    $path=preg_replace('/\/$/', '', $path);
    preg_match('/\/([\d\D][^\/]*)$/', $path, $matches, PREG_OFFSET_CAPTURE);
    $filename=$matches[1][0].".zip";
    // var_dump($filename);
    // set_time_limit(0);
    $zip = new \ZipArchive();
    $zip->open(WWWROOT.'src/API_Down/report/'.$filename,\ZipArchive::CREATE);//return ;
    // var_dump($path);
    if (is_file($path)) {
        $path=preg_replace('/\/\//', '/', $path);
        $base_dir=preg_replace('/\/[\d\D][^\/]*$/', '/', $path);
        $base_dir=addcslashes($base_dir, '/:');
        $localname=preg_replace('/'.$base_dir.'/', '', $path);
        // var_dump($localname);
        $zip->addFile($path,$localname);
        // var_dump($path);
        $zip->close();
        return;
    }elseif (is_dir($path)) {
        $path=preg_replace('/\/[\d\D][\/]*$/', '', $path);
        $base_dir=$path.'/';//基目录
        $base_dir=addcslashes($base_dir, '/:');
        // var_dump($base_dir);
    }
    $path=preg_replace('/\/\//', '/', $path);
    // var_dump($path);
    function addItem($path,&$zip,&$base_dir){
        // var_dump($path);
        $handle = opendir($path);
        // var_dump($path);
        while (false !== ($file = readdir($handle))) {
            if (($file!='.')&&($file!='..')){
                // var_dump($file);
                $ipath=$path.'/'.$file;
                if (is_file($ipath)){//条目是文件
                    $localname=preg_replace('/'.$base_dir.'/', '', $ipath);
                    //var_dump($localname);
                    $zip->addFile($ipath,$localname);
                    // var_dump($r);
                } else if (is_dir($ipath)){
                    addItem($ipath,$zip,$base_dir);
                    $localname=preg_replace('/'.$base_dir.'/', '', $ipath);
                    // var_dump($localname);
                    $zip->addEmptyDir($localname);
                }
                // var_dump($path);
            }
        }
    }
    // var_dump($base_dir);
    addItem($path,$zip,$base_dir);
    $zip->close();
    return $filename;
 }
/**
 * 解压文件
 */
function ezip($zip, $hedef = ''){
    $dirname=preg_replace('/.zip/', '', $zip);
    $root = $_SERVER['DOCUMENT_ROOT'].'/zip/';
    $zip = zip_open($root . $zip);
    @mkdir($root . $hedef . $dirname.'/'.$zip_dosya);
    while($zip_icerik = zip_read($zip)){
        $zip_dosya = zip_entry_name($zip_icerik);
        if(strpos($zip_dosya, '.')){
            $hedef_yol = $root . $hedef . $dirname.'/'.$zip_dosya;
            @touch($hedef_yol);
            $yeni_dosya = @fopen($hedef_yol, 'w+');
            @fwrite($yeni_dosya, zip_entry_read($zip_icerik));
            @fclose($yeni_dosya); 
        }else{
            @mkdir($root . $hedef . $dirname.'/'.$zip_dosya);
        };
    };
}

}