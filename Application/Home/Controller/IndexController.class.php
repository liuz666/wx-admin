<?php
namespace Home\Controller;

use Think\Controller;
use Think\Model;

class IndexController extends Controller
{
    public function index(){
        echo phpinfo();
    }
     //用户注册
    public function addUser(){
        $rules=$this->add_user_validation();
        $users = M('users');
        if (!$users->validate($rules)->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $info=$users->getError();
            $info=$info==='非法数据对象！'?'参数错误！':$info;
            $this->ajaxReturn(['code'=>0,'info'=>$info]);
        }else{
            // 验证通过 可以进行其他数据操作
            $name=$_REQUEST['account']; /*接收数据*///$_POST[]
            $dep=$_REQUEST['dep'];
            $ph=$_REQUEST['phone'];
            $ps=$_REQUEST['password'];
            if($dep){
                $dep_name = M('department')->where(array('id'=>$dep))->getField('dep_name');
            }
            $data = $users->data(array('account'=>$name,'password'=>$ps,'dep'=>$dep_name,'phone'=>$ph,'dep_id'=>$dep))->add();
            // echo $users->getLastSql();die();
            if($data !== false){
                $this->ajaxReturn(['code'=>1,'info'=>'注册成功']); //成功
            }else{
                $this->ajaxReturn(['code'=>0,'info'=>'注册失败']);
            } 
        }
    }
    //用户登录
    public function login() {
        $map = array();
        $phone = trim(I('post.phone',''));
        $password=trim(I('post.ps',''));
        if ($phone == '' || $password == '') {
            $this->ajaxReturn(['status'=>400,'info'=>'登录失败!']);
        }
        //构造查询条件 电话号码是唯一的
        $map['phone'] =$phone;
        $table = M('users');
        $data = $table->where($map)->find();
        // var_dump($data);
        if($data ){
            if($data['password'] != $password){
                $this->ajaxReturn(['status'=>400,'info'=>'密码错误!']);
            }
            $rsa =  new \Org\Util\RSA();
            $time =time()+60*30; 
            $key = array('uid'=>$data['id'],'time'=>$time);
            // print_r($key);
            $token =$rsa->private_encrypt(serialize($key)); //id,时间序列化数组,然后用rsa私钥加密
            $this->ajaxReturn(['status'=>200,'info'=>'登录成功!','id'=>$token]);
        }else{
            $this->ajaxReturn(['status'=>400,'info'=>'号码不存在!']);
        }
        
    }
    public function show(){
        $rsa =  new \Org\Util\RSA();
        $token = unserialize($rsa->public_decrypt($_POST['id']) );//公匙解密
        if(!$token){
            $this->ajaxReturn(['code'=>0,'info'=>'获取用户ID失败,请重新登录!']);
        }
        $time = $token['time'];
        if($time > time()){
            $time = $time+60*30;
            $key = array('uid'=>$token['uid'],'time'=>$time);
            $token = $rsa->private_encrypt(serialize($key)); //私钥生成新的密码
        }else{
            $this->ajaxReturn(['code'=>0,'info'=>'登录过期,请重新登录']);
        }
        $table = M('question'); 
        $sql = "select * from question order by rand() limit 0,10";
        $list = $table->query($sql);
        // echo $table->getLastSql();
        $arr = [];
        $data= [];
        foreach ($list as $key => $value) {
            $arr['id'] = $value['id'];
            $arr['title'] = $value['question'];
            $arr['option']=[
                ['value' => 'A','name' => $value['choicea']],
                ['value' => 'B','name' => $value['choiceb']],
                ['value' => 'C','name' => $value['choicec']],
                ['value' => 'D','name' => $value['choiced']],
            ];
            array_push($data,$arr);
        }
        // var_dump($data);
        $this->ajaxReturn(['content'=>$data,'id'=>$token]);
          
    }
    public function startTest(){ //验证当前用户是否能考试
        $rsa =  new \Org\Util\RSA();
        $token = unserialize($rsa->public_decrypt($_POST['id']) );//公匙解密
        if(!$token){
            $this->ajaxReturn(['code'=>0,'info'=>'获取用户ID失败,请重新登录!']);
        }
        $user_id = $token['uid'];
        $time = $token['time'];
        if($time > time()){
            $time = $time+60*30;
            $key = array('uid'=>$user_id,'time'=>$time);
            $token = $rsa->private_encrypt(serialize($key)); //私钥生成新的密码
        }else{
            $this->ajaxReturn(['code'=>0,'info'=>'登录过期,请重新登录']);
        }
        $currDate = date('Y-m-d');
        $dep_id = M('users')->where(array('id'=>$user_id))->getField('dep_id');
        $map['dep_id'] = array("like", "%".$dep_id."%"); //部门条件
        $map['start_time'] = array('ELT',$currDate); //当前日期 >= 开始日期
        $map['end_time'] = array('EGT',$currDate);  //当前日期 <= 结束日期
        $cycles = M('set_cycles')->field('id,cycles_num,start_time,end_time')->where($map
                        )->select();
        // echo M('set_cycles')->getLastSql();die();
        if($cycles){
            $cycles_num = $cycles[0]['cycles_num'];
            $this->ajaxReturn(['code'=>2,'cyclesNum'=>$cycles_num,'id'=>$token]);
        }else{
            $this->ajaxReturn(['code'=>1,'info'=>'考试暂未开始','id'=>$token]);
        }
    }
    public function submit(){
        $arr = json_decode($_POST['val'],true); 
        $str = array_slice($arr,0,count($arr)-2); //提交的内容
        $uid = array_slice($arr,-2,1); //提交的加密id
        $cycles_num = array_slice($arr,-1); //提交的期数
        $uid = $uid[0]['value'];
        foreach ($str as $key => $value) {
           $val[$value['id']] = $value['value'];          
        }
        $rsa =  new \Org\Util\RSA();
        $token = unserialize($rsa->public_decrypt($uid ) ); //解码
        $user_id = $token['uid'];
        // var_dump($token);
        if(!$token){
            $this->ajaxReturn(['code'=>0,'info'=>'获取用户ID失败,请重新登录']);
        }
        $time = $token['time'];
        if($time > time()){
            $time = $time+60*30;
            $key = array('uid'=>$user_id,'time'=>$time);
            $token = $rsa->private_encrypt(serialize($key)); //私钥生成新的密码
        }else{
            $this->ajaxReturn(['code'=>0,'info'=>'登录过期,请重新登录']);
        }
        
        //当前用户做的题目答案更新在answer数据表,可以查看错题 
        foreach ($str as $key => $value) {
            $answer = M('answer')->where(array('user_id'=>$user_id,'ques_id' =>$value['id'] ))->select();
            if($answer == NULL){
                M('answer')->add(array('user_id'=>$user_id,'ques_id'=>$value['id'],'answer'=>$value['value']));
            }else{
                M('answer')->where(array('user_id'=>$user_id,'ques_id' =>$value['id'] ))->save(array('answer'=>$value['value'])); 
            }
        }
        //对比question题库表 判断答案的正确
        $table = M('question');
        $data = $table->where(array('id' => array( 'in',implode(',',array_column($str,'id')))))->select();
        // echo $table->getLastSql();
        $err = $i =  0;
        foreach ( $data as $key => $value) { 
            $id = $value['id'];
            $input_answer = $val[$id];//用户提交的答案
            $right_answer = $value['right_answer']; //数据库的正确答案
            if($input_answer == $right_answer){ 
                // echo "正确";
                $i = $i + 1;
            }else{
                $details[] = $value['id'];
                $err = $err + 1;
            }
        }
        //在record数据表中保存记录用户每次答题正确与错误的个数，做排名统计
        $userData = M('users')->where(array('id'=>$user_id))->getField('account,dep');
        $user_name = array_keys($userData);
        $dep_name = $userData[$user_name[0]];
        M('record')->add(array('user_id'=>$user_id,'name'=>$user_name[0],'dep'=>$dep_name,'right_num'=>$i,'err_num'=>$err,'time'=>date('Y-m-d'),'cycles_num'=>$cycles_num[0]['cycles'] ));
        if ($err>0){

            $this->ajaxReturn(['code'=>1,'info'=>"本次答对：{$i}题，答错：{$err}题","errid"=>implode(',',$details),'id'=> $token]);
        }else{
            $this->ajaxReturn(['code'=>2,'info'=>"全部答对",'id'=> $token]);
        }
    }
    public function showerr(){
        $rsa =  new \Org\Util\RSA();
        $token = unserialize($rsa->public_decrypt($_POST['id']) );
        $user_id = $token['uid'];
        if(!$token){
            $this->ajaxReturn(['code'=>0,'info'=>'获取用户ID失败,请重新登录']);
        }
        $time = $token['time'];
        if($time > time()){
            $time = $time+60*30;
            $key = array('uid'=>$user_id,'time'=>$time);
            $token = $rsa->private_encrypt(serialize($key)); //私钥生成新的密码
        }else{
            $this->ajaxReturn(['code'=>0,'info'=>'登录过期,请重新登录']);
        }
        $id = $_POST['errid'];
        $id = explode(',', $id);
        $uid = $_POST['uid'];
        $table = M('question');
        $field = 'answer.ques_id,answer.answer,question.id,question.question,question.choiceA,question.choiceB,question.choiceC,question.choiceD,question.right_answer';
        $list = M('answer')->field($field)->join('left join question on answer.ques_id = question.id')->where(array('ques_id' => array('in',implode(',',$id)),'user_id'=>$user_id))->select();
        // echo M('answer')->getLastSql(); die();
        $arr = [];
        $data= [];
        foreach ($list as $key => $value) {
            $arr['id'] = $value['id'];
            $arr['title'] = $value['question'];
            $arr['option']=[
                ['value' => 'A','name' =>$value['choicea']],
                ['value' => 'B','name' =>$value['choiceb']],
                ['value' => 'C','name' =>$value['choicec']],
                ['value' => 'D','name' =>$value['choiced']],
            ];
            $arr['right_answer'] = $value['right_answer'];
            $arr['checked'] = $value['answer'];
            array_push($data,$arr);
        }
        // var_dump($data);
        $this->ajaxReturn(['code'=>1,'content'=>$data,'id'=>$token]);
    }
    //显示消息列表
    public function show_news(){
        $rsa =  new \Org\Util\RSA();
        $token = unserialize($rsa->public_decrypt($_POST['id']) );
        $user_id = $token['uid'];
        if(!$token){
            $this->ajaxReturn(['code'=>0,'info'=>'获取用户ID失败,请重新登录']);
        }
        $time = $token['time'];
        if($time > time()){
            $time = $time+60*30;
            $key = array('uid'=>$user_id,'time'=>$time);
            $token = $rsa->private_encrypt(serialize($key)); //私钥生成新的密码
        }else{
            $this->ajaxReturn(['code'=>0,'info'=>'登录过期,请重新登录']);
        }
        $data = M('news')->limit(10)->order('id DESC')->select();
        $this->ajaxReturn(['content'=>$data,'id'=>$token]);
    }
    //验证信息
    private function add_user_validation(){
        $rules = array(
            //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
            array('account','require','名称不能为空',1), 
            array('account','','名称已经存在！',0,'unique',1),
            array('phone','','号码已经存在！',0,'unique',1),
            array('dep','require','部门不能为空',1),
            array('password','require','密码不能为空',1),      
        );
        return $rules;
    }
    //用户答题排名
    public function rank(){
        $type = I('post.type');
        $value = I('post.value', '');
        $rsa =  new \Org\Util\RSA();
        $token = unserialize($rsa->public_decrypt($_POST['id']) );
        $user_id = $token['uid'];
        if(!$token){
            $this->ajaxReturn(['code'=>0,'info'=>'获取用户ID失败,请重新登录']);
        }
        $time = $token['time'];
        if($time > time()){
            $time = $time+60*30;
            $key = array('uid'=>$user_id,'time'=>$time);
            $token = $rsa->private_encrypt(serialize($key)); //私钥生成新的密码
        }else{
            $this->ajaxReturn(['code'=>0,'info'=>'登录过期,请重新登录']);
        }
        switch($type){
            case 'M':
                $cycles_num = M('set_cycles')->field('cycles_num')->select();
                $cycles_num = array_column($cycles_num, 'cycles_num');
                if($value ){
                    $where['cycles_num'] = $value;
                }else{
                    $where['cycles_num'] = $cycles_num[0];
                }
                break;
            case 'Y':
                $where['time'] = array("like", "%" . $value . "%");
                break;
        }
        $data = M('score')->field('uid,name,max(right_num) as right_num')->where($where)->group('name')->order('max(right_num) DESC')->limit(10)->select();
        // echo M('score')->getLastSql();die();
        $currUid = false;
        foreach ($data as $key => $value) {
           if($value['uid']==$user_id) $currUid = true; //1-10名中有登录用户
        }
        if(!$currUid){ //1-10名中没有登录用户继续查找当前用户的排名
            $name = M('users')->where(array('id'=>$user_id))->getField('account');
            $data2 = M('score')->field('uid,name,max(right_num) as right_num')->where($where)->group('name')->order('max(right_num) DESC')->select();
            if(count($data2)<=10){ //登录用户没有考试记录
                $rank = 0;
            }else{
                foreach ($data2 as $key => $value) {
                    if($value['uid'] == $user_id){
                        $rank = $key+1;
                    }
                }
            }
        }
        if($data){
            $this->ajaxReturn(['status'=>1,'content'=>$data,'id'=>$token,'cycles'=>$cycles_num,'currName'=>$name,'currRank'=>$rank]);
        }else{
            $this->ajaxReturn(['status'=>0,'content'=>$data,'id'=>$token,'cycles'=>$cycles_num,'currName'=>$name,'currRank'=>$rank]);
        }
    }
    public function getDep(){
        $data = M('department')->select();
        $this->ajaxReturn($data,'JSON');
    }
    public function getOpenId(){
        $code = $_GET['js_code'];
        $appid = $_GET['appid'];
        $secret = $_GET['secret'];
        $get_token_url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$get_token_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch); //输出内容
        curl_close($ch); //关闭连接
        $this->ajaxReturn($res);
    }
}