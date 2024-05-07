$(function () {
    $('#enabled').on('click', function () {
        let flag = $(this).data('flag');
        if (flag == 1) {
            $(this).data('flag', '0');
            $('.editnow').hide();
            $('.edit_datalist').hide();
            $('.datalist').show();
            $(this).html('<button type="button" class="mb-1 btn btn-primary"><span class="mdi mdi-pencil"></span> 編輯模式</button>')
        } else {
            $(this).html('<button type="button" class="mb-1 btn btn-danger"><span class="mdi mdi-pencil"></span> 取消編輯</button>')
            $(this).data('flag', '1');
            $('.editnow').show();
            $('.edit_datalist').show();
            $('.datalist').hide();
        }
    })
    $('#addBtn').on('click', function () {
        let appdata = $('.invisible_table>tbody').html();
        $('.edit_datalist').append(appdata);
        $('.no').each(function (key, value) {
            $(value).text(key + 1);
        })
    })
    $(document).on('click', '.data_del', function () {
        $(this).closest('tr').remove();
        $('.no').each(function (key, value) {
            $(value).text(key + 1);
        })
    })
})