<!-- 1、模板 html -->
<template>
    <div>
        <div  style="margin-bottom:10px;">
            <div class="line">
                <h4><i class="glyphicon glyphicon-star"></i> 成绩概览</h4>
            </div>
            <form class="form-horizontal" id="searchForm" >
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">周期数</label>
                            <div class="col-sm-6">
                                <select  id="cyclesNum" class="form-control" name="cycles_num">
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">部门</label>
                            <div class="col-sm-6">
                                <select  id="dep" class="form-control" name="dep_id">
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">姓名</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="name" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">考试日期</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="time" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center row" style="margin-top:15px;">
                    <span class="btn btn-info" id="search">立即搜索</span>
                </div>
            </form>
            <div class="row features">
                <div class="col-sm-12">
                    <span class="btn btn-danger btn-sm" id="handleExport">导出excel</span>
                    <span class="btn btn-info btn-sm" id="isSearh">搜索
                        <i class="glyphicon glyphicon-search"></i>
                    </span>
                    <span class="btn btn-primary" id="clearSearch">清除搜索</span>
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
                    <th>ID</th>
                    <th>部门</th>
                    <th>姓名</th>
                    <th >正确个数</th>
                    <th>错误个数</th>
                    <th>期数</th>
                    <th>考试日期</th>
                      
                </tr>
            </thead>
            <tbody >  
                <tr v-for="item in user">
                    <td>item</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table> 
        <div class="d-flex justify-content-between"  id="page">
            <div class="btn-group limit">
                <button type="button" class="btn btn-default" >5</button>
                <button type="button" class="btn btn-default" >10</button>
                <button type="button" class="btn btn-default" >20</button>
            </div>
            <ul class="pagination pagination-custom">
                  <li class="page-item"><a class="page-link" href="#">上一页</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">下一页</a></li>
            </ul>
            <div class="total ">
                <span>共 <b class="total-page">1</b> 页</span>
                <span>,  到第</span>
                <input id="input-jmppage"  min="1">
                <span>页</span>
                <button id="submit-jmppage" class="btn btn-sm btn-info" >确定</button>
            </div>
        </div>
        <!-- 导出筛选 -->
        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title active">导出excel</h4>
                    </div>
                    <div class="modal-body">
                        <form  class="form-horizontal" id="exportForm">
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">部门</label>
                                <div class="col-sm-10">
                                    <select name="dep_id" id="export_dep" class="form-control"></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">姓名</label>
                                <div class="col-sm-10">
                                    <input type="text"  class="form-control" name="name" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">期数</label>
                                <div class="col-sm-10">
                                    <select name="cycles_num" id="export_cyclesNum" class="form-control"></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">考试日期</label>
                                <div class="col-sm-10">
                                    <input type="date"  class="form-control" name="time" >
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-blue btn-sm" id="export">导出</button>
                        <button type="button" class="btn btn-sm" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<!-- 2、行为 处理逻辑 -->
<script>
    export default{
        data() {
            return {
                user :[],
                message: 'Vue的生命周期'
            };
        },
        methods:{
        },
        beforeCreate:function(){
            this.$http.get('http://jsonplaceholder.typicode.com/users').then((data)=>{
                console.log(data)
            })
        },
        mounted: function(){
            $('#isSearh').click(function(){
                console.log(2)
            })
        }   
        /*,
        beforeCreate: function() {
          console.group('------beforeCreate创建前状态------');
          console.log("%c%s", "color:red" , "el     : " + this.$el); //undefined
          console.log("%c%s", "color:red","data   : " + this.$data); //undefined 
          console.log("%c%s", "color:red","message: " + this.message) 
        },
        created: function() {
          console.group('------created创建完毕状态------');
          console.log("%c%s", "color:red","el     : " + this.$el); //undefined
          console.log("%c%s", "color:red","data   : " + this.$data); //已被初始化 
          console.log("%c%s", "color:red","message: " + this.message); //已被初始化
        },
        beforeMount: function() {
          console.group('------beforeMount挂载前状态------');
          console.log("%c%s", "color:red","el     : " + (this.$el)); //已被初始化
          console.log("%c%s", "color:red","data   : " + this.$data); //已被初始化  
          console.log("%c%s", "color:red","message: " + this.message); //已被初始化  
        },
        mounted: function() {
          console.group('------mounted 挂载结束状态------');
          console.log("%c%s", "color:red","el     : " + this.$el); //已被初始化
          console.log("%c%s", "color:red","data   : " + this.$data); //已被初始化
          console.log("%c%s", "color:red","message: " + this.message); //已被初始化 
        },
        beforeUpdate: function () {
          console.group('beforeUpdate 更新前状态===============》');
          console.log("%c%s", "color:red","el     : " + this.$el);
          console.log("%c%s", "color:red","data   : " + this.$data); 
          console.log("%c%s", "color:red","message: " + this.message); 
        },
        updated: function () {
          console.group('updated 更新完成状态===============》');
          console.log("%c%s", "color:red","el     : " + this.$el);
          console.log("%c%s", "color:red","data   : " + this.$data); 
          console.log("%c%s", "color:red","message: " + this.message); 
        },
        beforeDestroy: function () {
          console.group('beforeDestroy 销毁前状态===============》');
          console.log("%c%s", "color:red","el     : " + this.$el);
          console.log("%c%s", "color:red","data   : " + this.$data); 
          console.log("%c%s", "color:red","message: " + this.message); 
        },
        destroyed: function () {
          console.group('destroyed 销毁完成状态===============》');
          console.log("%c%s", "color:red","el     : " + this.$el);
          console.log("%c%s", "color:red","data   : " + this.$data); 
          console.log("%c%s", "color:red","message: " + this.message)
        }*/
    }
   
</script>
<!-- 3、css样式 -->
<style>
    
</style>