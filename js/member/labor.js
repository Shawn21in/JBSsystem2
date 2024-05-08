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
    $('#onlineHealthInputBtn').on('click', function () {
        console.log("onlineInputBtn onclick");
        Swal.fire({
            title: "確定要匯入健保最新資訊?",
            showDenyButton: true,
            confirmButtonText: "確定",
            denyButtonText: "取消"

        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var year = parseInt($("#niandu").val());
                $.ajax({
                    url: 'ajax/health.php',
                    type: "get",
                    dataType: "json",
                    beforeSend: function () {
                        let timerInterval;
                        Swal.fire({
                            title: "自動匯入中!",
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        })
                    },
                    error: function () {
                        alert("失敗");
                    },
                    success: function (data) {
                        var datalist = '';
                        var datalist_edit = '';
                        data.forEach(function (value, index, array) {
                            //console.log(value);   
                            datalist += "<tr>"
                                + "<td>" + (index + 1) + "</td>"
                                + "<td>" + parseFloat(value[0]) + "</td>"
                                + "<td>" + parseFloat(value[1]) + "</td>"
                                + "<td>" + parseFloat(value[2]) + "</td>"
                                + "</tr>";
                            datalist_edit += "<tr>"
                                + "<td class=\"no\">" + (index + 1) + "</td>"
                                + "<td><input class=\"form-control\" name=\"purchasermny[]\" type=\"number\" value=\"" + parseFloat(value[0]) + "\" step=\"any\"></td>"
                                + "<td><input class=\"form-control\" name=\"purchaserhmny[]\" type=\"number\" value=\"" + parseFloat(value[1]) + "\" step=\"any\"></td>"
                                + "<td><input class=\"form-control\" name=\"employerPurchaserhmny[]\" type=\"number\" value=\"" + parseFloat(value[2]) + "\" step=\"any\"></td>"
                                + "<td><a href=\"javascript: void (0)\" class=\"data_del\"><span class=\"mdi mdi-delete mdi-18px\"></span></a></td>"
                                + "</tr>";
                        });
                        $(".datalist").html(datalist);
                        $(".edit_datalist").html(datalist_edit);
                        Swal.fire("成功!", "匯入成功", "success");
                    },
                });

            }
        });
    });
    $("#onlinelaborInputBtn").on('click', function () {
        console.log("onlinelaborInputBtn");
        $.ajax({
            url: "ajax/labor.php",
            type: "get",
            dataType: "json",
            beforeSend: function () {
                let timerInterval;
                Swal.fire({
                    title: "自動匯入中!",
                    didOpen: () => {
                        Swal.showLoading();
                    },
                })
            },
            error: function () {
                alert("失敗");
            },
            success: function (data) {
                var datalist = '';
                var datalist_edit = '';
                data.forEach(function(value,index,array){
                    var a= Math.round(parseFloat(parseInt(value)*0.12*0.2))






                    datalist += "<tr>"+
                    "<td>"+index+"</td>"+
                    "<td>"+Math.round(parseFloat(parseInt(value)))+"</td>"+

                    "<td>"+Math.round(parseFloat(parseInt(value)*0.12*0.2))+"</td>"+
                    "<td>"+Math.round(parseFloat(parseInt(value)*0.11*0.2))+"</td>"+
                    "<td>"+Math.round(parseFloat(parseInt(value)*0.12*0.7))+"</td>"+
                    "<td>"+Math.round(parseFloat(parseInt(value)*0.11*0.7))+"</td>"+
                    "</tr>";

                    datalist_edit += "<tr>"+
                    "<td class=\"no\">"+index+"</td>"+
                    "<td><input class=\"form-control\" name=\"seclabMny[]\" type=\"number\" value=\""+Math.round(parseFloat(parseInt(value)))+"\" step=\"any\"></td>"+
                    "<td><input class=\"form-control\" name=\"seclablMny[]\" type=\"number\" value=\""+Math.round(parseFloat(parseInt(value)*0.12*0.2))+"\" step=\"any\"></td>"+
                    "<td><input class=\"form-control\" name=\"ForeignMny[]\" type=\"number\" value=\""+Math.round(parseFloat(parseInt(value)*0.11*0.2))+"\" step=\"any\"></td>"+
                    "<td><input class=\"form-control\" name=\"employerSeclablMny[]\" type=\"number\" value=\""+Math.round(parseFloat(parseInt(value)*0.12*0.7))+"\" step=\"any\"></td>"+
                    "<td><input class=\"form-control\" name=\"employerForeignMny[]\" type=\"number\" value=\""+Math.round(parseFloat(parseInt(value)*0.11*0.7))+"\" step=\"any\"></td>"+
                    "<td><a href=\"javascript:void(0)\" class=\"data_del\"><span class=\"mdi mdi-delete mdi-18px\"></span></a></td>"+
                    "</tr>";

                    

                });
                $(".datalist").html(datalist);
                $(".edit_datalist").html(datalist_edit);
                Swal.fire("成功!", "匯入成功", "success");
            },
        });
    });
})