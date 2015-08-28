//新生信息
//
//顺序id 面试编号 学号 姓名 性别 手机号 学院 是否已经报名其他部门 状态 自我简介
//
//状态
//
//1 已录取，用户已确认
//2 录取中，用户已超过一天未确认
//3 录取中，待用户确认
//4 已通过
//5 待审核
//6 未通过
//7 用户已取消
//8 用户已被其他校级组织录取

//排序 搜索

var a = ["赵", "钱", "孙", "李", "周", "吴", "郑", "王", "冯", "陈", "楮", "卫", "蒋", "沈", "韩", "杨", "朱", "秦", "尤", "许", "何", "吕", "施", "张", "孔", "曹", "严", "华", "金", "魏", "陶", "姜", "戚", "谢", "邹", "喻", "柏", "水", "窦", "章", "云", "苏", "潘", "葛", "奚", "范", "彭", "郎", "鲁", "韦", "昌", "马", "苗", "凤", "花", "方", "俞", "任", "袁", "柳", "酆", "鲍", "史", "唐", "费", "廉", "岑", "薛", "雷", "贺", "倪", "汤", "滕", "殷", "罗", "毕", "郝", "邬", "安", "常", "乐", "于", "时", "傅", "皮", "卞", "齐", "康", "伍", "余", "元", "卜", "顾", "孟", "平", "黄"];
var b = "丰源 为民 祥熙 言卿 卿舒 柳贵 枝盛 莱彬 世吉 永祺 奕一 培致 修为 伟珍 山东 国邑 春蕴 珂菲 广博 文书 春刚 宏阳 宾盟 锡明 天印 琰沣 恒铭 天晴 钧涵 佳宇 梓言 志勇 家乐 珧冬 若木 启波 久宣 城瑞 异才 传舫 奕灿 依培 嘉欣 艳秋 传祥 传禄 志强 焕兴 志嘉 依衡 传荣 映廷 成宏 欣鑫 如钰 天宇 一帆 钦泽 鑫源 又铭 恒伟 鹭洋 勇清 霈泽 珂佳 遥来 乘昊 永靖 小军 永静 汉元 依潼 依凡 欣瑜 逸樊 圣祥 楠楠 传方 欣尉 函珏 德友 英智 双庆 子辰 子芯 蒙蒙 玮檬 牧归 佳龙 靖心 炜航 玉平 江华 兴友 嘉锦 东峰 传贵 逸矾 传福 海语 矾逸 文吉 知娴 任杰 豫坤 留生 策驰 振中 林峰 浩云 远葸 清瑜 墨晗 龙飞 宗良 维顺 瀚辰 改珍 思敏 长春 孟钰 桐瑞 星月 中源 季恒 书帛 炀霏 龙斌 卉霖 玮宏 永明 宇浩 少春 续豪 雁君 誉玮 培杰 壁霰 一玮 一薄 添睿 海轩 嘉辉 嘉欣 健烨 周华 泰国 月康 建雄 京津 军生 致齐 书显 建新 金利 力千 轴毛 钊幸 珏珲 先涛 泞鹪 庆昌 顺香 梦暄 欣逸 晓坤 广应 其运 淇兴 镜桦 凯岳 敬海 振宇 锦容 锡海 钧玮 庆敏 文龙 逢厅 祚玮 全民 兴碧 乙默 金元 书含 利仁 宝涛 佳明 筱璇 思帅 政翰 宇博 文会 稼傲 佳烈 稼音 稼金 稼辛 稼竹 富豪 林丰 梁宏 临峰 临锋 传澎 矾豫 晓东 临风 传卿 若然 传鑫 凌风 大为 力仁 允灏 品言".split(" ");
var c = ["男","女"];
var d = ["软件学院","计算机学院","通信学院","传媒学院","自动化学院","外国语学院","经管学院"];
var info = [];

for(var i = 0 ; i <43 ; i++){
    var INFO = [i,parseInt(10000*Math.random()),20142100000+parseInt(40000*Math.random()),a[parseInt(96*Math.random())]+b[parseInt(216*Math.random())],c[parseInt(1.5*Math.random())],10000000000+parseInt(10000000000*Math.random()),d[parseInt(7*Math.random())],parseInt(1.5*Math.random()),parseInt(8*Math.random())+1,""];
    info[i] = [];
    info[i] = INFO;
}
console.log(info);

