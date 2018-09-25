<?php
namespace Org\Util;

/**
*文件压缩解压类
*/
class File
{
    public function recurse_copy($src,$dst){
        $dir=opendir($src);
        if(!@mkdir($dst)){return array('code'=>0,'info'=>$dst.' ---- 目录不可写或存在！');}
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
    return array('code'=>1,'info'=>$dst);
    }
}