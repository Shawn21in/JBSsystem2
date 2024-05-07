$(function () {

    // ----------------------基本資料設定----------------------

    $('select[name=partno]').on('change', function () {
        let n = $(this).find('option:selected').data('type');
        // console.log(n)
        $('input[name=partname]').val(n);
    })
    $('select[name=appno]').on('change', function () {
        let n = $(this).find('option:selected').data('type');
        // console.log(n)
        $('input[name=appname]').val(n);
    })
    $('select[name=presenttype]').on('change', function () {
        let n = $(this).find('option:selected').data('type');
        // console.log(n)
        $('input[name=presentname]').val(n);
    })
    $(".datepicker-tw").datepicker({
        language: 'zh-TW',
        maxViewModel: 1,
        autoclose: true,
        clearBtn: true,
    })

    // ----------------------薪資設定----------------------

    $('select[name=bankno]').on('change', function () {
        let n = $(this).find('option:selected').data('type');
        // console.log(n)
        $('input[name=bankname]').val(n);
    })
    $('select[name=bankno2]').on('change', function () {
        let n = $(this).find('option:selected').data('type');
        // console.log(n)
        $('input[name=bankname2]').val(n);
    })
    $('input[name=sandtype]').on('change', function () {
        let st = $(this).val();
        switch (st) {
            case '1':
                $('#monthmny').prop('readonly', false)
                $('#daymny').prop('readonly', false)
                break;
            case '2':
                $('#monthmny').prop('readonly', true)
                $('#daymny').prop('readonly', false)
                $('#monthmny').val(0)
                break;
            case '3':
                $('#monthmny').prop('readonly', true)
                $('#daymny').prop('readonly', true)
                $('#monthmny').val(0)
                $('#daymny').val(0)
                break;
        }
    })
    $('#monthmny,#daymny,#hourmny,#standardday,#standardhour').focusout(function () {
        let sday = $('#standardday').val();
        let shour = $('#standardhour').val();
        let st = $('input[name=sandtype]:checked').val();
        if (sday != '') {
            if (st == '1') {
                $('#daymny').val(Math.round(($('#monthmny').val() / sday) * 100) / 100)
            }
            if (shour != '' && (st == '1' || st == '2')) {
                $('#hourmny').val(Math.round(($('#daymny').val() / shour) * 100) / 100)
            }
        }
    })

    // ----------------------加班設定----------------------

    $('input[name=overtime]').on('change', function () {
        let k = $(this).val();
        // console.log(t)
        if (k == '1') {
            $('input[name=jiabanbudashi]').prop('readonly', false);
        } else {
            $('input[name=jiabanbudashi]').prop('readonly', true);
        }
    })
    $('input[name=otway]').on('change', function () {
        let t = $(this).val();
        let k = $('input[name=jiabanbudadan]').prop('checked');
        let j = $('input[name=overtime]:checked').val();
        if (t == '2') {
            $('input[name=overtime]').prop('disabled', false);
            $('input[name=jiabanbudadan]').prop('disabled', true);
            $('.otdiv').css('opacity', '0.5');
            if (j == '1') {
                $('input[name=jiabanbudashi]').prop('readonly', false);
            }
        } else {
            $('input[name=jiabanbudadan]').prop('disabled', false);
            $('.otdiv').css('opacity', '');
            if (k) {
                $('input[name=overtime]').prop('disabled', false);
            } else {
                $('input[name=jiabanbudashi]').prop('readonly', true);
                $('input[name=overtime]').prop('disabled', true);
            }
        }
    })
    $('input[name=jiabanbudadan]').on('click', function () {
        let k = $(this).prop('checked');
        let j = $('input[name=overtime]:checked').val();
        if (k) {
            $('input[name=overtime]').prop('disabled', false);
            if (j == '1') {
                $('input[name=jiabanbudashi]').prop('readonly', false);
            }
        } else {
            $('input[name=jiabanbudashi]').prop('readonly', true);
            $('input[name=overtime]').prop('disabled', true);
        }
    })
    $('input[name=overtimemnytype]').on('change', function () {
        let o = $(this).val();
        switch (o) {
            case '1':
                $('.mny').prop('readonly', false);
                $('.rate').prop('readonly', true);
                $('.rate').val('0');
                break;
            case '2':
                $('.rate').prop('readonly', false);
                $('.mny').prop('readonly', true);
                $('.mny').val('0');
                break;
            case '3':
                $('.rate').prop('readonly', false);
                $('.mny').prop('readonly', true);
                $('.mny').val('0');
                break;
        }
    })

    // ----------------------勞健保設定----------------------

    var status = $('input[name=insuredperson]:checked').val();//投保人身分判斷
    $('input[name=insuredperson]').on('change', function () {
        status = $(this).val();
        $('#seclabno').trigger('change');
    })
    $('#seclabno').on('change', function () {
        let mny = $(this).find('option:selected').data('type');
        let smny = $(this).find('option:selected').data('self' + status);
        let amny = $(this).find('option:selected').data('afford');
        // console.log('self' + status)
        $('#lmoney').val(mny);
        $('#selflmoney').val(smny);
        $('#employerlmny').val(amny)
    })
    $('#seclabtno').on('change', function () {
        let mny = $(this).find('option:selected').data('type');
        let srate = $('#selftrate').val();
        $('#tmoney').val(mny);
        if (srate != '') {
            $('#selftmoney').val(Math.round(mny * srate / 100));
        }
    })
    $('#selftrate').focusout(function () {
        let srate = $(this).val();
        let mny = $('#seclabtno').find('option:selected').data('type');
        if (mny != '') {
            $('#selftmoney').val(Math.round(mny * srate / 100));
        }
    })
    $('#purchaserno').on('change', function () {
        let mny = $(this).find('option:selected').data('type');
        let smny = $(this).find('option:selected').data('self');
        let amny = $(this).find('option:selected').data('afford');
        $('#hmoney').val(mny);
        $('#selfhmoney').val(smny);
        $('#employerhmny').val(amny)
    })
    $('#insuredsum').focusout(function () {
        let psum = $(this).val();
        $('#insuredmny').val(Math.round($('#selfhmoney').val() * psum));
    })
    $('#tuixiuselfmny').focusout(function () {
        let tmny = $(this).val();
        $('#tuixiugerenmny').val(Math.round(tmny * 6 / 100));
    })

    // ----------------------固定加扣款----------------------

    $('#addBtn').on('click', function () {
        let count = $('.datalist tr').length;
        let html = $('.invisible_table tbody').html();
        html = html.replace('dotype[]', 'dotype[' + count + ']');
        $('#ededuction .datalist').append(html)
    })
    $(document).on('click', '.data_del', function () {
        $(this).closest('tr').remove();
    })
    $(document).on('change', 'select[name^=deductionno]', function () {
        let dname = $(this).find('option:selected').data('name');
        let dmny = $(this).find('option:selected').data('mny');
        $(this).closest('tr').find('input[name^=deductionname]').val(dname)
        $(this).closest('tr').find('input[name^=deductionmny]').val(dmny)
    })
})