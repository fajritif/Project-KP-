@extends('layouts.app')

@section('css')
    @parent
    {{-- Tambahkan <style> disini --}}
@endsection

@section('content')

    <h6 class="mb-0 text-uppercase">Data Widgets  @foreach($pksName as $Name) 
    {{$Name->NAMA }}@endforeach</h6>
    <hr/>
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        @foreach($data as $item)
            <div class="col">
                <div class="card radius-10" onclick="handleClickDevice('{{ $item->DEVICE_ID }}')" style="cursor: pointer">
                    <div class="card-body">
                        <div class="align-items-center">
                            <div class="text-center">
                                <h6 class="mb-0">{{ $item->DEVICE_NAME }}</h6>
                            </div>
                        </div>
                        <div class="" id="{{ $item->DEVICE_ID }}"></div>
                        <div class="align-items-center">
                            <span id="{{ "lastUpdate".str_replace("-", "", $item->DEVICE_ID) }}"></span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('js')
    @parent
    {{-- Tambahkan <script> disini --}}
    <script src="{{ url('') }}/assets/plugins/highcharts/js/highcharts.js"></script>
    <script src="{{ url('') }}/assets/plugins/highcharts/js/highcharts-more.js"></script>
    <script>

        function handleClickDevice(deviceId) {
            window.location.href = '{{ url("ptpn/device/") }}/'+deviceId
        }

        function drawGaugeChart(chartId, lastUpdateId, dataValue, lastUpdate, gaugeTitle, gaugeSatuan, standartBlock) {
            if (lastUpdate) {
                document.getElementById(lastUpdateId).textContent = "Last Update "+lastUpdate;
            } else {
                document.getElementById(lastUpdateId).textContent = "Device Not Active";
            }
            let chart = Highcharts.chart(chartId, {
                    chart: {
                        type: 'gauge',
                        plotBackgroundColor: null,
                        plotBackgroundImage: null,
                        plotBorderWidth: 0,
                        plotShadow: false,
                        height: 200,
                        marginTop: 0
                    },
                    credits: {
                        enabled: false
                    },
                    title: {
                        text: null
                    },
                    pane: {
                        startAngle: -150,
                        endAngle: 150,
                        background: [{
                            backgroundColor: {
                                linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                stops: [
                                    [0, '#FFF'],
                                    [1, '#333']
                                ]
                            },
                            borderWidth: 0,
                            outerRadius: '109%'
                        }, {
                            backgroundColor: {
                                linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                stops: [
                                    [0, '#333'],
                                    [1, '#FFF']
                                ]
                            },
                            borderWidth: 1,
                            outerRadius: '107%'
                        }, {
                            // default background
                        }, {
                            backgroundColor: '#DDD',
                            borderWidth: 0,
                            outerRadius: '105%',
                            innerRadius: '103%'
                        }]
                    },
                    // the value axis
                    yAxis: {
                        min: 0,
                        max: standartBlock[2],

                        minorTickInterval: 'auto',
                        minorTickWidth: 1,
                        minorTickLength: 10,
                        minorTickPosition: 'inside',
                        minorTickColor: '#666',

                        tickPixelInterval: 30,
                        tickWidth: 2,
                        tickPosition: 'inside',
                        tickLength: 10,
                        tickColor: '#666',
                        labels: {
                            step: 2,
                            rotation: 'auto'
                        },
                        title: {
                            text: gaugeSatuan
                        },
                        plotBands: [{
                            from: 0,
                            to: standartBlock[0],
                            color: '#DF5353' // red
                        }, {
                            from: standartBlock[0],
                            to: standartBlock[1],
                            color: '#DDDF0D' // yellow
                        }, {
                            from: standartBlock[1],
                            to: standartBlock[2],
                            color: '#55BF3B' // green
                        }]
                    },
                    series: [{
                        name: gaugeTitle,
                        data: [dataValue],
                        tooltip: {
                            valueSuffix: gaugeSatuan
                        }
                    }],
                },
                // Add some life
                /*function (chart) {
                    if (!chart.renderer.forExport) {
                        setInterval(function () {
                            var point = chart.series[0].points[0],
                                newVal,
                                inc = Math.round((Math.random() - 0.5) * 20);

                            newVal = point.y + inc;
                            if (newVal < 0 || newVal > 25) {
                                newVal = point.y - inc;
                            }

                            point.update(newVal);

                        }, 3000);
                    }
                }*/);
            return chart
        }

        $(document).ready(function () {
            let arrGauge = []
            @foreach($data as $item)
                @php
                $fDate = null;
                $valData = 0;
                $standartBlock = [10,17,25];
                if(preg_match('(BLR|BPV|PRS|RBS|TRB)', $item->DEVICE_ID) === 1) {
                    $valData = round($item->PRESSURE, 2);
                }
                if(preg_match('(CST|DIG|FED|GEN)', $item->DEVICE_ID) === 1) {
                    $valData = round($item->TEMPERATURE, 2);
                    $standartBlock = [80,110, 150];
                }
                if(false !== strpos($item->DEVICE_ID, "WTP")) {
                    $valData = round($item->PH, 2);
                    $standartBlock = [4,8,14];
                }
                if(false !== strpos($item->DEVICE_ID, "CBC")) {
                    $valData = round($item->ARUS, 2);
                    $standartBlock = [4,7,15];
                }
                if ($item->TANGGAL != null) { $fDate = date('d M Y H:i:s', strtotime($item->TANGGAL)); }
                @endphp
                arrGauge["{{ $item->DEVICE_ID  }}"] =
                drawGaugeChart(
                    '{{ $item->DEVICE_ID }}',
                    '{{ "lastUpdate".str_replace("-", "", $item->DEVICE_ID) }}',
                    {{ $valData }},
                    '{{ $fDate ?: "" }}',
                    '{{ $item->TITLE_PAGE }}',
                    '{{ $item->SATUAN }}',
                    @json($standartBlock)
                )
            @endforeach
            setInterval(function () {
                fetch('{{ url('api/device-per-pks/'.$pks) }}').then(function (response) {
                    return response.json()
                }).then(function (data) {
                    data.forEach((element) => {
                        let dataVal = 0;
                        if(element.DEVICE_ID.match(/BLR|BPV|PRS|RBS|TRB/)) {
                            dataVal = Math.round(element.PRESSURE*100)/100
                        }
                        if(element.DEVICE_ID.match(/CST|DIG|FED|GEN/)) {
                            dataVal = Math.round(element.TEMPERATURE*100)/100
                        }
                        if(element.DEVICE_ID.match(/WTP/)) {
                            dataVal = Math.round(element.PH*100)/100
                        }
                        if(element.DEVICE_ID.match(/CBC/)) {
                            dataVal = Math.round(element.ARUS*100)/100
                        }
                        arrGauge[element.DEVICE_ID].series[0].points[0].update(dataVal)
                    })
                })
            }, 3000)
        })
    </script>
@endsection

