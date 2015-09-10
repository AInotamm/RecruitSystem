//$.extend($.fn.dataTableExt.oStdClasses, {
//    "sSortAsc": "header headerSortDown",
//    "sSortDesc": "header headerSortUp",
//    "sSortable": "header",
//    "sSortableNone": "header"
//});
$(document).ready(function() {
    $('#table_id').dataTable( {
        "bPaginate": false, //翻页功能
        "bLengthChange": false, //改变每页显示数据数量
        "bFilter": false, //过滤功能
        "bSort": true, //排序功能
        "bInfo": false, //页脚信息
        "bAutoWidth": true, //自动宽度
        "oLanguage" : { //主要用于设置各种提示文本
            "sProcessing" : "正在处理...", //设置进度条显示文本
            "sLengthMenu" : "每页_MENU_行",//显示每页多少条记录
            "sEmptyTable" : "没有找到记录",//没有记录时显示的文本
            "sZeroRecords" : "没有找到记录",//没有记录时显示的文本
            "sInfo" : "总记录数_TOTAL_当前显示_START_至_END_",
            "sInfoEmpty" : "",//没记录时,关于记录数的显示文本
            "sSearch" : "搜索:",//搜索框前的文本设置
            "oPaginate" : {
                "sFirst" : "首页",
                "sLast" : "未页",
                "sNext" : "下页",
                "sPrevious" : "上页"
            }
        },
        "aoColumnDefs": [{"bSortable": false, "aTargets": [1, 2, 3, 4, 5, 7]}],
    });
});