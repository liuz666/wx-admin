<?php
namespace Home\Controller;
use Think\Controller; 
class CommonController extends Controller {
    function _initialize(){
        // var_dump(session('uid') ) ;
        if (empty(session('uid')) ) {
            // $this->error('对不起,您还没有登录!请先登录!', U('Home/Login/login'), 1);
        }
    }
} 
?>