<template>
    <div>
        <div  style="margin-bottom:10px;">
            <div class="line">
                <h4><i class="glyphicon glyphicon-pencil"></i> 题目列表</h4>
            </div>
            <div class="row features">
                <div class="col-sm-12">
                    <span class="btn btn-sm btn-success" data-toggle="modal" data-target="#addModal">添加题目</span>
                    <span class="btn btn-sm btn-info" data-toggle="modal" data-target="#importModal">批量导入</span>
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
                    <th >题目</th>
                    <th>选项A</th>
                    <th>选项B</th>
                    <th>选项C</th>
                    <th>选项D</th>
                    <th>正确答案</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody >  
                
            </tbody>
        </table> 
        <div class="d-flex justify-content-between" id="page">
            <div class="btn-group limit">
                <button type="button" class="btn btn-default" >5</button>
                <button type="button" class="btn btn-default" >10</button>
                <button type="button" class="btn btn-default" >20</button>
            </div>
            <ul class="pagination  pagination-custom">
                <li class="page-item"><a class="page-link" href="#">上一页</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">下一页</a></li>
            </ul>
            <div class="total">
                <span>共 <b class="total-page">10</b> 页</span>
                <span>,  到第</span>
                <input id="input-jmppage"  min="1">
                <span>页</span>
                <button id="submit-jmppage" class="btn btn-sm btn-info" >确定</button>
            </div>
        </div>            

        <!-- 修改模态框 -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title active">修改题目信息</h4>
                    </div>
                    <div class="modal-body">
                       <form action="" class="form-horizontal question">
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">题目</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="question" placeholder="请输入题目">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">选项A</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="choiceA" placeholder="请输入A选项内容">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">选项B</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="choiceB" placeholder="请输入B选项内容">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">选项C</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="choiceC" placeholder="请输入C选项内容">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">选项D</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="choiceD" placeholder="请输入D选项内容">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">答案</label>
                                <div class="col-sm-10">
                                    <select  class="form-control answer">
                                        <option value="-1">请设置正确答案</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-blue btn-sm" id="edit">修改</button>
                        <button type="button" class="btn btn-sm" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- 添加题目模态框 -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title active">添加题目</h4>
                    </div>
                    <div class="modal-body">
                        <form action="" class="form-horizontal question">
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">题目</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="question" placeholder="请输入题目">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">选项A</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="choiceA" placeholder="请输入A选项内容">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">选项B</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="choiceB" placeholder="请输入B选项内容">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">选项C</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="choiceC" placeholder="请输入C选项内容">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">选项D</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="choiceD" placeholder="请输入D选项内容">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">答案</label>
                                <div class="col-sm-10">
                                    <select  class="form-control answer">
                                        <option value="-1">请设置正确答案</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-blue btn-sm" id="add-question">添加</button>
                        <button type="button" class="btn btn-sm" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- 批量导入题目 -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title active">批量导入题目</h4>
                    </div>
                    <div class="modal-body">
                       <form  class="form-horizontal import" enctype="multipart/form-data" method="post">
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">导入题目</label>
                                <div class="col-sm-10">
                                    <input type="file"  class="form-control" name="import" >
                                </div>
                            </div>

                        </form>
                        <div class="row text-center" >
                            <a class="btn btn-info " href="/index.php/Home/Admin/export_template" target='_self'>下载导入模板</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-blue btn-sm" id="import">导入</button>
                        <button type="button" class="btn btn-sm" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
   
    
</script>