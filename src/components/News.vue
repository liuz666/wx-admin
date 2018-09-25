<template>
    <div>
        <div  style="margin-bottom:10px;">
            <div class="line">
                <h4><i class="glyphicon glyphicon-align-justify"></i> 消息列表</h4>
            </div>
            <div class="row features">
                <div class="col-sm-12">
                    <span class="btn btn-sm btn-info" data-toggle="modal" data-target="#addNewsModal">添加消息</span>
                </div>
            </div>
            
        </div>
        <!--信息展示-->
        <table  class="footable table-striped" id="s-tab">
            <thead>
                <tr >
                    <th>
                        <input type="checkbox">
                    </th>
                    <th >ID</th>
                    <th >标题</th>
                    <th>简要描述</th>
                    <th>url</th>
                    <th>首图url</th>
                    <th>日期</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody >  
                
            </tbody>
        </table> 
        <div id="page">
            <div class="col-md-3">
                <div class="btn-group limit">
                    <button type="button" class="btn btn-default" >5</button>
                    <button type="button" class="btn btn-default" >10</button>
                    <button type="button" class="btn btn-default" >20</button>
                </div>
            </div>
            <div class="col-md-6" align="center">   
                <ul class="pagination  pagination-custom">
                    <li class="prevList"> <a href="javascript:void(0)" id="pre-page">上一页</a></li>
                    <li class="list-item"> <a href="javascript:void(0)">1</a> </li>
                    <li><a href="javascript:void(0)" id="next-page"> 下一页</a></li>
                </ul>
            </div>
            <div class="col-md-3" align="right">
                <div class="total">
                    <span>共 <b class="total-page">10</b> 页</span>
                    <span>,  到第</span>
                    <input id="input-jmppage"  min="1">
                    <span>页</span>
                    <button id="submit-jmppage" class="btn btn-sm btn-info" >确定</button>
                </div>
            </div>
        </div>
        <!-- 查看详情 -->
        <div class="" id="detailModal" style="display:none">
            <div class="detail_title">
                <h2>详情</h2>
            </div>
            <div class="info">
                <div id="info_title">
                    <h4></h4>
                    <span class="btn btn-info btn-sm" id="back">返回</span>
                </div>
                <div id="info_time">
                    <span>日期：</span>
                    <span class="info_time"></span>
                </div>
                <div id="info_description">
                    <span>简要信息:</span>
                    <span class="info_description"></span>
                </div>
                <div id="info_url">
                    <span >图片路径：</span>
                    <pre class="info_url"></pre>
                    
                </div>
                <div id="info_imgName">
                    <span>图片名称：</span>
                    <pre class="info_imgName"></pre>
                </div>
                <div id="info_news">
                    <span>消息内容：</span>
                    <pre class="info_news"></pre>
                </div>
            </div> 
        </div>
        <!-- 添加消息模态框 -->
        <div class="modal fade" id="addNewsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title active">添加消息</h4>
                    </div>
                    <div class="modal-body">
                       <form action="" class="form-horizontal news" id="newsForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">标题</label>
                                <div class="col-sm-10">
                                    <input type="text" name="title" class="form-control" placeholder="标题">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">简要信息</label>
                                <div class="col-sm-10">
                                    <input type="text" name="description" class="form-control" placeholder="简要描述">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">首页图片</label>  
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="img" >
                                </div>                          
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">消息<span style="font-size:12px;color:red">(图片大小50Kb)</span></label>
                                <div class="col-sm-10">
                                    <script id="editor"  style="height:400px;"> </script>
                                </div> 
                            </div>
                           
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-blue btn-sm" id="add-news">添加</button>
                        <button type="button" class="btn btn-sm" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
   
    var ue = UE.getEditor('editor',{
        toolbars: [
            [   'undo', 
                'redo',
                'bold',
                'simpleupload', //单图上传
                'justifyleft', //居左对齐
                'justifyright', //居右对齐
                'justifycenter', //居中对齐
                'justifyjustify', //两端对齐
                'forecolor', //字体颜色
                'help' //帮助
            ]
        ]
        ,zIndex : 1100
    })
    ue.ready(function() { //编辑器准备好后再做操作 
        // ue.setContent('hello'); //设置编辑器的内容
        $('#add-news').click(function(){
            var file = new FormData($('#newsForm')[0]); //实例化对象
            var html = ue.getContent(); //获取html内容
            var url = [];
            if(html.indexOf('img')> -1){
                var reg = /<img.+?>/ig; 
                var arr = html.match(reg);  //匹配所有的img返回数组格式
                var srcReg = /src=[\'\"]?([^\'\"]*)[\'\"]?/i;
                for (var i = 0; i < arr.length; i++) {
                    var src = arr[i].match(srcReg);
                    if(src[1]){ //获取图片地址
                        var u = src[1];
                        url.push(u);
                    }
                }
            }
            url = url.join(',')
            file.append("url",url)
            file.append("news",html)
            $.ajax({
                url: '/index.php/Home/Admin/add_news',
                type: "POST",
                data: file,
                processData: false,
                contentType: false,
                success: function(result) {
                    console.log(result);
                    if (result.code == 0) {
                        alert(result.info);
                    } else {
                        alert(result.info);
                        $('#addNewsModal').modal('hide');
                        window.location.reload();//刷新当前页面.
                    }
                }
            });
        })
    });

</script>