var $Obj = $(".info-table-tboby");





var searchArr = info.slice(0);
var $pointer = $(".pointer");
var $more = $(".more");
var $form = $(".form-body");
var $refuse = $(".refuse");
var $pass = $(".pass");
var $anroll = $(".anroll");
var $canel = $(".canel");
var $anrolled = $(".anrolled");
var $passed = $(".passed");
var $invalid = $(".invalid");
var infoLen = info.length-1;
var indexNum = 0;
var indexSortNum = 0;
var num = [[1,2,5],[3,6]];
var copyFlag = false;


$pointer.on("click",function(){
    var $this = $(this);
    var sortNum = $this.data("sortnum");
    var arrangement = $this.attr("arrangement");
    quickSort(searchArr,sortNum,0,infoLen);
    $Obj.empty();
    indexNum = 0;
    $pointer.attr("arrangement","0");
    indexSortNum = arrangement;
    $this.attr("arrangement",(+indexSortNum+1)%2);
    creatStus();
});

$($pointer[2]).click();

$more.on("click",function(){
    indexNum = indexNum+20;
    creatStus();
});

$("#search").keyup(function(){
    if($("#search").val()){
        search($("#search").val());
    }else{
        searchArr = info.slice(0);
        infoLen = info.length-1
    }
    $Obj.empty();
    indexNum = 0;
    creatStus();
});

function search(searchText){
    searchArr = [];
    info.forEach(function (arr){
        var temp = arr.slice(0);
        copyFlag = false;
        if(!isNaN(parseInt(searchText))) {
            for(var i = 0; i < 3; i++) {
                var str = temp[num[0][i]].toString();
                var pattern = new RegExp(searchText);
                var match = str.match(pattern);
                if(match !== null) {
                    replaceArrayElement(parseInt(match.input), searchText, pattern, temp);
                }
            }
        } else if(/[\u4e00-\u9fa5]$/g.exec(searchText.toString())) {
            for(var i = 0; i < 2; i++) {
                var str = temp[num[1][i]].toString();
                var pattern = new RegExp(escape(searchText).toLocaleLowerCase().replace(/%u/gi, '\\u'));
                var match = str.match(pattern);
                if(match != null) {
                    replaceArrayElement(match.input, searchText, pattern, temp);
                }
            }
        }
    });
    infoLen = searchArr.length-1;
}

function replaceArrayElement(input, searchText, pattern, temp) {
    if(temp.constructor == Array) {
        var pos = temp.lastIndexOf(input);
        temp[pos] = input.toString().replace(pattern, "<span class='yellow'>" + searchText + "</span>");
        if(!copyFlag) {
            searchArr.push(temp);
        } else {
            return ;
        }
        copyFlag = true;
    }
}

function partition(array,num,first,end){
    var i = first;
    var j = end;
    var mid = [];
    while(i<j){
        while (i<j && array[i][num]<=array[j][num]) j--;
        if(i<j){
            mid = array[i];
            array[i] = array[j];
            array[j] = mid;
            i++;
        }
        while (i<j && array[i][num]<=array[j][num]) i++;
        if(i<j){
            mid = array[i];
            array[i] = array[j];
            array[j] = mid;
            j--;
        }
    }
    return i;
}

function quickSort(array,num,first,end){
    if(first<end){
        var pivot = partition(array,num,first,end);
        quickSort(array,num,first,pivot-1);
        quickSort(array,num,pivot+1,end);
    }
}

function creatStus() {
    $("#info").html("点击显示更多");
    if(indexSortNum == 0){
        for(var i = 0; i < 20; i++){
            if(i+indexNum>infoLen){
                $("#info").html("没有更多了");
            }else {
                creatStu($Obj, searchArr[i + indexNum]);
            }
        }
    }else{
        for(var i = 0; i < 20; i++){
            if(i+indexNum>infoLen){
                $("#info").html("没有更多了");
            }else {
                creatStu($Obj, searchArr[infoLen - (i + indexNum)]);
            }
        }
    }
}

