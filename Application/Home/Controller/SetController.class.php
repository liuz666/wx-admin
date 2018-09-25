<?php
namespace Home\Controller;
use Think\Controller;
class SetController extends CommonController{
    public function show(){
        $page = I('get.page',1);
        $limit = I('get.limit',10);
        $search = I( 'get.search', NULL );
        $table = M('set_cycles');
        $field = "id,cycles_num,test_dep,start_time,end_time";
        if ( $search ) {
            $data = $this->search( $search );
            $where = $data["where"];
            $content = $table->field($field)->where($where)->order('cycles_num desc')->page($page,$limit)->select();
            $count = $table->where( $where )->count();
            // echo $table->getLastSql();exit();
        }else {
            $content = $table->field($field)->order('cycles_num desc')->page($page,$limit)->select();
            $count = $table->count();
          // echo $table->getLastSql();die();
        }
       
        $pageCount = ceil($count/$limit); //ceil()取整函数
        $result[0]['count'] = $pageCount; //总页码
        $result[1]['content'] = $content; //内容
        $this->ajaxReturn( $result, 'JSON' );
    }
    public function search($search){
        $json_data = search_decode($search);
        $where = array();
        foreach ( $json_data as $key => $value ) {
            $value=trim($value);
            $key=trim($key);
            if ( $value === "" ) {
                continue;
            }
            switch ( $key ) {
                case 'cycles_num':
                case 'dep_id':
                    $where[$key]=array( "like", "%".$value."%" );
                    break;
                default:
                    break;
            }
        }
        $data["where"] = $where;
        return $data;
    }
    public function set_cycles(){
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $cyclesNum = $_POST['cyclesNum'];
        $testDep = $_POST['testDep'];
        if(empty($startTime) || empty($endTime) ||empty($cyclesNum) || empty($testDep)){
            $this->ajaxReturn(['code'=>0,'info'=>' 设置内容不完整!']);
        }
        $setCycles = M('set_cycles')->field('id,cycles_num,start_time,end_time')->select();
        if($setCycles){
            foreach($setCycles as $key => $value){
                $start = $value['start_time'];
                $end = $value['end_time'];
                if($start == $startTime && $end == $endTime){ //开始、结束时间已经存在
                    $this->ajaxReturn(['code'=>0,'info'=>'时间段已经存在']);
                }
                if( $endTime < $end){ //开始、结束时间，在已有时间段之内
                    $this->ajaxReturn(['code'=>0,'info'=>'当前设置时间段已经存在!']);
                }
                if($value['cycles_num'] == $cyclesNum){
                    $this->ajaxReturn(['code'=>0,'info'=>'期数标识已经存在']);
                }
            }  
        }
        
        // var_dump($setCycles);die();
        $dep_name = M('department')->where(array('id'=>array('in',$testDep)))->select();
        $dep_name = array_column($dep_name,'dep_name');
        // echo M('department')->getLastSql();die();
        $data = M('set_cycles')->add(array('start_time'=>$startTime,'end_time'=>$endTime,'cycles_num'=>$cyclesNum,'test_dep'=>implode(',',$dep_name),'dep_id'=>$testDep ));
        if($data){
            $this->ajaxReturn(['code'=>1,'info'=>'设置成功']);
        }else{
            $this->ajaxReturn(['code'=>0,'info'=>'设置失败']);
        }
    }
    public function del(){
        $id=I('post.id');
        if (empty($id))$this->ajaxReturn(0);
        $state=M('set_cycles')->where(array('id'=>$id))->delete();
        $state===false?$this->ajaxReturn(0):$this->ajaxReturn(1);
    }
}