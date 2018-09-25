<template>
    <div>
        <div  style="margin-bottom:10px;">
            <div class="line">
                <h4><i class="glyphicon glyphicon-user"></i> 用户列表</h4>
            </div>
            <div class="row features">
                <div class="col-sm-12">
                    <span class="btn btn-sm btn-info" data-toggle="modal" data-target="#addModal">添加用户</span>
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
                    <th >用户ID</th>
                    <th >用户名</th>
                    <th>部门</th>
                    <th>电话</th>
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

        <!-- 修改模态框 -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title active">修改用户信息</h4>
                    </div>
                    <div class="modal-body">
                       <form action="" class="form-horizontal ">
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">用户名称</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="account" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="password" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">部门</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="dep" >
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">电话</label>
                                <div class="col-sm-10">
                                    <input type="number"  class="form-control" name="phone" maxlength="11">
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
        <!-- 添加用户模态框 -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title active">添加用户</h4>
                    </div>
                    <div class="modal-body">
                        <form action="" class="form-horizontal">
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">用户名称</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="account" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="password" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">部门</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="dep" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">电话</label>
                                <div class="col-sm-10">
                                    <input type="number"  class="form-control" name="phone" maxlength="11">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-blue btn-sm" id="add-user">添加</button>
                        <button type="button" class="btn btn-sm" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>    
<script>
   
</script>
