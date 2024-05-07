$(function () {
    var data_arr = new Array();
    $(window).on('load', function () {
        $('#yearsum').val($('#yeare').val() - $('#years').val() + 1)
        $('#monthsum').val($('#monthe').val() - $('#months').val() + 1)
        $('#daysum').val($('#daye').val() - $('#days').val() + 1)
        $('#hoursum').val($('#houre').val() - $('#hours').val() + 1)
        $('#minutesum').val($('#minutee').val() - $('#minutes').val() + 1)
        $('#employeesum').val($('#employeee').val() - $('#employees').val() + 1)
        $('#discernsum').val($('#discerne').val() - $('#discerns').val() + 1)
    })
    $('#years,#yeare').on('change', function () {
        let s = parseInt($('#years').val())
        let e = parseInt($('#yeare').val())
        if (s > e && s != '' && e != '') {
            swal.fire({
                title: "起始數字不可高於結尾數字",
                icon: "error"
            })
            $(this).val('')
            $('#yearsum').val('')
        } else if (s != '' && e != '') {
            $('#yearsum').val(e - s + 1)
        } else {
            $('#yearsum').val('')
        }
    })
    $('#months,#monthe').on('change', function () {
        let s = parseInt($('#months').val())
        let e = parseInt($('#monthe').val())
        if (s > e && s != '' && e != '') {
            swal.fire({
                title: "起始數字不可高於結尾數字",
                icon: "error"
            })
            $(this).val('')
            $('#monthsum').val('')
        } else if (s != '' && e != '') {
            $('#monthsum').val(e - s + 1)
        } else {
            $('#monthsum').val('')
        }
    })
    $('#days,#daye').on('change', function () {
        let s = parseInt($('#days').val())
        let e = parseInt($('#daye').val())
        if (s > e && s != '' && e != '') {
            swal.fire({
                title: "起始數字不可高於結尾數字",
                icon: "error"
            })
            $(this).val('')
            $('#daysum').val('')
        } else if (s != '' && e != '') {
            $('#daysum').val(e - s + 1)
        } else {
            $('#daysum').val('')
        }
    })
    $('#hours,#houre').on('change', function () {
        let s = parseInt($('#hours').val())
        let e = parseInt($('#houre').val())
        if (s > e && s != '' && e != '') {
            swal.fire({
                title: "起始數字不可高於結尾數字",
                icon: "error"
            })
            $(this).val('')
            $('#hoursum').val('')
        } else if (s != '' && e != '') {
            $('#hoursum').val(e - s + 1)
        } else {
            $('#hoursum').val('')
        }
    })
    $('#minutes,#minutee').on('change', function () {
        let s = parseInt($('#minutes').val())
        let e = parseInt($('#minutee').val())
        if (s > e && s != '' && e != '') {
            swal.fire({
                title: "起始數字不可高於結尾數字",
                icon: "error"
            })
            $(this).val('')
            $('#minutesum').val('')
        } else if (s != '' && e != '') {
            $('#minutesum').val(e - s + 1)
        } else {
            $('#minutesum').val('')
        }
    })
    $('#employees,#employeee').on('change', function () {
        let s = parseInt($('#employees').val())
        let e = parseInt($('#employeee').val())
        if (s > e && s != '' && e != '') {
            swal.fire({
                title: "起始數字不可高於結尾數字",
                icon: "error"
            })
            $(this).val('')
            $('#employeesum').val('')
        } else if (s != '' && e != '') {
            $('#employeesum').val(e - s + 1)
        } else {
            $('#employeesum').val('')
        }
    })
    $('#discerns,#discerne').on('change', function () {
        let s = parseInt($('#discerns').val())
        let e = parseInt($('#discerne').val())
        if (s > e && s != '' && e != '') {
            swal.fire({
                title: "起始數字不可高於結尾數字",
                icon: "error"
            })
            $(this).val('')
            $('#discernsum').val('')
        } else if (s != '' && e != '') {
            $('#discernsum').val(e - s + 1)
        } else {
            $('#discernsum').val('')
        }
    })
    $('#customFile').on('change', function () {
        let file = $(this)[0].files[0];
        let extension = file.name.split('.').pop().toLowerCase();  //file extension from input file
        let reader = new FileReader();
        let allow_ext = ['txt'];
        let isSuccess = allow_ext.indexOf(extension) > -1;
        if (!isSuccess) {
            swal.fire({
                title: '請上傳txt格式',
                icon: 'error'
            })
            $(this).val('');
            $('#uploadBtn').prop('disabled', true);
            data_arr = [];
            return false;
        }
        reader.readAsText(file);

        reader.onload = function () {
            data_arr = reader.result.split('\r\n');
            // console.log(data_arr);
            $('#uploadBtn').prop('disabled', false);
        };

        reader.onerror = function () {
            $('#uploadBtn').prop('disabled', true);
            console.log(reader.error);
            data_arr = [];
        };
    })
    $('#uploadBtn').on('click', function () {
        let Form_Data = new Array();
        let token = $('input[name=token]').val();
        let token_array = { "name": "token", "value": token };
        let action = { "name": "_type", "value": "daka_upload" };
        Form_Data.push({ "name": "data", "value": data_arr });
        Form_Data.push(token_array)
        Form_Data.push(action)
        // console.log(Form_Data);

        $.ajax({
            url: 'ajax/ajax.php',
            type: "POST",
            data: Form_Data,
            beforeSend: function () {
                Swal.fire({
                    title: "上傳中...",
                    html: `
                                <div class="card-body d-flex align-items-center justify-content-center" style="height: 160px">
                                    <div class="sk-chase">
                                        <div class="sk-chase-dot"></div>
                                        <div class="sk-chase-dot"></div>
                                        <div class="sk-chase-dot"></div>
                                        <div class="sk-chase-dot"></div>
                                        <div class="sk-chase-dot"></div>
                                        <div class="sk-chase-dot"></div>
                                    </div>
                                </div>
                                `,
                    showConfirmButton: false,
                    isDismissed: true
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.close()
                Swal.fire({
                    title: "上傳失敗，請重新試一次！",
                });
            },
            success: function (data, textStatus, jqXHR) {
                var _msg = JSON.parse(data);
                console.log(_msg.html_content)
                Swal.close()
                if (_msg.html_status == '1') {
                    swal.fire({
                        title: "訊息",
                        text: _msg.html_msg,
                        icon: 'error'
                    });
                } else {
                    swal.fire({
                        title: "訊息",
                        text: _msg.html_msg,
                        icon: 'success'
                    }).then((result) => {
                        location.reload();
                    });
                }

            }
        })
    })
})