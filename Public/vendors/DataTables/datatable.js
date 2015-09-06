$(document).ready(function() {
    $('#table_id').dataTable( {
        "bPaginate": false, //翻页功能
        "bLengthChange": false, //改变每页显示数据数量
        "bFilter": false, //过滤功能
        "bSort": true, //排序功能
        "bInfo": false, //页脚信息
        "bAutoWidth": true, //自动宽度
        "aoColumnDefs": [{"bSortable": false, "aTargets": [1, 2, 3, 4, 5, 7]}]
    });
});