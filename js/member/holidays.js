$(function () {
    $('#ensureBtn').on('click', function () {
        let niandu = $('input[name=niandu]').val();
        let AD = parseInt(niandu) + 1911;
        console.log(niandu)
        console.log(AD)
        $('input[name=niandu]').prop('readonly', true)
        $('#addBtn').show()
        $('#autoimpBtn').show()
        $('input[name^=holiday]').attr('min', AD + '-01-01');
        $('input[name^=holiday]').attr('max', AD + '-12-31');
        $(this).attr('disabled', true)
    })
    $('#addBtn').on('click', function () {
        let appdata = $('.invisible_table>tbody').html();
        $('.datalist').append(appdata);
    })
    $('#autoimpBtn').on('click', function () {
        Swal.fire({
            title: "確定要從線上自動匯入行事曆?",
            html: "<p><span style='color:red'>請注意！</span>若確認匯入而原先下方的資料會被刪除後匯入</p>",
            input: "checkbox",
            inputValue: 1,
            inputPlaceholder: '是否自動設定0501勞動節為國定假日',
            showDenyButton: true,
            confirmButtonText: "確認",
            denyButtonText: "取消"
        }).then((result) => {
            if (result.isConfirmed) {
                let year = parseInt($('#niandu').val()) + 1911;
                // console.log(result.value); //是否自動設定0501勞動節為國定假日，1為同意，0為不同意
                var labor = result.value;
                $.ajax({
                    url: 'ajax/calendar.php',
                    type: "POST",
                    data: {
                        year: year,
                    },
                    beforeSend: function () {
                        $('.autoupload-icon').removeClass('mdi-cloud-upload-outline');
                        $('.autoupload-icon').addClass('mdi-spin mdi-loading');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('.autoupload-icon').removeClass('mdi-spin mdi-loading');
                        $('.autoupload-icon').addClass('mdi-cloud-upload-outline');
                        alert('線上取得失敗，請重新試一次！')
                        location.reload();
                    },
                    success: function (data, textStatus, jqXHR) {
                        $('.autoupload-icon').removeClass('mdi-spin mdi-loading');
                        $('.autoupload-icon').addClass('mdi-cloud-upload-outline');
                        var _msg = JSON.parse(data);
                        console.log(_msg.html_content)
                        if (_msg.html_status == '1') {
                            swal.fire({
                                title: "訊息",
                                text: _msg.html_msg,
                                icon: 'error'
                            });
                        } else {
                            let content = Object.values(_msg.html_content)
                            let datalist_html = '';
                            content.forEach(function (value, index, array) {
                                datalist_html += '<tr>';
                                datalist_html += '<th scope="col">';
                                datalist_html += ' <input name="holiday[]" min="' + year + '-01-01" max="' + year + '-12-31" value="' + year + '-' + value.date + '" type="date" class="form-control">'
                                datalist_html += '</th>';
                                datalist_html += '<th scope="col"><input name="holidayName[]" value="' + value.description + '" type="text" class="form-control"></th>';
                                datalist_html += '<th scope="col">';
                                datalist_html += '<select class="form-control" name="AttendDay[]" style="width:unset">';
                                if (value.description == '補行上班') {
                                    datalist_html += '<option value="工作日" selected>工作日</option>';
                                } else {
                                    datalist_html += '<option value="工作日">工作日</option>';
                                }
                                datalist_html += '<option value="休息日">休息日</option>';
                                datalist_html += '<option value="例假日">例假日</option>';
                                if (value.description == '補行上班') {
                                    datalist_html += '<option value="國定日">國定日</option>';
                                } else {
                                    datalist_html += '<option value="國定日" selected>國定日</option>';
                                }
                                datalist_html += '<option value="空班日">空班日</option>';
                                datalist_html += '</select>';
                                datalist_html += '</th>';
                                datalist_html += '<th scope="col">';
                                datalist_html += '<a href="javascript:void(0)" class="data_del"><span class="mdi mdi-delete mdi-18px"></span></a>';
                                datalist_html += '</th>';
                                datalist_html += '</tr>';
                            })
                            if (labor == 1) {
                                datalist_html += '<tr>';
                                datalist_html += '<th scope="col">';
                                datalist_html += ' <input name="holiday[]" min="' + year + '-01-01" max="' + year + '-12-31" value="' + year + '-05-01" type="date" class="form-control">'
                                datalist_html += '</th>';
                                datalist_html += '<th scope="col"><input name="holidayName[]" value="勞動節" type="text" class="form-control"></th>';
                                datalist_html += '<th scope="col">';
                                datalist_html += '<select class="form-control" name="AttendDay[]" style="width:unset">';
                                datalist_html += '<option value="工作日">工作日</option>';
                                datalist_html += '<option value="休息日">休息日</option>';
                                datalist_html += '<option value="例假日">例假日</option>';
                                datalist_html += '<option value="國定日" selected>國定日</option>';
                                datalist_html += '<option value="空班日">空班日</option>';
                                datalist_html += '</select>';
                                datalist_html += '</th>';
                                datalist_html += '<th scope="col">';
                                datalist_html += '<a href="javascript:void(0)" class="data_del"><span class="mdi mdi-delete mdi-18px"></span></a>';
                                datalist_html += '</th>';
                                datalist_html += '</tr>';
                            }
                            $('.datalist').html(datalist_html);
                            swal.fire({
                                title: "成功",
                                text: "自動匯入成功",
                                icon: 'success'
                            });
                        }

                    }
                })
            }
        });
    })
    $(document).on('click', '.data_del', function () {
        $(this).closest('tr').remove();
    })
})