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
    // $('#addBtn').on('click', function () {
    //     let appdata = $('.invisible_table>tbody').html();
    //     $('.edit_datalist').append(appdata);
    //     $('.no').each(function (key, value) {
    //         $(value).text(key + 1);
    //     })
    // })
    $(document).on('click', '.data_del', function () {
        $(this).closest('tr').remove();
        $('.no').each(function (key, value) {
            $(value).text(key + 1);
        })
    })
    // $("#onlineOutputBtn").click(function () {
    //     var csvList = [],
    //         titleList = [],
    //         memberContent = "",
    //         csvContent;
    //     // 取得標題
    //     $(".card-body th").each(function () {
    //         titleList.push(this.innerHTML);
    //     });
    //     csvList.push(titleList);
    //     // 取得所有資料
    //     $(".datalist > tr").each(function () {
    //         var regList = [];
    //         $(this).children("td").each(function () {
    //             regList.push(this.innerHTML);
    //         });
    //         csvList.push(regList);
    //     });
    //     // 產生 csv 內容
    //     csvList.forEach(function (rowArray) {
    //         var row = rowArray.join(",");
    //         memberContent += row + "\r\n";
    //     });
    //     console.log(memberContent);
    //     // 產生 csv Blob
    //     csvContent = URL.createObjectURL(new Blob(["\uFEFF" + memberContent], {
    //         type: 'text/csv;charset=utf-8;'
    //     }));
    //     console.log(csvContent);
    //     // 產生 csv 連結
    //     this.href = csvContent;
    //     console.log(this.href);
    // });
    $('#onlineInputBtn').on('click', function () {
        console.log("onlineInputBtn onclick");
        Swal.fire({
            title: "自動線上匯入?",
            showDenyButton: true,
            confirmButtonText: "確定",
            denyButtonText: "取消"

        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ajax/labor.php',
                    type: "post",
                    data: {
                        year: 103,
                    },
                    error: function () {
                        alert("失敗");
                    },
                    success: function (data) {
                        $("#test1").html(data)
                        console.log(data[2]);
                        Swal.fire("成功!", "自動匯入成功", "success");
                    }
                });

            }
        });
    });
})