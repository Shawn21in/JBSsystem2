/*
 * 調用Chart 前端按鈕動作
 *
 * */


function hihi(aaa) {
    alert(aaa);
}


function makeChart(getDIV) {
    var main = document.getElementById(getDIV);
    var div = document.createElement('div');
    var width = document.body.clientWidth;
    div.style.cssText = width + 'px; height:500px';
    main.appendChild(div);
    return echarts.init(div);
}

function lineChartResult(inputData, getDIV, getChartTile) {

    $(`#${getDIV}`).empty();
    var chart;
    var myChart;

    console.log('js-in');
    $.ajax({
        type: 'POST',
        url: 'php/record/recordclassList.post.php',
        dataType: "json",
        data: inputData,
        success: function (data) {
            let stype ='';

            if(data['type']=='login'){
                stype = '登入';
            }else if(data['type']=='select'){
                stype = '查詢';
                
            }else if(data['type']=='update'){             
                stype = '修改';
            }

            $(".datalist").append(data.shtml);
            $(".blue bolder").hide();

            record_chart(data,getDIV,stype );
            

        }
    });
}

function record_chart(data,getDIV,stype){
    if (data[0] != '') {

        if (parseInt(data.maxdata) > 10) {

            var maxVioCounts = parseInt(data.maxdata);
            var intercvalMaxCounts = parseInt((maxVioCounts) / 10); /*間距5等分*/

        } else {

            var maxVioCounts = 10;
            var intercvalMaxCounts = parseInt((maxVioCounts) / 5); /*間距5等分*/

        }



        require([

            'echarts' /*, 'map/js/china' */

        ], function (echarts) {


            //var chart = makeChart(getDIV);    

            var labelOption = {
                normal: {
                    show: true,
                    position: 'insideBottom',
                    rotate: 90,
                    textStyle: {
                        align: 'left',
                        verticalAlign: 'middle'
                    }
                }
            };


            var option = {
                title: {

                    text: 'AI智慧問卷生成服務平台',
                    subtext:  '華越資通'+' ' +data.date1 + '~' + data.date2 + stype + ' 使用者軌跡',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'cross',
                        crossStyle: {
                            color: '#999'
                        }
                    }
                },
                toolbox: {
                    feature: {
                        dataView: {
                            show: false,
                            readOnly: false
                        },
                        magicType: {
                            title: '轉換圖表',
                            show: true,
                            type: ['line', 'bar']
                        },
                        restore: {
                            title: '還原',
                            show: true
                        },
                        saveAsImage: {
                            title: '下載',
                            show: true
                        }
                    }
                },
                legend: {
                    orient: 'horizontal',
                    left: 'left',
                    data: ['新增','修改','刪除','查看'],

                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '15%',
                    containLabel: true
                },
                dataZoom: {
                    show: true,
                    realtime: true,
                    start: 10,
                    end: 150
                },
                xAxis: [{
                    type: 'category',
                    data: data.xunit,
                    axisPointer: {
                        type: 'shadow'
                    },
                    axisLabel: {
                        interval: 0,
                        rotate: 40,
                        fontSize: 12,
                        // formatter:function(value)
                        // {
                        //     return value.split("").join("\n");
                        // }
                    }
                }],
                yAxis: [{
                        type: 'value',
                        name: '筆數',
                        min: 0,
                        max: parseInt(maxVioCounts) + parseInt(maxVioCounts / 5),
                        interval: intercvalMaxCounts,
                        axisLabel: {
                            formatter: '{value} '
                        }
                    }
                    // ,
                    // {
                    //     type: 'value',
                    //     name: '數量',
                    //     min: 0,
                    //     max: parseInt(maxVioCounts) + parseInt(maxVioCounts / 5),
                    //     interval: intercvalMaxCounts,
                    //     axisLabel: {
                    //         formatter: '{value} '
                    //     }
                    // }
                ],
                series: [{
                        name: '新增',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: data.line1data

                        //data.line1_chartlist

                    },
                    {
                        name: '修改',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: data.line2data

                        //data.line1_chartlist

                    },
                    {
                        name: '刪除',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: data.line3data

                        //data.line1_chartlist

                    },
                    {
                        name: '查看',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: data.line4data

                        //data.line1_chartlist

                    }
                    // ,
                    // {
                    //     name: '寄送失敗',
                    //     type: 'line',
                    //     label: {
                    //         show: true,
                    //         position: 'top'
                    //     },
                    //     data: data.line2data
                    // }
                ]
            };

            chart = myChart = testHelper.create(echarts, getDIV, {
                option: option,


            });


            //chart.setOption(option);

        });


    } else {

    }
}

