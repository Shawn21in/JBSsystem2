
$(function () {
    //搜尋判斷現有表格內資料是否有符合的
    $('#search_box').on('keyup', function () {
        $('.datalist tr').removeClass('show');
        $('.datalist').find('td').each(function (key, value) {
            let is_exist = $(value).text().indexOf($('#search_box').val());
            if ($(value).closest('tr').hasClass('show')) {
                return;
            }
            if (is_exist == -1) {
                $(value).closest('tr').css('display', 'none');
            } else {
                $(value).closest('tr').css('display', '');
                $(value).closest('tr').addClass('show');
            }
        })
    })
})