function creatStu(obj,info){

    var tr = $("<tr>",{
        "data-id" : info[0],
        "type":"button",
        "data-target":"#information",
        "data-toggle":"modal"
    }).appendTo(obj);

    tr.on("click",function(){
        var id = $(this).data("id");
        $form.find(".name .col-md-8").html(info[3]);
        $form.find(".sex .col-md-8").text(info[4]);
        $form.find(".num .col-md-8").html(info[1]);
        $form.find(".ID .col-md-8").html(info[2]);
        $form.find(".academy .col-md-8").html(info[6]);
        $form.find(".phoneNum .col-md-8").html(info[5]);
        $form.find(".introduction .col-md-8").text(info[9]);
        var infomation = "";
        switch (info[8]){
            case 1:
                infomation = "已录取，用户已确认";
                $refuse.css("display","none");
                $pass.css("display","none");
                $anroll.css("display","none");
                $canel.css("display","none");
                $anrolled.css("display","inline-block");
                $passed.css("display","none");
                $invalid.css("display","none");
                break;
            case 2:
                infomation = "录取中，用户已超过一天未确认";
                $refuse.css("display","none");
                $pass.css("display","none");
                $anroll.css("display","none");
                $canel.css("display","inline-block");
                $anrolled.css("display","none");
                $passed.css("display","none");
                $invalid.css("display","none");
                break;
            case 3:
                infomation = "录取中，待用户确认";
                $refuse.css("display","none");
                $pass.css("display","none");
                $anroll.css("display","none");
                $canel.css("display","inline-block");
                $anrolled.css("display","none");
                $passed.css("display","none");
                $invalid.css("display","none");
                break;
            case 4:
                infomation = "已通过";
                $refuse.css("display","none");
                $pass.css("display","none");
                $anroll.css("display","none");
                $canel.css("display","none");
                $anrolled.css("display","none");
                $passed.css("display","inline-block");
                $invalid.css("display","none");
                break;
            case 5:
                infomation = "待审核";
                $refuse.css("display","inline-block");
                $pass.css("display","inline-block");
                $anroll.css("display","inline-block");
                $canel.css("display","none");
                $anrolled.css("display","none");
                $passed.css("display","none");
                $invalid.css("display","none");
                break;
            case 6:
                infomation = "未通过";
                $refuse.css("display","none");
                $pass.css("display","none");
                $anroll.css("display","none");
                $canel.css("display","none");
                $anrolled.css("display","none");
                $passed.css("display","none");
                $invalid.css("display","inline-block");
                break;
            case 7:
                infomation = "用户已自行取消";
                $refuse.css("display","none");
                $pass.css("display","none");
                $anroll.css("display","none");
                $canel.css("display","none");
                $anrolled.css("display","none");
                $passed.css("display","none");
                $invalid.css("display","inline-block");
                break;
            case 8:
                infomation = "用户已被其他校级组织录取";
                $refuse.css("display","none");
                $pass.css("display","none");
                $anroll.css("display","none");
                $canel.css("display","none");
                $anrolled.css("display","none");
                $passed.css("display","none");
                $invalid.css("display","inline-block");
                break;
        }
        $form.find(".condition .col-md-8").text(infomation);
        switch (info[7]){
            case 0:
                infomation = "该生暂未报名其他的校级组织部门";
                break;
            case 1:
                infomation = "该生已经报名其他的校级组织部门";
                break;
        }
        $form.find(".bool .col-md-8").text(infomation);
    });

    $("<td>",{
        "html" : info[1]
    }).appendTo(tr);

    $("<td>",{
        "html" : info[2]
    }).appendTo(tr);

    $("<td>",{
        "html" : info[3]
    }).appendTo(tr);

    $("<td>",{
        "text" : info[4]
    }).appendTo(tr);

    $("<td>",{
        "html" : info[6]
    }).appendTo(tr);

    $("<td>",{
        "html" : info[5]
    }).appendTo(tr);

    var condition = "";
    switch (info[8]){
        case 1:
            condition = "已录取，用户已确认";
            break;
        case 2:
            condition = "录取中，用户已超过一天未确认";
            break;
        case 3:
            condition = "录取中，待用户确认";
            break;
        case 4:
            condition = "已通过";
            break;
        case 5:
            condition = "待审核";
            break;
        case 6:
            condition = "未通过";
            break;
        case 7:
            condition = "用户已自行取消";
            break;
        case 8:
            condition = "用户已被其他校级组织录取";
            break;
    }
    $("<td>",{
        "text" : condition
    }).appendTo(tr);

}


