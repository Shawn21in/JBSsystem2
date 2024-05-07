$(function () {
    $(window).on('load', function () {
        let s = location.hash;
        if (location.hash != '') {
            $(s + '-tab').trigger('click');
        }
    })
    $('.dropdown-menu a').on('click', function () {
        let s = $(this).data('tag');
        if (s != '' || s !== NULL) {
            console.log(s);
            $('#' + s + '-tab').trigger('click');
        }
    })
})