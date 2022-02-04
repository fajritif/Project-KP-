@extends('layouts.app')

@section('css')
    @parent
    {{-- Tambahkan <style> disini --}}
@endsection

@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <h6 class="mb-0 text-uppercase">Tekanan Boiler All PTPN</h6>

        <hr/>


        <div class="card">
            <div class="card-body">
                <div id="chart"></div>
            </div>
        </div>



@endsection

@section('js')
    @parent
    {{-- Tambahkan <script> disini --}}
        <script src="{{ url('') }}/assets/plugins/apexcharts-bundle/js/apexcharts.js"></script>
        <script>
//   const item=[]
//         $.getJSON('{{url('api/latest-boiler')}}', function(response) {



  //categories = categories.concat(item[x].NAMA_PTPN);



function chartData(dataset,category){
  var options = {
          series: dataset,
          chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: category,
        },
        yaxis: {
          title: {
            text: '$ (thousands)'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "" + val + " atm"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

}
// $.getJSON('{{url('api/latest-boiler')}}', function(response) {
//   console.log(response)
//   for (let x = 0; x < response.length; x++) {
//   chart.updateSeries([{
//     name: response[x].KETERANGAN,
//     data: [response[x].TEKANAN]
//   }]),
//   console.log(response[x].TEKANAN)
// }
// });
$.getJSON('{{url('api/latest-boiler')}}', function(response) {
                const dataSet = []
                const category=[]

                response.forEach((element) => {
                    let itemData = {
                        name: element.KETERANGAN,
                        data: [
                            element.TEKANAN
                        ]
                    }

                    dataSet.push(itemData)
                    category.push(element.NAMA_PTPN)

                })
                if (dataSet.length > 0) {
                    chartData(dataSet,category)
                }
            })


</script>

@endsection

