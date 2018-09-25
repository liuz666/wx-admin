<?php
namespace Home\Controller;

use Think\Controller;
class AdminController extends CommonController{
    
    public function show_question(){
        $page = I('get.page',1);
        $limit = I('get.limit',10);
        $table = M('question');
        $field = "id,question,choiceA,choiceB,choiceC,choiceD,right_answer";
        // $list = $table->field($field)->limit(10)->order('id DESC')->select();
        // var_dump($list);
        $content = $table->field($field)->order('id desc')->page($page,$limit)->select(); //读取数据集，获取表中多行记录
        $count = $table->count();
        //echo $students->getLastSql();exit();
        $pageCount = ceil($count/$limit); //ceil()取整函数
        $result[0]['count'] = $pageCount; //总页码
        $result[1]['content'] = $content; //内容
        $this->ajaxReturn( $result, 'JSON' );
    }
    public function add_question(){
        $data['question']=$_POST['question'];
        $data['choiceA']=$_POST['choiceA'];
        $data['choiceB']=$_POST['choiceB'];
        $data['choiceC']=$_POST['choiceC'];
        $data['choiceD']=$_POST['choiceD'];
        $data['right_answer']=$_POST['right_answer'];
        $data['time'] = date('Y-m-d H:i:s');
        $state=M('question')->data($data)->add();
        $state===false?$this->ajaxReturn(0):$this->ajaxReturn(1);
    }
    public function edit_question(){
        $id = $_POST['id'];
        $data['question']=$_POST['question'];
        $data['choiceA']=$_POST['choiceA'];
        $data['choiceB']=$_POST['choiceB'];
        $data['choiceC']=$_POST['choiceC'];
        $data['choiceD']=$_POST['choiceD'];
        $data['right_answer']=$_POST['right_answer'];
  
        $state=M('question')->where(array('id'=>$id))->save($data);
        // echo M('question')->getLastSql();exit();
        $state===false?$this->ajaxReturn(0):$this->ajaxReturn(1);
    }
    public function del_question(){
        $id=I('post.id');
        if (empty($id))$this->ajaxReturn(0);
        $state=M('question')->where(array('id'=>$id))->delete();
        $state===false?$this->ajaxReturn(0):$this->ajaxReturn(1);
    }
    public function show_news(){
        $page = I('get.page',1);
        $limit = I('get.limit',10);
        $content = M('news')->order('id DESC')->page($page,$limit)->select();
        $count = M('news')->count();
        
        $pageCount = ceil($count/$limit); //ceil()取整函数
        $result[0]['count'] = $pageCount; //总页码
        $result[1]['content'] = $content; //内容
        $this->ajaxReturn( $result, 'JSON' );
    }
    public function del_news(){
        $id=I('post.id');
        if (empty($id))$this->ajaxReturn(0);
        $data = M('news')->field('url,index_img_url')->where(array('id'=>$id))->find();
        if($data){
            $url = explode(',',$data['url']);
            foreach($url as $key=>$val){
                $news_url = explode('/',$val);
                $img_name = array_slice($news_url,-1,1);
                $file_name = array_slice($news_url,-2,1);
                // var_dump('../ueditor/php/upload/image/'.$file_name[0].'/'.$img_name[0] );die();
                unlink('../ueditor/php/upload/image/'.$file_name[0].'/'.$img_name[0]);//删除图片
            }

            $index_url = explode('/',$data['index_img_url']);
            $index_url = array_pop($index_url);
            unlink('Public/Uploads/'.$index_url);
        }
        $state=M('news')->where(array('id'=>$id))->delete();
        $state===false?$this->ajaxReturn(0):$this->ajaxReturn(1);
    }
    public function add_news(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = 'Public/Uploads/' ; // 设置附件上传根目录
        // $upload->savePath = ''; //文件上传的保存路径（相对于根路径
        $upload->autoSub = false; //自动使用子目录保存上传文件 默认为true
        $upload->saveName = time() . '_1'; //上传文件保存命名
        $info = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->ajaxReturn(['code' => 0, 'info' => $upload->getError()]);
        }else{// 上传成功
            $img_name = $info['img']['savename'];//首图片保存名字
            $image = new \Think\Image(); //实例化图像处理功能
            $image->open('Public/Uploads/'.$img_name);  //打开图像
            $image->thumb(300, 300)->save('Public/Uploads/'.$img_name);//按照原图的比例生成一个最大为150*150的缩略图并保存
            $title = $_POST['title'];
            $description = $_POST['description'];
            $news = $_POST['news']; //消息
            $url = $_POST['url'];
            if(empty($news) || empty($description)) $this->ajaxReturn(['code'=>0,'info'=>'标题或简要描述不能为空']);
            $index_url = 'wx/Public/Uploads/'.$img_name;
            $data = M('news')->data(array('title'=>$title,'description'=>$description,'news'=>$news,'url'=>$url,'index_img_url'=>$index_url,'time'=>date('Y-m-d') ))->add();
            if($data === false){
                $this->ajaxReturn(['code' => 1, 'info' => '添加消息失败']);
            }else{
                $this->ajaxReturn(['code' => 1, 'info' => '添加消息成功']);
            }
        }
    }
    public function export_template(){ //下载导入数据模板
        $tab_name = array(
            "question" => "题目名称",
            "choiceA" => "选项A",
            "choiceB" => "选项B",
            "choiceC" => "选项C",
            "choiceD" => "选项D",
            "right_answer" => "正确答案",
        );
        $col = filter_col('question,choiceA,choiceB,choiceC,choiceD,right_answer', $tab_name);
        $expCellName = array();
        foreach ($col as $i => $col_name) {
            $expCellName[] = array($col_name, $tab_name[$col_name]);
        }
        //导出
        export_excel('题目导入', $expCellName, '', 'question','备注信息:题目必须填写,选项至少写一个,正确答案填写格式(A,B,C,D)');
    }
    public function import(){ //批量导入数据
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('xls');// 设置附件上传类型
        $upload->rootPath = 'Public/Uploads/excel/' ; // 设置附件上传根目录
        $upload->autoSub = false;
        $upload->saveName = time() . '_' . mt_rand();
        if (!is_dir('Public/Uploads/excel/')) { //不存在则创建
            mkdirs('Public/Uploads/excel/');
        }
        chmod('Public/Uploads/excel/',0777);
        // 上传文件
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $this->ajaxReturn(['code' => 0, 'info' => $upload->getError()]);
        }else{// 上传成功
            $path = ($upload->rootPath . $upload->saveName . '.xls');
            // var_dump($path);die();
            $array = format_excel2array($path);
            $array = trim_array(array_del_empty(array_del_empty($array))); //excel转为数组格式
            // var_dump($array);
            $err = $i = $sum = 0;
            for($a = 4; $a <=count($array); $a++){
                $question = $array[$a]['A'];
                $choiceA = $array[$a]['B'];
                $choiceB = $array[$a]['C'];
                $choiceC = $array[$a]['D'];
                $choiceD = $array[$a]['E'];
                $right_answer = strtoupper($array[$a]['F']);
                $datas = array( 'question' =>$question, 'choiceA' =>$choiceA, 'choiceB' =>$choiceB, 'choiceC' =>$choiceC,'choiceD' =>$choiceD ,'right_answer' =>$right_answer , 'time' => date('Y-m-d H:i:s') );
                $data = M('question')->data($datas)->add();
                // var_dump($data);
                if ($data === false) {
                    $err = $err + 1;
                    $details[]=$a;
                } else {
                    $i = $i + 1;
                }
                $sum = $sum + 1;
            }
            unlink($path);
            if ($err>0){
                $this->ajaxReturn(['code'=>1,'info'=>"操作成功！本次成功导入：{$i}条，失败：{$err}条\n\n失败条目行号：".implode(',',$details)]);
            }else{
                $this->ajaxReturn(['code'=>1,'info'=>"操作成功！本次成功导入：{$i}条，失败：{$err}条"]);
            }
        }
    }

}
