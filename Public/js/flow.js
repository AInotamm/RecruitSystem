/**
 * Created by Haku on 15/9/9.
 */
$(function() {
    $('.bwizard-steps-o').die().live('click', function() {
        //noinspection JSValidateTypes
        var ch = $(this).children();
        var num = ch.length, flag = false;
        var width = Math.ceil($('#progress').width() / num);
        ch.each(function(e) {
            if(!flag) {
                $(this).css({
                    'border': '3px solid #e74c3c'
                });
                $(this).find('i').removeAttr('style').css('color', '#e74c3c');
            } else {
                $(this).css({'border': '3px solid #6ec06e'});
                $(this).find('i').removeAttr('style');
            }
            if($(this).attr('class') == 'active') {
                $('#progress>div').css('width', (width - 20) * (e + 1) + 'px');
                flag = true;
            }
        })
    });
    $('#setFlow').on('click', function() {
        var form = $('#add').find('.form-group');
        var type = ['type', 'time', 'location', 'remark'];
        var val = {};
        form.each(function(e) {
            var ch = $(this).find('#' + type[e]);
            val[type[e]] = ch.val();
        });
        $.post('/index.php/Home/Recruit/setFlow', val).done(function(data, status) {
            if(data) alert(data['info']);
            else parent.location.reload();
        });
    })
});