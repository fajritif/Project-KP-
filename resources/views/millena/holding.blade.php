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

//                 }).then(function (data) {

//                     data.forEach((element) => {
//                        item.push(element)
//                     })

//                 })
// console.log(item)

  //categories = categories.concat(item[x].NAMA_PTPN);


  var options = {
          series: [],
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
          categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
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
$.getJSON('{{url('api/latest-boiler')}}', function(response) {
  console.log(response)
  for (let x = 0; x < response.length; x++) {
  chart.updateSeries([{
    name: response[x].KETERANGAN,
    data: response[x].TEKANAN
  }]),
  console.log(response[x].TEKANAN)
}
});





</script>

@endsection

