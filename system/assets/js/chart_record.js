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

            $(".datalist").html(data.shtml);
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
                    subtext:  data.companyname +data.date1 + '~' + data.date2 + stype + ' 使用者軌跡',
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
                    data: ['登入','問券生成','投放問券','折扣券','數據','設定'],

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
                        name: '登入',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: data.line1data

                        //data.line1_chartlist

                    },
                    {
                        name: '問券生成',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: data.line2data

                        //data.line1_chartlist

                    },
                    {
                        name: '投放問券',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: data.line3data

                        //data.line1_chartlist

                    },
                    {
                        name: '折扣券',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: data.line4data

                        //data.line1_chartlist

                    },
                    {
                        name: '數據',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: data.line5data

                        //data.line1_chartlist

                    },
                    {
                        name: '設定',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: data.line6data

                        //data.line1_chartlist

                    }
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

