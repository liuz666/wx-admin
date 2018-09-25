<?php
namespace Home\Controller;

use Think\Controller;
class LoginController extends Controller{
    //用户登录
    public function login() {
        $this->display();//显示登录页面
    }
    // 用户登出
    public function logout(){
        session_destroy(); //清除session
        $this->error('登出成功！', U('Home/Login/login'));
    }
    public function usLogin(){
        $account = $_POST['account'];
        $ps = $_POST['ps'];
        if($account=='' || $ps==''){
            $this->ajaxReturn(['code'=>0,'info'=>'账号或密码不能为空!']);
        }
        $data = M('admin_users')->where(array('account'=>$account))->find();
        // echo  M('users')->getLastSql();
        if($data){
            if($data['password'] != $ps){ $this->ajaxReturn(['code'=>0,'info'=>'密码不正确']);}
            session('uid',$data['id']);
            $this->ajaxReturn(['code'=>1,'info'=>'登录成功']);
        }else{
            $this->ajaxReturn(['code'=>0,'info'=>'用户不存在']);
        }
    }
}