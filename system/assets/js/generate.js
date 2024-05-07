$(function(){
    $('input[name=sdate]').on('change',function(){
        $('input[name=edate]').attr('min',$(this).val());
    })
    $('input[name=edate]').on('change',function(){
        $('input[name=sdate]').attr('max',$(this).val());
    })
})
function generate(arr){
    $.ajax({
        type: 'POST',
        url: 'php/generate/generateList.post.php',
        data:  arr,
        error: function (jqXHR, textStatus, errorThrown) {
            alert('請重新試一次！')
        },
        success: function (data) {
           alert(data);
        }
    });
}