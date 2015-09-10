/**
 * Created by Grallen on 15/8/10.
 */

$(document).ready(function() {
    $.get('/index.php/Home/Recruit/showFlow').done(function(data) {
        var ids = [];
        info = data['data'];
        info.forEach(function(e) {
            ids.push(e.pop());
        });
        $('#progress>div').css('width', Math.ceil($('#progress').width() / info.length) - 20 + 'px');
        initialize($(".bwizard-steps-o"),$(".tab-content"),$("#revise"), data['data']);

        function initialize(ulObj,contentObj,reviseObj,infomation){
            ulObj.children().remove();
            contentObj.children().remove();

            var type = [0,0,0,0];
            infomation.forEach(function(item){
                type[item[0]]++;
            });

            var typeCar = [0,0,0,0];
            infomation.forEach(function(item,index){
                var infoma = {};
                var name = "";
                typeCar[item[0]]++;
                switch (item[0]){
                    case 0:
                        name = "报名";
                        infoma.类型 = "报名";
                        infoma.报名截止时间 = item[1];
                        infoma.已经报名人数 = item[2];
                        infoma.部门介绍 = item[3];
                        infoma.备注 = item[4];
                        break;
                    case 1:
                        if(type[1]>=2){
                            name = "第"+numToChinese(typeCar[1])+"轮笔试";
                        }else{
                            name = "笔试";
                        }
                        infoma.类型 = "笔试";
                        infoma.时间 = item[1];
                        infoma.地点 = item[2];
                        infoma.备注 = item[3];
                        break;
                    case 2:
                        if(type[2]>=2){
                            name = "第"+numToChinese(typeCar[2])+"轮面试";
                        }else{
                            name = "面试";
                        }
                        infoma.类型 = "面试";
                        infoma.时间 = item[1];
                        infoma.地点 = item[2];
                        infoma.备注 = item[3];
                        break;
                    case 3:
                        name = "录取";
                        infoma.类型 = "录取";
                        infoma.预计招生人数 = item[1];
                        infoma.备注 = item[2];
                        break;
                }
                creatLi(ulObj,contentObj,reviseObj,index,item[0],infoma,name);
            });
            $(ulObj.find("li")[0]).addClass("active");
            $(contentObj.find(".tab-pane")[0]).addClass("active");
            ulObj.find("li").css("margin-left",80/(infomation.length-1)+"%");
        }

        function creatLi(ulObj,contentObj,reviseObj,index,type,infomation,name) {
            var li = $("<li>",{
                "index":index
            }).appendTo(ulObj);
            var a = $("<a>",{
                "href":"#tab"+index+"-wizard-custom-circle",
                "data-toggle":"tab"
            }).appendTo(li);
            var i = $("<i>",{
            }).appendTo(a);
            var p = $("<p>",{
                "class":"anchor",
                "text":name
            }).appendTo(a);
            switch (type) {
                case 0:
                    i.addClass("fa fa-user");
                    break;
                case 1:
                    i.addClass("fa fa-pencil");
                    break;
                case 2:
                    i.addClass("fa fa-microphone");
                    break;
                case 3:
                    i.addClass("glyphicon glyphicon-check");
                    break;
            }
            //infomation.foreach(function(item,index){
            //    console.log(item);
            //});
            var content = $("<div>",{
                "id":"tab"+index+"-wizard-custom-circle",
                "class":"tab-pane"
            }).appendTo(contentObj);
            var row = $("<div>",{
                "class":"row"
            }).appendTo(content);
            var col_12 = $("<div>",{
                "class":"col-lg-12"
            }).appendTo(row);
            $("<h3>",{
                "class":"mbxl",
                "id": ids.shift(),
                "text":name+"流程详情"
            }).appendTo(col_12);
            var form = $("<div>",{
                "class":"form-horizontal"
            }).appendTo(col_12);
            for(var key in infomation){
                var form_group = $("<div>",{
                    "class":"form-group"
                }).appendTo(form);
                $("<p>",{
                    "class":"col-sm-3 control-label",
                    "text":key+":"
                }).appendTo(form_group);
                $("<p>",{
                    "class":"col-sm-9 control-label",
                    "text":infomation[key],
                    "style":"text-align: left;"
                }).appendTo(form_group);
            }
            var del = $("<button>",{
                "type":"button",
                "class":"btn btn-primary",
                "index":index,
                "style":"float: right;margin-left: 10px;",
                "text":"删除"
            }).appendTo(col_12);
            del.on('click', function() {
                var id = $(this).siblings('.mbxl');
                var type = $(this).siblings('.form-horizontal').find('.form-group>.col-sm-9');
                $.post('/index.php/Home/Recruit/deleteFlow', {id: id.attr('id'), type: type[0].textContent}).then(function() {
                    var error = data['error'];
                    if(error != undefined) alert(data['error']);
                    else parent.location.reload();
                });
            })
            var modify = $("<button>",{
                "type":"button",
                "class":"btn btn-default",
                "index":index,
                "data-toggle":"modal",
                "data-target":"#modal-revise",
                "style":"float: right;margin-left: 10px;",
                "text":"修改"
            }).appendTo(col_12);
            modify.on("click",function(){
                var id = $(this).siblings('.mbxl');
                reviseObj.children().remove();
                $("#modal-revise .modal-title").text(name+"流程修改");
                for(var key in infomation){
                    var form_group = $("<div>",{
                        "class":"form-group"
                    }).appendTo(reviseObj);
                    $("<label>",{
                        "class":"col-sm-3 control-label",
                        "text":key
                    }).appendTo(form_group);
                    var col_9 = $("<div>",{
                        "class":"col-md-9"
                    }).appendTo(form_group);
                    var input = $("<div>",{
                        "class":"input-icon right"
                    }).appendTo(col_9);
                    $("<input>",{
                        "type":"text",
                        "class":"form-control",
                        "value":infomation[key]
                    }).appendTo(input);
                }
                var input_icon = reviseObj.find(".input-icon.right");
                $(input_icon[0]).html("<select class='form-control'><option>"+infomation["类型"]+"</option></select>")
                $(input_icon[input_icon.length-1]).html("<textarea class='form-control' placeholder='请输入该审核流程的备注' rows='4'></textarea>")
                //var retu = [];
                //for(var len = $("#set input").length- 1,i = 0; i<=len;i++){
                //    retu.push($($("#set input")[i]).val());
                //}
                input_icon.find("textarea").val(infomation["备注"]);
                var submit = $(reviseObj).closest('.modal-body').next().children('.btn');
                var translate = {
                    '类型': 'type', '报名截止时间': 'deadline', '已经报名人数': 'hand_num',
                    '部门介绍': 'content', '备注': 'remark', '时间': 'time',
                    '地点': 'location', '预计招生人数': 'pretotal'
                    };
                //noinspection JSValidateTypes
                var form_ch = $('#revise').find('.form-control');
                var label_ch = $('#revise').find('label');
                var data = {}, label = [], ddata = {};
                $(submit[1]).off('click').on('click', function() {
                    label_ch.each(function() {label.push($(this)[0].textContent);})
                    form_ch.each(function(e) {data[label[e]] = $(this).val();})
                    if(data['类型'] && data['类型'] == '录取') translate['备注'] = 'admit_remark';
                    for(var key in data) {
                        ddata[translate[key]] = data[key];
                    }
                    ddata['id'] = id.attr('id');
                    if(ddata) {
                        console.log(ddata['type']);
                        $.post('/index.php/Home/Recruit/modifyFlow', ddata).done(function(e) {
                            if(e['error']) alert(e['error']);
                            else {}
                                parent.location.reload();
                        });
                    }
                });
            });

        }

        function numToChinese(num){
            switch (num){
                case 1:
                    return "一";
                case 2:
                    return "二";
                case 3:
                    return "三";
                case 4:
                    return "四";
                case 5:
                    return "五";
                case 6:
                    return "六";
            }
        }

    });
});

//function (){}









