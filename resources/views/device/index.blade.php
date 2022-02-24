@extends('layouts.app')

@push('page_css')
	<link href="{{ url('vertical') }}/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endpush

@section('content')
    <h6 class="mb-0 text-uppercase">DAFTAR DEVICE</h6>
    <hr>
    <div class="card">
        <div class="card-body">

            <table id="devices" class="table table-striped table-bordered dataTable" role="grid">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>KODE</th>
                        <th>PTPN</th>
                        <th>PKS</th>
                        <th>STASIUN</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal DEVICE-->
    <div class="modal fade" id="editDeviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Device Baru / Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2" id="modal_alert" style="display:none;">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-dark"><i class="bx bx-info-circle"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-dark"></h6>
                                <div class="text-dark" id="modal_message"></div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form action="" id="edit-device-form" method="post">
                        @csrf

                        <div class="row mb-3">
                            <label for="company" class="col-sm-3 col-form-label">PTPN</label>
                            <div class="col-sm-9">
                                
                                @if(auth()->user()->ROLEID == 'ADMIN_HOLDING')
                                    <select class="form-select" aria-label="Default select example" name="company" id="company">
                                        <option selected="" value="">-- Pilih PTPN --</option>
                                    </select>
                                @else
                                    <input type="text" class="form-control" id="company" name="company" value="{{ auth()->user()->PTPN }}" readonly>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="pks" class="col-sm-3 col-form-label">PKS</label>
                            <div class="col-sm-9">
                                @if(auth()->user()->ROLEID == 'ADMIN_UNIT')
                                <input type="text" class="form-control" id="pks" name="pks" value="{{ auth()->user()->PSA }}" readonly>
                                @else
                                <select class="form-select" aria-label="Default select example" name="pks" id="pks">
                                    <option selected="" value="">-- Pilih PKS --</option>
                                </select>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="stasiun" class="col-sm-3 col-form-label">Stasiun</label>
                            <div class="col-sm-9">
                                <select class="form-select" aria-label="Default select example" name="stasiun" id="stasiun">
                                    <option selected="" value="">-- Pilih Stasiun --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nomor" class="col-sm-3 col-form-label">Nomor</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nomor" placeholder="" placeholder="1" id='nomor'>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="kode_device" class="col-sm-3 col-form-label">Kode Device</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="kode_device" name="kode_device" placeholder="kode" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder=""></textarea>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="edit-device-form" disabled id="button-simpan-device">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
		<script src="{{ url('vertical') }}/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
		<script src="{{ url('vertical') }}/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
        <script>
            Number.prototype.pad = function(size) {
                var s = String(this);
                while (s.length < (size || 2)) {s = "0" + s;}
                return s;
            }

            let active_company = '';
            let company_code = '';
            let pks_code = '';
            let stasiun_code = '';
            let nomor = '';
            let kode_device = '';
            let my_devices = [];
            let datatable_devices = [];

            var t = $('#devices')
                .on( 'init.dt', function () {


                    // this.api().columns().every( function () {
                    // var column = this;
                    // var select = $('<select><option value=""></option></select>')
                    //     .appendTo( $(column.footer()).empty() )
                    //     .on( 'change', function () {
                    //         var val = $.fn.dataTable.util.escapeRegex(
                    //             $(this).val()
                    //         );

                    //         column
                    //             .search( val ? '^'+val+'$' : '', true, false )
                    //             .draw();
                    //     } );

                    //     column.data().unique().sort().each( function ( d, j ) {
                    //         select.append( '<option value="'+d+'">'+d+'</option>' )
                    //     } );
                    // } );

                }
                ).DataTable( {
                data: [],
                buttons: {
                    buttons: [
                        {
                            text: 'Alert',
                            action: function ( e, dt, node, config ) {
                                alert( 'Activated!' );
                                this.disable(); // disable button
                            }
                        }
                    ]
                },
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                },{
                    "searchable": false,
                    "orderable": false,
                    "targets": 6
                } ],
                "order": [[ 1, 'asc' ]]
            } );

            t.on( 'order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            t.on( 'draw', function () {
                // hindari event duplicate dengana unbind
                $('.device-status').unbind('click');
                $('.device-status').click(function(){
                    if($(this).hasClass('text-danger')){
                        new_is_active = 1; // ubah status
                        new_title = 'aktif';
                    }else{
                        new_is_active = 0; // ubah status
                        new_title = 'non aktif';
                    }
                    $.post('{{ url("admin/device") }}/'+$(this).data('kode'), {'_method':'PUT','IS_ACTIVE':new_is_active, '_token':'{{ csrf_token()  }}'}, function(){

                    }, 'json');
                    $(this).toggleClass('text-success text-danger').children().toggleClass('bxs-toggle-right bxs-toggle-left');
                    $(this).attr('title', new_title);
                });
            } )

            // t.on('initComplete', function(){
            //     console.log('aaaaaaaa');
            //     this.api().columns().every( function () {
            //         var column = this;
            //         var select = $('<select><option value=""></option></select>')
            //             .appendTo( $(column.footer()).empty() )
            //             .on( 'change', function () {
            //                 var val = $.fn.dataTable.util.escapeRegex(
            //                     $(this).val()
            //                 );

            //                 column
            //                     .search( val ? '^'+val+'$' : '', true, false )
            //                     .draw();
            //             } );

            //         column.data().unique().sort().each( function ( d, j ) {
            //             select.append( '<option value="'+d+'">'+d+'</option>' )
            //         } );
            //     } );
            // });

            $('#devices_filter').append(
                ' <a href="javascript:;"  class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editDeviceModal" id="device_baru_button"><i class="bx bxs-plus-square"></i>Device Baru</a>'
            );


            $(document).ready(function(){
                
                @if(auth()->user()->ROLEID == 'ADMIN_HOLDING')
                
                    fetch("{{ url('api/company') }}").then((response) => {
                        return response.json();
                    }).then((data) => {
                        data.forEach(company => {
                            $('#company').append("<option value='"+company.KODE+"'>"+company.NAMA+"</option>");
                        });
                    });
                @endif

                @unless(auth()->user()->ROLEID == 'ADMIN_UNIT')
                    fetch("{{ url('api/pks') }}").then((response) => {
                        return response.json();
                    }).then((data) => {
                        data.forEach(pks => {
                            $('#pks').append("<option value='"+pks.KODE+"' data-company='"+pks.COMPANY_CODE+"' class='pks_option'>"+pks.NAMA+"</option>")
                        });

                        filter_pks();
                    });

                @endunless
                
                fetch("{{ url('api/stasiun') }}").then((response) => {
                    return response.json();
                }).then((data) => {
                    data.forEach(stasiun => {
                        $('#stasiun').append("<option value='"+stasiun.KODE+"'>"+stasiun.NAMA+"</option>")
                    });
                });

                fetch("{{ url('api/device') }}").then((response) => {
                    return response.json();
                }).then((json_devices) => {
                    my_devices = json_devices;
                    json_devices.forEach(dev => {

                        if(dev.IS_ACTIVE == 1){
                            status_button = '<a data-kode="'+dev.KODE_DEVICE+'" href="javascript:;" class="text-success device-status" title="aktif"><i class="bx bxs-toggle-right" style="font-size:16pt"></i></a>';
                        }else{
                            status_button = '<a data-kode="'+dev.KODE_DEVICE+'" href="javascript:;" class="text-danger device-status" title="non aktif"><i class="bx bxs-toggle-left" style="font-size:16pt"></i></a>'
                        }

                        datatable_devices.push([
                            0,
                            dev.KODE_DEVICE,
                            dev.COMPANY_CODE,
                            dev.KODE_PKS,
                            dev.KODE_STASIUN,
                            dev.KETERANGAN,
                            // '<a href="javascript:;" class="text-warning"><i class="bx bxs-edit"></i></a>'+
                            status_button
                        ]);
                    });
                    t.rows.add( datatable_devices ).draw();
                });

                $('#company').change(function(){
                    filter_pks();
                });

                $('#company, #pks, #stasiun').change(function(){
                    isi_nomor();
                });

                $('#nomor').change(function(){
                    isi_kode_device();
                });

                $('#device_baru_button').click(function(){

                    $('#modal_alert').hide();
                    
                    @if(auth()->user()->ROLEID == 'ADMIN_HOLDING')
                    $('#company').val($("#company option:first").val());
                    @endif

                    $('#stasiun').val($("#stasiun option:first").val());
                    $('#nomor').val('');
                    $('#kode_device').val('');
                    
                    @unless(auth()->user()->ROLEID == 'ADMIN_UNIT')
                    filter_pks();
                    @endunless

                    $('#edit-device-form').attr('action', "{{ url('admin/device') }}");
                });

                $('#edit-device-form').submit(function(e){
                    e.preventDefault();
                    $.post($(this).attr('action'), $(this).serialize(), function(my_response){
                        if(my_response.status == true){
                            t.row.add([
                                0,
                                my_response.data.KODE_DEVICE,
                                my_response.data.COMPANY_CODE,
                                my_response.data.KODE_PKS,
                                my_response.data.KODE_STASIUN,
                                my_response.data.KETERANGAN,
                                '<a data-kode="'+my_response.data.KODE_DEVICE+'" href="javascript:;" class="text-success device-status" title="aktiv"><i class="bx bxs-toggle-right" style="font-size:16pt"></i></a>'
                            ]).draw(false);

                            my_devices.push(my_response.data);

                            $("#editDeviceModal").modal('toggle');
                        }else{
                            $('#modal_message').text(my_response.message);
                            $('#modal_alert').show();

                        }

                    }, 'json');
                });
            });

            let filter_pks = function(){
                active_company = $('#company').val();

                $('.pks_option').hide();
                $(".pks_option[data-company='"+active_company+"']").show();
                $('#pks').val($("#target option:first").val());
            }

            let isi_nomor = function(){
                company_code = $('#company').val();
                pks_code = $('#pks').val();
                stasiun_code = $('#stasiun').val();

                var prefix = company_code+'-'+pks_code+'-'+stasiun_code+'-';
                //console.log(prefix);


                if(company_code == '' || pks_code == '' || stasiun_code == '' ){
                    $('#nomor').val('');
                }else{

                    filtered_devices = my_devices.filter(function(device,index){
                        return device.kode_prefix == prefix;
                    });
                    //console.log(filtered_devices);

                    // cari nomor maksimal
                    max_nomor = Math.max.apply(Math, filtered_devices.map(function(o) { return o.nomor; }));

                    $('#nomor').val(max_nomor + 1);
                }
                isi_kode_device();

            }

            let isi_kode_device = function(){
                company_code = $('#company').val();
                pks_code = $('#pks').val();
                stasiun_code = $('#stasiun').val();
                nomor = $('#nomor').val();

                if(company_code == '' || pks_code == '' || stasiun_code == '' || nomor ==''){
                    $('#kode_device').val('');
                    $('#button-simpan-device').prop('disabled', true);
                }else{
                    $('#kode_device').val(company_code + '-' + pks_code + '-' + stasiun_code + '-' + (parseInt(nomor)).pad(2));
                    $('#button-simpan-device').prop('disabled',false);
                }

            }

        </script>
@endpush
