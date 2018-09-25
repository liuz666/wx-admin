<?php
namespace Home\Controller;
use Think\Controller;
class ScoreController extends CommonController{
    public function show(){
        $page = I('get.page',1);
        $limit = I('get.limit',10);
        $search = I( 'get.search', NULL );
        $table = M('score');
        $field = "id,dep,name,right_num,err_num,cycles_num,time";
        if ( $search ) {
            $data = $this->search( $search );
            $where = $data["where"];
            $content = $table->field( $field )->where( $where )->page( $page, $limit )->select();
           // die($table->getLastSql());
            $count = $table->where( $where )->count();
            // echo $table->getLastSql();exit();
        }
        else {
            $content = $table->field($field)->page($page,$limit)->select();
            $count = $table->count();
          // echo $table->getLastSql();
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
                case 'name':
                case 'dep_id':
                case 'time':
                    $where[$key]=array( "like", "%".$value."%" );
                    break;
                default:
                    break;
            }
        }
        $data["where"] = $where;
        return $data;
    }
    public function getDepCycles(){
        $dep = M('department')->field('id,dep_name')->select();
        $cycles = M('set_cycles')->field('id,cycles_num')->select();
        $this->ajaxReturn(['code'=>1,'dep'=>$dep,'cycles'=>$cycles]);
    }
    public function export(){
        $table = M( 'score' );
        $field = 'id,cycles_num,name,dep,right_num,err_num,time';
        $sorting='id desc';
        
        $type = I('get.type'); 
        if($type == 1) {  //为1则勾选id导出
            $ids=explode(',',I('get.id'));
            $where['id']=array('in',$ids);
            $content = $table->field($field)->where($where)->order($sorting)->select();
           // echo $table->getLastSql();
        }else {  //弹出搜索框导出
            $search2 = I( 'get.search', NULL ); //条件
            // var_dump($search2);die();
            $where = array();
            if ($search2) {
                $data = $this->search( $search2 );
                $where = $data["where"];
            }
            // var_dump($where);die();
            $content = $table->field( $field )->where($where)->order($sorting)->select();
            // echo $table->getLastSql();die();

            if (! $content) {
                die("<script>alert('没有数据！');window.location=history.go(-1);</script>");
            }
        }
        $tab_name = array(
            "id" => "id",
            "cycles_num" => "周期数",
            "name" => "姓名",
            "dep" => "部门",
            "right_num" =>'正确个数',
            "err_num"=>'错误个数',
            "time" => "考试日期",
        );
        array_unshift($content,$tab_name); //添加excel表头
        create_xls($content);
    }
    
}
// DROP VIEW IF EXISTS score

/*
CREATE VIEW score as SELECT 
    MAX(record.right_num) as right_num, 
    record.cycles_num as cycles_num, 
    users.account as name, 
    users.dep as dep
FROM 
    record, 
    users 
WHERE  
    record.user_id = users.id
GROUP BY
    record.user_id,record.cycles_num


*/
/*根据周期分组，然后再用每个周期中的每个人分组，再统计数量个数排名*/

/*
 //不计算重复排名
SELECT
    score.right_nums,score.cycles_num,score.name,score.dep,
    @rownum := @rownum + 1 AS rank
FROM
    (
        SELECT
            right_nums,
            cycles_num,
            name,
            dep
        FROM
            score
        GROUP BY
            cycles_num
        ORDER BY
            cycles_num DESC
    ) AS score,
    (SELECT @rownum := 0) r
*/
    




    