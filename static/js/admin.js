(function(window){
    var current = 1;//当前页数，
    var limitNum = 10; //每页条目
    var URL = '',
        searchVal = '';
    var Table = function() {
       
    }
    Table.fn = Table.prototype = { //Table对象下面添加init()方法，对象动态添加方法用 .prototype属性
        constructor: Table,
        init: function(pageName) {
            this.pageName = pageName;
            URL = this.getUrl('show'); //对象里面调用自己的方法或者属性 用 this
            // console.log(URL);
            this.handle(URL);
        }
    }
    /*
    *上面对象添加方法的模式也可以使用这种写法
    * Table.prototype.init = function(){
    *    ....
    *    ....      
    * }
    */
    Table.prototype.handle = function(url){ 
        var that = this,
            pagename = that.pageName;
        function getData(url){
            $.when(fetch(url)).then(function(result) { /*ajax顺序平行执行(同步效果)-->获取数据*/
                var keyArr =[],
                    str = '',
                    data = result[1].content;
                    total = result[0].count;
                
                $('.total-page').text(total);
                var list = listFactory(total,current )  //分页列表  
                // console.log(data)
                $("#s-tab tbody").empty();
                for(var i=0; i<data.length;i++){
                    var valArr=[];
                    for(var j in data[i]){
                         valArr.push(data[i][j])
                    }
                    str = element(valArr[0],valArr) ;
                    $('#s-tab tbody').append(str);
                }
                elementEvent()
                tdHide()
                page(list)
            }) 
        }
        getData(url)

        function fetch(url) {
            var deferred = $.Deferred();
            $.get(url, function(data) {
                deferred.resolve(data)
            })
            return deferred.promise();
        };
        function element(id,arr) {
            that = this;
            var editStr = '<span class="glyphicon glyphicon-pencil edit-item" title="修改" ></span>',
                checkboxStr = "<td><input type='checkbox'></td>",
                delStr = '<span class="glyphicon glyphicon-remove delete-item" title="删除" /></span>';
                detailStr = '<span class="glyphicon glyphicon-eye-open detail" title="详情"></span>'
            var str = '<tr id="'+id+'" > ' + checkboxStr;
            switch(pagename){
                case 'news':
                    str = elementTd(arr)
                    str += '<td class="events">'  + detailStr + delStr + '</td>';
                    str += '</tr>';
                    break;
                case 'score':
                    str = elementTd(arr)
                    // str += '<td class="events">' + editStr + delStr + '</td>';
                    str += '</tr>';
                    break;
                case 'set':
                    str = elementTd(arr)
                    str += '<td class="events">'  + delStr + '</td>';
                    str += '</tr>';
                    break;
                default:
                    str = elementTd(arr)
                    str += '<td class="events">' + editStr + delStr + '</td>';
                    str += '</tr>';
                    break;
            }    
            function elementTd(arr){
                switch(pagename){
                    // case 'news':
                        // for (k = 0; k < arr.length; k++) {
                        //     if(k == 4){ //单独处理内容字段
                        //         if(arr[k].indexOf('<p') > -1){
                        //               var s = arr[k].replace(/</g, "&lt;");
                        //                  // s = arr[k].replace(/>/g, "&gt;");
                        //             // console.log(s)
                        //             arr[k] = s;
                        //         }
                        //     }
                        //     str += '<td >' + arr[k] + "</td>";
                        // };
                        // break;
                    default:
                        for (k = 0; k < arr.length; k++) {
                            str += '<td title='+ arr[k]+'>' + arr[k] + "</td>";
                        };
                        break;
                }
                return str;
            }
             // console.log(str)
            return str;
        }

        function elementEvent(){
            $('#s-tab tbody .events .edit-item').click(function(){ //填充修改内容

                var tr = $(this).parents('tr'),
                    id = tr.attr('id');  
                setValue(tr);
                $('#editModal').modal('show');
                $('#editModal #edit').unbind("click").click(function(){ //提交修改内容
                    var params;
                    switch(pagename){
                        case 'question':
                            params = $('#editModal .question').serializeArray();
                            var answer = $('#editModal .answer').val();
                            if(answer == -1){
                                alert("请设置正确答案");
                                return;
                            }else{
                                params.push({"name":"right_answer","value":answer},{"name":"id","value":id});
                            }
                            break;
                        case 'user':
                            params = $('#editModal form').serializeArray();
                            params.push({'name':'id','value':id})
                            break;
                    }
                    // console.log(params);return;
                    var url = that.CreateTable.getUrl('edit');
                    $.post(url,params,function(data){
                        if(data ==1){
                            alert("修改成功");
                            $('#editModal').modal('hide');
                            getData(URL)
                        }else{
                            alert("修改失败")
                        }
                    })
                })
            })
            $('#s-tab tbody .events .delete-item').click(function(){ //删除题目
                // console.log(that.CreateTable.getUrl('del'));return;
                var id = $(this).parents('tr').attr('id'),
                    url = that.CreateTable.getUrl('del');
                $.post(url,{'id':id},function(data){
                    if(data ==1){
                        alert("删除成功");
                        $('#'+id).fadeOut('fast',function(){
                            $(this).remove();
                        })
                    }else{
                        alert("删除失败")
                    }
                })
            })
            $('#s-tab tbody .events .detail').click(function(){ //查看详情
                var tr = $(this).parents('tr'), 
                    td = tr.find('td'),
                    title = td.eq(2).text(),
                    description = td.eq(3).text(),
                    news = td.eq(4).text(),
                    url = td.eq(5).text(),
                    img_name = td.eq(6).text(),
                    time = td.eq(7).text(); 
                // console.log(td)
                $('#info_title h4').text(title);
                $('#info_description .info_description').text(description)
                $('#info_time .info_time').text(time)
                url = url.replace(/,/g,"\n")
                img_name = img_name.replace(/,/g,"\n")
                $('#info_url .info_url').text(url)
                $('#info_imgName .info_imgName').text(img_name)
                $('#info_news .info_news').text(news)
                $('#content').hide();
                $('#detailModal').show();
                $('#back').click(function(){
                    $('#detailModal').hide();
                    $('#content').show();
                })
            })
        }
        function tdHide(){
            var tr = $('#s-tab tr');
            switch(CreateTable.pageName){
                case 'news':
                    for(var i=1;i<tr.length;i++){
                        var td = tr.eq(i).children('td');
                        td.eq(4).hide();
                    }
                    break;
                case 'user':
                    for(var i=1;i<tr.length;i++){
                        var td = tr.eq(i).children('td');
                        td.eq(3).hide();
                    }
                    break;
                case 'score':
                    var depCyclesUrl = CreateTable.getUrl('getDepCycles');
                    $.post(depCyclesUrl,function(result){
                        if(result.code == 1){
                            depData = result.dep;
                            cyclesData = result.cycles;
                            var str = '<option value=""></option>';
                            $('#dep,#cyclesNum,#export_dep,#export_cyclesNum').empty();
                            $('#dep,#cyclesNum,#export_dep,#export_cyclesNum').append(str);
                            for(var i in depData){
                                var str2 = '<option value='+ depData[i].id +'>' + depData[i].dep_name + '</option>';
                                $('#dep,#export_dep').append(str2)
                            }
                            for(var j in cyclesData){
                                var str3 = '<option value='+ cyclesData[j].cycles_num +'>' + cyclesData[j].cycles_num + '</option>';
                                $('#cyclesNum,#export_cyclesNum').append(str3)
                            }
                            $("#dep,#export_dep,#cyclesNum,#export_cyclesNum").select2({ tag:true,});
                            $('.select2-container').css({"width":'100%'})   
                        }
                    })
                    break;
                case 'set':
                    var depCyclesUrl = CreateTable.getUrl('getDepCycles');
                    $.post(depCyclesUrl,function(result){
                        if(result.code == 1){
                            depData = result.dep;
                            var str = '<option value=""></option>';
                            $('#test_dep').empty();
                            $('#test_dep').append(str);
                            for(var i in depData){
                                var str2 = '<option value='+ depData[i].id +'>' + depData[i].dep_name + '</option>';
                                $('#test_dep').append(str2)
                            }
                        }
                    })
                    break;
                default:
                    break;
            }
        }
        function setValue(tr){
            var td = tr.find('td');
            switch(pagename){
                case 'question':
                    var num = [2,3,4,5,6,7],
                        name = ['question','choiceA','choiceB','choiceC','choiceD','answer'],
                        valArr = [];
                    for(var i=0;i<num.length;i++){
                        valArr.push(td.eq(num[i]).text());
                    }
                    
                    // console.log(valArr)
                    for(var i=0;i<name.length;i++){
                        var el = $('#editModal form input[name='+name[i]+']');
                        var val = valArr[i];
                        el.val(val)
                        if(name[i] == 'answer'){
                            $('.answer').find('option[value=' + val +']').prop("selected",true)
                        }  
                    }
                    break;
                case 'news':

                    break;
                case 'user':
                    var num = [2,3,4,5],
                        name = ['account','password','dep','phone'],
                        valArr = [];
                    for(var i=0;i<num.length;i++){
                        valArr.push(td.eq(num[i]).text());
                    }
                    
                    // console.log(valArr)
                    for(var i=0;i<name.length;i++){
                        var el = $('#editModal form input[name='+name[i]+']');
                        var val = valArr[i];
                        el.val(val)  
                    }
                    break;

            }
        }
        function listFactory(totalPage,cur) { 
            // 分页功能，totalPage总页数,cur当前页数
            var list = [];
            var cur = cur ; //当前页
            if (totalPage >= 5) {

                if (cur > 3 && cur < totalPage - 2) {
                    list = [];
                    for (var i = cur - 2; i <= cur; i++) {
                        list.push(i);
                    }
                    for (var i = cur + 1; i <= cur + 2; i++) {
                        list.push(i);
                    }
                } else if (cur <= 3) {
                    list = [1, 2, 3, 4, 5];
                } else {
                    list = [];
                    for (var i = totalPage - 4; i <= totalPage; i++) {
                        list.push(i);
                    }
                }

            } else if (0 < totalPage && totalPage < 5) {
                list = [];
                for (var i = 1; i <= totalPage; i++) {
                    list.push(i);
                }
            } else {
                list = [1];
            }
            return list;
        }
        function page(list){
            $('.list-item').empty();
            for(var i=0,val=i+1;i<list.length;i++,val++){ //生成页数
                $('.list-item').last().after("<li class='list-item list'><a href='javascript:void(0)'>" + list[i] + "</a></li>")
            }
            chgItem(current)
            //翻页事件  
            $('#page .list a').unbind("click").click(function(){ 
                if (parseInt($(this).text()) == current) return;
                current = parseInt($(this).text());
                jumpUrl(current)
            })
            $('#pre-page').unbind("click").click(function(){ //上一页
                if (current <= 1) { return false;  }
                jumpUrl(--current)
            })
            $('#next-page').unbind("click").click(function(){ //下一页
                if (current >= parseInt($(".total-page").text())) { return false;}
                jumpUrl(++current)
            }) 
            $('.limit button').unbind("click").click(function(){ //每页条目
                var url = '';
                limitNum = $(this).text();
                $(this).removeClass('btn-default').addClass('cur').siblings().removeClass('cur').addClass('btn-default');
                current = 1;
                if(URL.indexOf('?')>0){
                    url = URL + "&page=1&limit=" + limitNum + '&search=' +searchVal;
                }else{
                    url = URL + "?page=1&limit=" + limitNum + '&search=' +searchVal;
                } 

                $('.list').remove(); 
                getData(url);
            })      
            function jumpUrl(num){
                current = parseInt(num);
                var url='';
                if(URL.indexOf('?')>0){
                    url =URL + "&page=" + num + "&limit=" + limitNum + '&search=' +searchVal ;
                }else{
                    url =URL + "?page=" + num + "&limit=" + limitNum + '&search=' +searchVal;
                } 
                $('.list').remove();  
                getData(url); 
                if (num <= parseInt($(".total-page").text())) {
                    $("#input-jmppage").val(num);
                }
            }
            function chgItem(num) { //选中样式
                var item = $(".list-item");
                for (var i = 0; i < item.length; i++) {
                    var currPage = parseInt(item.eq(i).children('a').text());
                    if (currPage == num) {
                        $(".pagination .list-item").eq(i).siblings().children("a").removeClass('cur');
                        $(".pagination .list-item").eq(i).children("a").addClass('cur');
                    }
                }
            }
        }  
    }
    Table.prototype.getUrl = function(type){
        var URL = {
            "question":{
                "show":'/index.php/Home/Admin/show_question',
                "del":'/index.php/Home/Admin/del_question',
                "edit":'/index.php/Home/Admin/edit_question',
            },
            "news":{
                "show":'/index.php/Home/Admin/show_news',
                "del":'/index.php/Home/Admin/del_news',
            },
            "user":{
                "show":'/index.php/Home/Users/user?act=show',
                "del":'/index.php/Home/Users/user?act=del',
                "edit":'/index.php/Home/Users/user?act=edit',
            },
            "score":{
                "show":'/index.php/Home/Score/show',
                "search":'/index.php/Home/Score/show?page=1&limit=10',
                'getDepCycles':'/index.php/Home/Score/getDepCycles',
            },
            "set":{
                "show":'/index.php/Home/Set/show',
                "del":'/index.php/Home/Set/del',
                "search":'/index.php/Home/Set/show?page=1&limit=10',
                'getDepCycles':'/index.php/Home/Score/getDepCycles',
            }
        }
        return URL[this.pageName][type]
    }
    //导出成绩
    $('#handleExport').click(function(){
        alert(1);
        var arr = [],
            ch = $('#s-tab tbody input[type=checkbox]');
        for(var i=0;i<ch.length;i++){
            if(ch.eq(i).prop('checked') == true){
                arr.push( ch.eq(i).parents('tr').attr('id') );
            }     
        }
        if (arr.length) {
            if(confirm("是否确认批量导出？")){ 
                location.href = "/index.php/Home/Score/export?type=1&id="+ arr.join(',');
            }else{
                $("#exportModal").modal("show");
            }
        }else{
            $("#exportModal").modal("show");
        }
    })
    $('#export').click(function(){
        var val ;
        val = $('#exportForm').serializeObject();
        val = JSON.stringify(val);
        val = utf16to8(val);
        val = base64encode(val);
        location.href = "/index.php/Home/Score/export?" + "search=" + val;
        $("#exportModal").modal("hide");
    })
    // 添加题目
    $('#add-question').click(function(){
        var params = $('#addModal .question').serializeArray();
        var answer = $('#addModal .answer').val();
        if(answer == -1){
            alert("请设置正确答案");
            return;
        }else{
            params.push({"name":"right_answer","value":answer});
        }
        $.post('/index.php/Home/Admin/add_question',params,function(data){
            if(data ==1){
                alert("添加成功");
                $("#addModal").modal('hide');
                CreateTable.handle(URL)
            }else{
                alert("添加失败")
            }
        })
    })
    //批量导入题目
    $('#import ').click(function(){
        var file = new FormData($('#importModal .import')[0]);
        $.ajax({
            url:'/index.php/Home/Admin/import',
            type:'POST',
            data:file,
            processData: false,
            contentType: false,
            success:function(result){
                // console.log(result)
                if (result.code == 0) {
                    alert(result.info);
                } else {
                    alert(result.info);
                    $('#importModal').modal('hide');
                    CreateTable.handle(URL)
                }
            }
        })
    })
    $('#add-user').click(function(){  //添加用户
        var phone = $('#addModal form input[name="phone"]').val(),
            re = /^1([3|5|8]{1})+\d{9}$/;
        if (!re.test(phone)) {
            alert("手机号码不正确!")
            return ;
        }
        var url = '/index.php/Home/Index/addUser',
            params = $('#addModal form').serializeArray();
        $.post(url,params,function(data){
            if(data.code == 1){
                alert("添加成功");
                $("#addModal").modal('hide');
                CreateTable.handle(URL)
            }else{
                alert(data.info)
            }
        })
    })
    $('#isSearh').click(function(){ //是否显示搜索
        var a = $('#searchForm').css('display');
        if(a == 'none'){
            $('#searchForm').slideDown("slow");
        }else{
            $('#searchForm').slideUp("slow");
        }
    })
    $('#search').click(function(){ //搜索功能
        $('#clearSearch').show()
        var params = $('#searchForm').serializeObject();
        params = JSON.stringify(params);
        var value = base64encode(utf16to8(params)); //base64加密
        var searchUrl = CreateTable.getUrl('search');
        searchUrl += '&search='+value; 
        // console.log(searchUrl)
        CreateTable.handle(searchUrl)
    })
    $('#clearSearch').click(function(){ //清除搜索
        $(this).hide();
        $('#searchForm').slideUp("slow");
        searchVal = '';
        CreateTable.handle(URL)
    })
    $('#isSetCycles').click(function(){ //是否显示周期设置
        $("#test_dep").select2();
        $('.select2-container').css({"width":'100%'})
        var a = $('#cyclesFrom').css('display');
        if(a == 'none'){
            $('#cyclesFrom').slideDown("slow");
        }else{
            $('#cyclesFrom').slideUp("slow");
        }
    })
    $('#setCycles').click(function(){ //周期设置
        var startTime = $('#cyclesFrom input[name="startTime"]').val(),
            endTime = $('#cyclesFrom input[name="endTime"]').val(),
            cyclesNum = $('#cyclesFrom input[name="cycles_num"]').val(),
            testDep = $('#test_dep').val();//['1','2']
        var currTime = new Date(),
            curr = new Date(currTime.toLocaleDateString()),
            st1 = new Date( startTime.replace(/-/g,"/") ),
            et2 = new Date( endTime.replace(/-/g,"/") );
        // console.log(testDep.join(','))
        if(st1=='Invalid Date' || et2=='Invalid Date'){alert("周期时间段不能为空");return;}
        if(testDep.length == 0){alert("考试部门不能为空");return;}
        if(curr >st1){  alert("开始时间不能小于当前时间"); return;}
        if(et2 < st1){  alert("结束时间不能小于开始时间");return;}
        var params = {'startTime':startTime,'endTime':endTime,'cyclesNum':cyclesNum,'testDep':testDep.join(',')}
        $.post('/wx/index.php/Home/Set/set_cycles',params,function(result){
            if(result.code == 0){
                alert(result.info);
            }else{
                alert(result.info);
                $('#cyclesFrom input[name="startTime"]').val('')
                $('#cyclesFrom input[name="endTime"]').val('')
                $('#cyclesFrom input[name="cycles_num"]').val('')
                $('#test_dep').val('')
                $('#cyclesFrom').slideUp("slow");
            }
        })
    })
    window.CreateTable = new Table();
    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (this.value) {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            }
        });
        return o;
    };
})(window)






   

