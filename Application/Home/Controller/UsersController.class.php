<?php
namespace Home\Controller;
use Think\Controller;
class UsersController extends CommonController{
    /*
     * 普通用户管理
     */
    public function user(){
        $act = I('get.act');
        switch($act){
            case 'show':
                $page = I('get.page',1);
                $limit = I('get.limit',10);
                $table = M('users');
                $field = "id,account,password,dep,phone";
                $content = $table->field($field)->order('id desc')->page($page,$limit)->select(); //读取数据集，获取表中多行记录
                $count = $table->count();
                //echo $students->getLastSql();exit();
                $pageCount = ceil($count/$limit); //ceil()取整函数
                $result[0]['count'] = $pageCount; //总页码
                $result[1]['content'] = $content; //内容
                $this->ajaxReturn( $result, 'JSON' );
                break;
            case 'edit':
                $id = $_POST['id'];
                $data['account']=$_POST['account'];
                $data['dep']=$_POST['dep'];
                $data['password']=$_POST['password'];
                $data['phone']=$_POST['phone'];
                $state=M('users')->where(array('id'=>$id))->save($data);
                // echo M('users')->getLastSql();die();
                $state===false?$this->ajaxReturn(0):$this->ajaxReturn(1);
                break;
            case 'del':
                $id=I('post.id');
                if (empty($id))$this->ajaxReturn(0);
                $state=M('users')->where(array('id'=>$id))->delete();
                $state===false?$this->ajaxReturn(0):$this->ajaxReturn(1);
                break;
            default:
                break;
        }
    }
    /*
     * 管理员用户管理
     */
}