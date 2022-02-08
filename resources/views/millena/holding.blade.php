@extends('layouts.app')

@section('css')
    @parent
    {{-- Tambahkan <style> disini --}}
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-9 mx-auto">
            <h6 class="mb-0 text-uppercase">Tekanan Boiler All PTPN</h6>

            <hr />


            <div class="card">
                <div class="card-body" style="width:100%">
                    <div id="chart"></div>
                    <div class="align-items-center">
                    </div>
                </div>
            </div>



        @endsection

        @section('js')

            @parent
            {{-- Tambahkan <script> disini --}}
            <script src="{{ url('') }}/assets/plugins/apexcharts-bundle/js/apexcharts.js"></script>
            <script>
                //  function chartData(dataSet) {



                // var options = {
                //     series: [],
                //     chart: {
                //         height: 350,
                //         type: 'bar'
                //     },
                //     plotOptions: {
                //         bar: {
                //             columnWidth: '60%'
                //         }
                //     },
                //     colors: ['#00E396'],
                //     dataLabels: {
                //         enabled: false
                //     },
                //     legend: {
                //         show: true,
                //         showForSingleSeries: true,
                //         customLegendItems: ['Tekanan', 'Expected'],
                //         markers: {
                //             fillColors: ['#00E396', '#775DD0']
                //         }
                //     }
                // };
                // var chart = new ApexCharts(document.querySelector("#chart"), options);
                // chart.render();

                //   }

// setInterval(function() {


                $.getJSON('{{ url('api/latest-boiler') }}', function(response) {

                    let mydata = []
                    let latest = []
                    for (let i = 0; i < response.length; i++) {

                        mydata.push({
                            x: response[i].NAMA + '(' + response[i].KETERANGAN + ')' + response[i].LATEST,
                            y: response[i].PRESSURE.toFixed(2),
                            goals: [{
                                name: 'Minimal',
                                value: 17,
                                strokeHeight: 5,
                                strokeColor: '#775DD0'
                            }],

                        })
                        latest.push(
                            response[i].LATEST

                        )
                    }
                    console.log(latest)
                    chartdata(mydata)

                })
           // }, 3000);

                function chartdata(mydata) {

                    var options = {
                        series: [{
                            name: 'Actual',
                            data: mydata
                        }],
                        chart: {
                            height: 700,
                            type: 'bar'
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '60%'
                            }
                        },
                        colors: ['#00E396'],
                        dataLabels: {

                            enabled: true,
                            style: {

                                fontSize: '6px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 400,
                                cssClass: 'apexcharts-xaxis-label',
                            }
                        },
                        xaxis: {
                            labels: {

                                show: true,
                                style: {

                                    fontSize: '5px',
                                    fontFamily: 'Helvetica, Arial, sans-serif',
                                    fontWeight: 400,
                                    cssClass: 'apexcharts-xaxis-label',
                                }
                            }
                        },

                        legend: {
                            show: true,
                            showForSingleSeries: true,
                            customLegendItems: ['Tekanan', 'Tekanan Minimal'],
                            markers: {
                                fillColors: ['#00E396', '#775DD0']
                            }
                        }


                    };
                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                }









                // $.getJSON('{{ url('api/latest-boiler') }}', function(response) {
                //         for (i=0; i< response.length;i++) {

                //             chart.appendSeries({

                //             name: response[i].NAMA,
                //              data: [{
                //                     x: 'TEKANAN',
                //                     y: response[i].PRESSURE,
                //                     goals: [{
                //                         name: 'Expected',
                //                         value: 17,
                //                         strokeHeight: 5,
                //                         strokeColor: '#775DD0'
                //                     }]
                //                 }]



                //             })
                //             console.log(response[i].NAMA)
                //         }

                // })




                // $.getJSON('{{ url('api/latest-boiler') }}', function(response) {
                //     const dataSet = []
                //     const category = []
                //     const dateAdd = []

                //     response.forEach((element) => {
                //         let itemData = {
                //             name: [element.NAMA],
                //             data: [
                //                 element.PRESSURE.toFixed(2)
                //             ],
                //             dateT: element.LATEST,
                //             cat: element.NAMA_PTPN
                //         }

                //         dataSet.push(itemData)
                //         category.push(element.NAMA)
                //         dateAdd.push(element.LATEST)

                //     })
                //     if (dataSet.length > 0) {
                //         chartData(dataSet, category, dateAdd)
                //     }
                // })
            </script>

        @endsection
