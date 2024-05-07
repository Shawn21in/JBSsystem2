$(function () {
    //左邊按鈕列偵測當前該提示的按鈕
    $(window).on('load', function () {
        let li = $('#sidebar-menu li').not('.has-sub');
        let current_li;
        li.each(function (key, value) {
            if ($(value).data('no') == no) {
                current_li = $(value);
                return false; // 等於 break
            }
        })
        current_li.addClass('active');
        current_li.parents('ul').not('#sidebar-menu').addClass('show');
        current_li.parents('li').addClass('active expand');
    })
})