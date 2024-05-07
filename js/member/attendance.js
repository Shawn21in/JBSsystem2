$(function () {
    $("#copyBtn").on('click', function () {
        $('.datalist tr').each(function (key, value) {
            if (key == 0) return;
            $(value).find('input[name^=ontime]').val($('.datalist tr').eq(0).find('input[name^=ontime]').val())
            $(value).find('input[name^=latetime]').val($('.datalist tr').eq(0).find('input[name^=latetime]').val())
            $(value).find('input[name^=resttime1]').val($('.datalist tr').eq(0).find('input[name^=resttime1]').val())
            $(value).find('input[name^=resttime2]').val($('.datalist tr').eq(0).find('input[name^=resttime2]').val())
            $(value).find('input[name^=resttime3]').val($('.datalist tr').eq(0).find('input[name^=resttime3]').val())
            $(value).find('input[name^=resttime4]').val($('.datalist tr').eq(0).find('input[name^=resttime4]').val())
            $(value).find('input[name^=offtime]').val($('.datalist tr').eq(0).find('input[name^=offtime]').val())
            $(value).find('input[name^=addontime]').val($('.datalist tr').eq(0).find('input[name^=addontime]').val())
            $(value).find('input[name^=addofftime]').val($('.datalist tr').eq(0).find('input[name^=addofftime]').val())
            $(value).find('input[name^=mealtime]').val($('.datalist tr').eq(0).find('input[name^=mealtime]').val())
            $(value).find('input[name^=worktime]').val($('.datalist tr').eq(0).find('input[name^=worktime]').val())
            $(value).find('select[name^=daytype]').val($('.datalist tr').eq(0).find('select[name^=daytype]').val())
        })
    })
    $(".table").draggable({
        axis: "x",
        drag: function (event, ui) {
            $('.table-scroll').css('cursor', 'grabbing');
            var element = $(".table-scroll")[0];
            // console.log(element.scrollWidth)
            // console.log(element.scrollLeft)
            // console.log(element.clientWidth)
            // 判斷是否已經滑到最右側
            if (element.scrollWidth - element.scrollLeft < element.clientWidth + 1) {
                $(".table-scroll").scrollLeft(element.scrollLeft - 20);
                $(".table").css('left', '0px');
                return false;
            }
            // 判斷是否已經滑到最左側
            if (element.scrollLeft === 0) {
                $(".table-scroll").scrollLeft(1);
                $(".table").css('left', '0px');
                return false;
            }
            // 根據拖動的距離計算滾動的偏移量
            var scrollOffset = ui.position.left - ui.originalPosition.left;
            // 在表格滾動區域上應用滾動偏移量
            $(".table-scroll").scrollLeft($(".table-scroll").scrollLeft() - scrollOffset);
        },
        stop: function (event, ui) {
            $('.table-scroll').css('cursor', 'grab');
        }
    });
})