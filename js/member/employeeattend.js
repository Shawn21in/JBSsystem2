$(function () {
    $(window).on('load', function () {
        $('#employ_select .e_radio').eq(0).trigger('click');
    })
    $("#search_eid").chosen({
        width: "50%",
        search_contains: true,
    });
    $("#gen_ed").chosen({
        width: "45%",
        search_contains: true,
    });
    $("#gen_ed2").chosen({
        width: "45%",
        search_contains: true,
    });

    $(".drag_me").draggable({
        axis: "x",
        drag: function (event, ui) {
            $('.table-scroll').css('cursor', 'grabbing');
            var element = $(".table-scroll")[0];
            // console.log(element.scrollWidth)
            // console.log(element.scrollLeft)
            // console.log(element.clientWidth)
            // 判斷是否已經滑到最右側
            if (element.scrollWidth - element.scrollLeft <= element.clientWidth + 1) {
                $(".table-scroll").scrollLeft(element.scrollWidth);
                $(".drag_me").css('left', '0px');
                return false;
            }
            // 判斷是否已經滑到最左側
            if (element.scrollLeft === 0) {
                $(".table-scroll").scrollLeft(1);
                $(".drag_me").css('left', '0px');
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
    $('#gen_niandu').on('change', function () {
        let year = parseInt($(this).val()) + 1911;
        $('input[name=startdate]').val(year + "-01-01")
        $('input[name=enddate]').val(year + "-12-31")
        // console.log(year);
        $('#daterange').daterangepicker({
            startDate: "01/01/" + year,
            endDate: "12/31/" + year,
            minDate: "01/01/" + year,
            maxDate: "12/31/" + year,
            locale: {
                cancelLabel: 'Clear'
            }
        }, function (start, end, label) {
            $('input[name=startdate]').val(start.format('YYYY-MM-DD'))
            $('input[name=enddate]').val(end.format('YYYY-MM-DD'))
            // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    })
    $('#daterange').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
    //切換月份
    $('input[name=month]').on('change', function () {
        let i = $(this).val();
        $('.mon').find('input').prop('disabled', true);
        $('.mon').find('select').prop('disabled', true);
        $('.mon').hide();
        $('.month' + i).find('input').prop('disabled', false);
        $('.month' + i).find('select').prop('disabled', false);
        $('.month' + i).show();
    })
    //切換班別設定
    $(document).on('change', 'select[name^=attendno]', function () {
        let current_col = $(this).closest('tr');

        // let Form_Data = new Array();
        // let eid = $(this).closest('tr').find('input[name^=eid]').val();//出勤曆編號
        // let eid_array = { "name": "eid", "value": eid };
        // let employeid = $('input[name^=employeid]:checked').val();//出勤曆編號
        // let employeid_array = { "name": "employeid", "value": employeid };
        // let attendno = $(this).val();
        // let attendno_array = { "name": "attendno", "value": attendno };
        // let token = $('input[name=token]').val();
        // let token_array = { "name": "token", "value": token };
        // let action = { "name": "_type", "value": "employeeattend_attendswitch" };
        // Form_Data.push(action)
        // Form_Data.push(eid_array)
        // Form_Data.push(employeid_array)
        // Form_Data.push(attendno_array)
        // Form_Data.push(token_array)
        current_col.find('input[name^=attendname]').val($(this).find('option:selected').data('name'))
        // console.log(Form_Data)
        // $.ajax({
        //     url: 'ajax/ajax.php',
        //     type: "POST",
        //     data: Form_Data,
        //     beforeSend: function () {
        //         Swal.fire({
        //             title: "修改中...",
        //             html: `
        //                     <div class="card-body d-flex align-items-center justify-content-center" style="height: 160px">
        //                         <div class="sk-chase">
        //                             <div class="sk-chase-dot"></div>
        //                             <div class="sk-chase-dot"></div>
        //                             <div class="sk-chase-dot"></div>
        //                             <div class="sk-chase-dot"></div>
        //                             <div class="sk-chase-dot"></div>
        //                             <div class="sk-chase-dot"></div>
        //                         </div>
        //                     </div>
        //                     `,
        //             showConfirmButton: false,
        //             isDismissed: true
        //         });
        //     },
        //     error: function (jqXHR, textStatus, errorThrown) {
        //         Swal.close()
        //         Swal.fire({
        //             title: "線上取得失敗，請重新試一次！",
        //         });
        //     },
        //     success: function (data, textStatus, jqXHR) {
        //         let _msg = JSON.parse(data);
        //         // console.log(_msg.html_content)
        //         // console.log(eid);
        //         let content = _msg.html_content;
        //         Swal.close()
        //         if (_msg.html_status == '1') {
        //             swal.fire({
        //                 title: "訊息",
        //                 text: _msg.html_msg,
        //                 icon: 'error'
        //             });
        //         } else {
        //             // console.log(current_col);
        //             current_col.find('select[name^=attendday] option').prop('selected', false)
        //             current_col.find('select[name^=attendday] option').each(function (key, value) {
        //                 if ($(value).val() == content['type']) {
        //                     $(value).prop('selected', true)
        //                 }
        //             })
        //             if (content['type'] == '工作日') {
        //                 current_col.find('input[name^=ontime]').val(content['ontime'])
        //                 current_col.find('input[name^=offtime]').val(content['offtime'])
        //                 current_col.find('input[name^=restime1]').val(content['resttime1'])
        //                 current_col.find('input[name^=restime2]').val(content['resttime2'])
        //             } else {
        //                 current_col.find('input[name^=ontime]').val('')
        //                 current_col.find('input[name^=offtime]').val('')
        //                 current_col.find('input[name^=restime1]').val('')
        //                 current_col.find('input[name^=restime2]').val('')
        //             }

        //             if (content['starttype'] == '1' && content['type'] == '工作日') {
        //                 current_col.find('input[name^=daka]').prop('checked', true);
        //             } else {
        //                 current_col.find('input[name^=daka]').prop('checked', false);
        //             }

        //             // swal.fire({
        //             //     title: "訊息",
        //             //     text: _msg.html_msg,
        //             //     icon: 'success'
        //             // }).then((result) => {
        //             //     $('#heading2 button').trigger('click');
        //             // });
        //         }

        //     }
        // })
    })
    //查詢出勤曆
    $('input[name=employeid],input[name=niandu]').on('change', function () {
        let Form_Data = new Array();
        let eid = $('input[name=employeid]:checked').val();
        let eid_array = { "name": "eid", "value": eid };
        let niandu = $('input[name=niandu]').val();
        let niandu_array = { "name": "niandu", "value": niandu };
        let token = $('input[name=token]').val();
        let token_array = { "name": "token", "value": token };
        let action = { "name": "_type", "value": "employeeattend_search" };
        if (niandu == '') {
            swal.fire({
                title: "請先填入年度",
                icon: "info"
            })
            $('input[name=employeid]:checked').prop('checked', false);
            return false;
        } else if (eid === undefined) {
            return false;
        }
        Form_Data.push(eid_array)
        Form_Data.push(niandu_array)
        Form_Data.push(token_array)
        Form_Data.push(action)
        // console.log(Form_Data);

        $.ajax({
            url: 'ajax/ajax.php',
            type: "POST",
            data: Form_Data,
            beforeSend: function () {
                Swal.fire({
                    title: "查詢中...",
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
                    title: "線上取得失敗，請重新試一次！",
                });
                $('.ea_saveBtn').prop('disabled', true);
                $('.ea_delBtn').prop('disabled', true);
            },
            success: function (data, textStatus, jqXHR) {
                var _msg = JSON.parse(data);
                // console.log(_msg.html_content)
                Swal.close()
                if (_msg.html_status == '1') {
                    $('input[name=employeid]').closest('tr').addClass('table-secondary')
                    $('input[name=employeid]').closest('tr').removeClass('table-primary')
                    $('input[name=employeid]:checked').prop('checked', false);
                    $('.mon').html('');
                    swal.fire({
                        title: "訊息",
                        text: _msg.html_msg,
                        icon: 'error'
                    });
                    $('.saveBtn').prop('disabled', true);
                    //按鈕功能關閉
                    $('.ea_saveBtn').prop('disabled', true);
                    $('.ea_delBtn').prop('disabled', true);
                } else {
                    let attdlist = Object.values(_msg.html_content['attdlist'])
                    let count = 0;
                    for (let i = 1; i <= 12; i++) {
                        let content = Object.values(_msg.html_content[i])
                        let month_html = '';
                        content.forEach(function (value, index, array) {
                            month_html += '<tr>';
                            month_html += '<th>';
                            month_html += value.ndweektype;
                            month_html += '<input class="form-control" type="hidden" style="width:unset" name="eid[' + index + ']" value="' + value.eid + '" disabled readonly>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += value.nddate;
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<select class="form-control" name="attendday[' + index + ']" style="width:unset" disabled>';
                            month_html += '<option value="工作日"' + (value.attendday == '工作日' ? 'selected' : '') + '>工作日</option>';
                            month_html += '<option value="休息日"' + (value.attendday == '休息日' ? 'selected' : '') + '>休息日</option>';
                            month_html += '<option value="例假日"' + (value.attendday == '例假日' ? 'selected' : '') + '>例假日</option>';
                            month_html += '<option value="國定日"' + (value.attendday == '國定日' ? 'selected' : '') + '>國定日</option>';
                            month_html += '<option value="空班日"' + (value.attendday == '空班日' ? 'selected' : '') + '>空班日</option>';
                            month_html += '</select>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<label class="switch switch-primary switch-pill form-control-label">'
                            month_html += '<input type="checkbox" class="switch-input form-check-input" name="isnearly[' + index + ']" value="1" ' + (value.isnearly == '1' ? 'checked' : '') + ' disabled>'
                            month_html += '<span class="switch-label"></span>';
                            month_html += '<span class="switch-handle"></span>';
                            month_html += '</label>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<select class="form-control" name="attendno[' + index + ']" style="width:unset" disabled>';
                            attdlist.forEach(function (value2, index2, array2) {
                                month_html += '<option value="' + value2.attendanceno + '" data-name="' + value2.attendancename + '" ' + (value2.attendanceno == value.attendno ? 'selected' : '') + '>' + value2.attendanceno + '-' + value2.attendancename + '</option>';
                            })
                            month_html += '</select>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="text" style="width:unset" name="attendname[' + index + ']" value="' + value.attendname + '" disabled readonly>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<label class="switch switch-primary switch-pill form-control-label">'
                            month_html += '<input type="checkbox" class="switch-input form-check-input" name="daka[' + index + ']" value="1" ' + (value.daka == '1' ? 'checked' : '') + ' disabled>'
                            month_html += '<span class="switch-label"></span>';
                            month_html += '<span class="switch-handle"></span>';
                            month_html += '</label>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="number" style="width:unset" name="ontime[' + index + ']" value="' + value.ontime + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="number" style="width:unset" name="offtime[' + index + ']" value="' + value.offtime + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="number" style="width:unset" name="restime1[' + index + ']" value="' + value.restime1 + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="number" style="width:unset" name="restime2[' + index + ']" value="' + value.restime2 + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="number" style="width:unset" name="addontime[' + index + ']" value="' + value.addontime + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="number" style="width:unset" name="addofftime[' + index + ']" value="' + value.addofftime + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="number" style="width:unset" name="latemins[' + index + ']" value="' + value.latemins + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="number" style="width:unset" name="worktime[' + index + ']" value="' + value.worktime + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="number" style="width:unset" name="jiabantime[' + index + ']" value="' + value.jiabantime + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="number" style="width:unset" name="qingjiatime[' + index + ']" value="' + value.qingjiatime + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="text" style="width:unset" name="absencename[' + index + ']" value="' + value.absencename + '" disabled>';
                            month_html += '</th>';
                            month_html += '<th>';
                            month_html += '<input class="form-control" type="text" style="width:unset" name="memo[' + index + ']" value="' + value.memo + '" disabled>';
                            month_html += '</th>';
                            month_html += '</tr>';
                            count++;
                        })
                        $('.month' + i).html(month_html);
                    }
                    //提示選擇當前員工
                    $('input[name=employeid]').closest('tr').addClass('table-secondary')
                    $('input[name=employeid]').closest('tr').removeClass('table-primary')
                    $('input[name=employeid]:checked').closest('tr').addClass('table-primary')
                    $('input[name=employeid]:checked').closest('tr').removeClass('table-secondary')
                    //按鈕功能開啟
                    $('.ea_saveBtn').prop('disabled', false);
                    $('.ea_delBtn').prop('disabled', false);
                    //月份修改開啟
                    let month = $('input[name=month]:checked').val();
                    $('.month' + month).find('input').prop('disabled', false);
                    $('.month' + month).find('select').prop('disabled', false);
                    $('.month' + month).show();
                    swal.fire({
                        title: "訊息",
                        text: _msg.html_msg,
                        icon: 'success'
                    }).then((result) => {
                        $('#heading2 button').trigger('click');
                    });
                }

            }
        })

    })
    //產生員工出勤曆
    $('.subBtn').on('click', function () {
        if (form_check('generate_date')) {
            let field = $('#generate_date');
            let Form_Data = new Array();
            let token = $('input[name=token]').val();
            let token_array = { "name": "token", "value": token };
            let action = { "name": "_type", "value": $(this).data('type') };
            Form_Data = field.serializeArray();
            Form_Data.push(token_array)
            Form_Data.push(action)
            $.ajax({
                url: 'ajax/ajax.php',
                type: "POST",
                data: Form_Data,
                beforeSend: function () {
                    Swal.fire({
                        title: "生成中...",
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
                        title: "線上取得失敗，請重新試一次！",
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
        }
    })
    //儲存出勤曆修改
    $('.ea_saveBtn').on('click', function () {
        if (form_check('form1')) {
            let field = $('#form1');
            let Form_Data = new Array();
            let eid = $('input[name=employeid]:checked').val();
            let eid_array = { "name": "employeid", "value": eid };
            let niandu = $('input[name=niandu]').val();
            let niandu_array = { "name": "niandu", "value": niandu };
            let month = $('input[name=month]:checked').val();
            let month_array = { "name": "month", "value": month };
            let token = $('input[name=token]').val();
            let token_array = { "name": "token", "value": token };
            Form_Data = field.serializeArray();
            Form_Data.push(token_array)
            Form_Data.push(niandu_array)
            Form_Data.push(eid_array)
            Form_Data.push(month_array)
            // console.log(Form_Data)
            let action = { "name": "_type", "value": $(this).data('type') };
            Form_Data.push(action)
            $.ajax({
                url: 'ajax/ajax.php',
                type: "POST",
                data: Form_Data,
                beforeSend: function () {
                    Swal.fire({
                        title: "儲存中...",
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
                        title: "線上取得失敗，請重新試一次！",
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
                        });
                    }

                }
            })
        }
    })
    //刪除出勤曆單月份
    $('.ea_delBtn').on('click', function () {
        let eid = $('input[name=employeid]:checked').val();
        let eid_array = { "name": "employeid", "value": eid };
        let niandu = $('input[name=niandu]').val();
        let niandu_array = { "name": "niandu", "value": niandu };
        let month = $('input[name=month]:checked').val();
        let month_array = { "name": "month", "value": month };
        let token = $('input[name=token]').val();
        let token_array = { "name": "token", "value": token };
        let action = { "name": "_type", "value": $(this).data('type') };
        Swal.fire({
            title: "確定要刪除" + month + "月份的資料?",
            showDenyButton: true,
            confirmButtonText: "確定",
            denyButtonText: "取消"
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                let Form_Data = new Array();
                Form_Data.push(token_array)
                Form_Data.push(niandu_array)
                Form_Data.push(eid_array)
                Form_Data.push(month_array)
                console.log(Form_Data)
                Form_Data.push(action)
                $.ajax({
                    url: 'ajax/ajax.php',
                    type: "POST",
                    data: Form_Data,
                    beforeSend: function () {
                        Swal.fire({
                            title: "刪除中...",
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
                            title: "線上取得失敗，請重新試一次！",
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
                            });
                            $('.month' + month).html('');
                        }

                    }
                })
            }
        });
    })
})