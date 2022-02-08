@extends('layouts.app')

@section('css')
    @parent
    {{-- Tambahkan <style> disini --}}
    <link href="{{ url('vertical') }}/assets/plugins/datetimepicker/css/classic.css" rel="stylesheet" />
    <link href="{{ url('vertical') }}/assets/plugins/datetimepicker/css/classic.date.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="card shadow-none bg-transparent border-bottom border-2">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="mb-3 mb-md-0">{{ $detail->NAMA_COMPANY }} | {{ $detail->NAMA_PKS }}</h4>
                </div>
                <div class="col-md-8">
                    <form class="float-md-end">
                        <div class="row row-cols-md-auto g-lg-3">
                            <label for="date_history" class="col-md-2 col-form-label text-md-end">Tanggal</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control datepicker" name="date_history" id="date_history"/>
                            </div>
                            <label for="selectDeviceId" class="col-md-2 col-form-label text-md-end">Device ID</label>
                            <div class="col-md-5">
                                <select id="selectDeviceId" class="form-select">
                                    <option value="">- Pilih Device -</option>
                                    @foreach($deviceList as $itemList)
                                        <option value="{{ $itemList->KODE_DEVICE }}" @if($deviceId == $itemList->KODE_DEVICE) selected @endif>{{ $itemList->KETERANGAN }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="padding-right: 30px">
            <div id="chart1"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div id="chart2"></div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ url('vertical/assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
    <script src="{{ url('vertical/assets/plugins/datetimepicker/js/legacy.js') }}"></script>
    <script src="{{ url('vertical/assets/plugins/datetimepicker/js/picker.js') }}"></script>
    <script src="{{ url('vertical/assets/plugins/datetimepicker/js/picker.date.js') }}"></script>
    <script>
        $('#date_history').data('value', new Date('{{ app('request')->input('date') }}'))
        $('.datepicker').pickadate({
            selectMonths: true,
            selectYears: true,
            format: 'd mmmm yyyy',
            formatSubmit: 'yyyy-mm-dd',
            hiddenName: true
        })
    </script>
    <script>
        function drawLineChartHistory(seriesData, catData, yAxisTitle) {
            var options = {
                series: [{
                    name: yAxisTitle,
                    data: seriesData
                }],
                chart: {
                    foreColor: '#9ba7b2',
                    height: 360,
                    type: 'line',
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: true
                    },
                    dropShadow: {
                        enabled: true,
                        top: 3,
                        left: 14,
                        blur: 4,
                        opacity: 0.10,
                    }
                },
                stroke: {
                    width: 5,
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'datetime',
                    categories: catData,
                },
                title: {
                    text: 'Riwayat Tekanan',
                    align: 'center',
                    style: {
                        fontSize: "16px",
                        color: '#666'
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        gradientToColors: ['#0A73BA'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    },
                },
                tooltip: {
                    x: {
                        format: 'dd MMM hh:mm',
                        formatter: undefined,
                    },
                    y: {
                        formatter: (value) => {
                            return value + "Atm"
                        },
                    },
                },
                markers: {
                    size: 4,
                    colors: ["#0A73BA"],
                    strokeColors: "#fff",
                    strokeWidth: 2,
                    hover: {
                        size: 7,
                    }
                },
                colors: ["#0A73BA"],
                yaxis: {
                    title: {
                        text: yAxisTitle,
                    },
                }
            };
            var chart = new ApexCharts(document.querySelector("#chart1"), options);
            chart.render();
        }

        function drawWorkHourChart(dataSet) {
            var options = {
                series: [
                    {
                        data: dataSet
                    }
                ],
                chart: {
                    height: 150,
                    type: 'rangeBar'
                },
                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },
                xaxis: {
                    type: 'datetime'
                },
                title: {
                    text: 'Riwayat Jam Jalan',
                    align: 'center',
                    style: {
                        fontSize: "16px",
                        color: '#666'
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd MMM hh:mm',
                        formatter: undefined,
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#chart2"), options);
            chart.render();
        }

        $(document).ready(function () {
            @php
                $arrTemp = [];
                $arrCat = [];
            foreach ($data as $item) {
                $arrTemp[] = random_int(10.00, 25.00);//$item->TEMPERATURE;
                $arrCat[] = $item->TANGGAL.' GMT';
            }
            @endphp
            drawLineChartHistory(@json($arrTemp, JSON_NUMERIC_CHECK), @json($arrCat), "Tekanan")
            fetch('{{ url("api/history/work-hour/$deviceId?date=").app('request')->input('date') }}').then(function (response) {
                return response.json()
            }).then(function (data) {
                const dataSet = []
                data.forEach((element) => {
                    let itemData = {
                        x: "Jam Jalan",
                        y: [
                            new Date(element.TIME_START_FULL + ' GMT').getTime(),
                            new Date(element.TIME_END_FULL + ' GMT').getTime()
                        ]
                    }
                    dataSet.push(itemData)
                })
                if (dataSet.length > 0) {
                    drawWorkHourChart(dataSet)
                }
            })
        })
    </script>
    <script>
        let selectedDate = $('[name="date_history"]').val()
        let selectedDeviceId = '{{ $deviceId }}'
        $('#date_history').change(function () {
            selectedDate = $('[name="date_history"]').val()
            refreshData()
        })
        $('#selectDeviceId').change(function () {
            selectedDeviceId = $(this).val()
            refreshData()
        })

        function refreshData() {
            window.location.href = "{{ url('/ptpn/device') }}/"+selectedDeviceId+"?date="+selectedDate;
        }
    </script>
@endsection

