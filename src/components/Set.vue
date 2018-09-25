<template>
    <div>
        <div  style="margin-bottom:10px;">
            <div class="line">
                <h4><i class="glyphicon glyphicon-cog"></i> 功能设置</h4>
            </div>
            <form class="form-horizontal" id="cyclesFrom">
                <div class="row ">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">周期时间</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" name="startTime">
                            </div>
                            <div class="col-sm-4 endTime">
                                <input type="date" class="form-control" name="endTime">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class=" form-group">
                            <label for="" class="col-sm-2 control-label">期数标识</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="cycles_num" placeholder="整数类型">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class=" form-group">
                            <label for="" class="col-sm-2 control-label">本期考试部门</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="test_dep" id="test_dep" multiple>
                    
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <span class="btn btn-success" id="setCycles">确认设置</span>
                </div>
            </form>
            <div class="row features">
                <div class="col-sm-12">
                    <span class="btn btn-success" id="isSetCycles">控制开关</span>
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
                    <th>期数</th>
                    <th>考试部门</th>
                    <th>开始时间</th>
                    <th>结束时间</th>
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
                    <span>共 <b class="total-page">1</b> 页</span>
                    <span>,  到第</span>
                    <input id="input-jmppage"  min="1">
                    <span>页</span>
                    <button id="submit-jmppage" class="btn btn-sm btn-info" >确定</button>
                </div>
            </div>
        </div> 
    </div>
</template>
<script>
   
    
</script>