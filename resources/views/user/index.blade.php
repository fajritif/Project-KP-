@extends('layouts.app')

@push('page_css')
	<link href="{{ url('vertical') }}/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endpush

@section('content')
    <h6 class="mb-0 text-uppercase">DAFTAR USER</h6>
    <hr>
    <div class="card">
        <div class="card-body">
            <table id="users" class="table table-striped table-bordered dataTable" role="grid">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NIK SAP</th>
                        <th>PTPN</th>
                        <th>UNIT</th>
                        <th>ROLE</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal USER-->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah / Ubah User</h5>
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

                    <form action="" id="edit-user-form" method="post" action="{{ url('admin/user') }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="nik_sap" class="col-sm-3 col-form-label">NIK SAP</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="nik_sap" placeholder="" placeholder="1" id='nik_sap'>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="roleid" class="col-sm-3 col-form-label">ROLE</label>
                            <div class="col-sm-9">
                                <select class="form-select" aria-label="Default select example" name="roleid" id="roleid">
                                    <option value="">-- Pilih ROLE --</option>
                                    <option >ADMIN_UNIT</option>
                                    <option >ADMIN_ANPER</option>
                                    <option >ADMIN_HOLDING</option>
                                    <option >VIEWER_UNIT</option>
                                    <option >VIEWER_ANPER</option>
                                    <option >VIEWER_HOLDING</option>
                                </select>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" form="edit-user-form" id="button-simpan-user">Simpan</button>
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
            let nomor = ''
            let datatable_users = [];


            var t2 = $('#users').DataTable( {
                data: [],
                buttons: {

                },
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                },{
                    "searchable": false,
                    "orderable": false,
                    "targets": 5
                } ],
                "order": [[ 1, 'asc' ]]
            } );

            t2.on( 'order.dt search.dt', function () {
                t2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

            t2.on( 'draw', function () {
                // hindari event duplicate dengana unbind
                $('.delete_button, .edit_button').unbind('click');
                $('.delete_button').click(function(){
                    $.post('{{ url("admin/user") }}', {'_method':'delete','_token':'{{ csrf_token() }}', 'nik_sap':$(this).data('nik-sap')}, function(data){

                    }, 'json');
                    t2.row($(this).parents('tr')).remove().draw();
                });
                $('.edit_button').click(function(){
                    $('#modal_alert').hide();
                    $('#nik_sap').val($(this).data('nik-sap'));
                    $('#roleid').val($(this).data('roleid'));
                });
            } )

            $('#users_filter').append(
                ' <a href="javascript:;"  class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#userModal" id="user_baru_button"><i class="bx bxs-plus-square"></i>Tambah User</a>'
            );


            $(document).ready(function(){

                $.get('{{ url("admin/user") }}', {}, function(data){

                    data.forEach(user => {
                        datatable_users.push([
                            0,
                            user.NIK_SAP,
                            user.PTPN,
                            user.PSA,
                            user.ROLEID,
                            '<a href="#" class="text-warning edit_button" data-nik-sap="'+user.NIK_SAP+'" data-roleid="'+user.ROLEID+'"  data-bs-toggle="modal" data-bs-target="#userModal"><i class="bx bxs-edit"></i></a>&nbsp;'+
                            '<a href="#" class="text-danger delete_button" data-nik-sap="'+user.NIK_SAP+'"><i class="bx bxs-trash"></i></a>'
                        ]);
                    });
                    t2.rows.add( datatable_users ).draw();

                }, 'json');

                $('#user_baru_button').click(function(){

                    $('#modal_alert').hide();
                    $('#nik_sap').val('');
                    $('#roleid').val($("#roleid option:first").val());
                });

                $('#edit-user-form').submit(function(e){
                    e.preventDefault();
                    $.post($(this).attr('action'), $(this).serialize(), function(my_response){
                        if(my_response.status == true){

                            if(my_response.action == 'add_row'){
                                t2.row.add([
                                    0,
                                    my_response.data.NIK_SAP,
                                    my_response.data.PTPN,
                                    my_response.data.PSA,
                                    my_response.data.ROLEID,
                                    '<a href="#" class="text-warning edit_button" data-nik-sap="'+my_response.data.NIK_SAP+'" data-roleid="'+my_response.data.ROLEID+'" data-bs-toggle="modal" data-bs-target="#userModal"><i class="bx bxs-edit"></i></a>&nbsp;'+
                                    '<a href="#" class="text-danger delete_button" data-nik-sap="'+my_response.data.NIK_SAP+'"><i class="bx bxs-trash"></i></a>'
                                ]).draw(false);
                            }else if(my_response.action == 'update_row'){
                                myrow = $("[data-nik-sap='"+my_response.data.NIK_SAP+"']").parents('tr');
                                t2.row(myrow).remove().draw();
                                t2.row.add([
                                    0,
                                    my_response.data.NIK_SAP,
                                    my_response.data.PTPN,
                                    my_response.data.PSA,
                                    my_response.data.ROLEID,
                                    '<a href="#" class="text-warning edit_button" data-nik-sap="'+my_response.data.NIK_SAP+'" data-roleid="'+my_response.data.ROLEID+'" data-bs-toggle="modal" data-bs-target="#userModal"><i class="bx bxs-edit"></i></a>&nbsp;'+
                                    '<a href="#" class="text-danger delete_button" data-nik-sap="'+my_response.data.NIK_SAP+'"><i class="bx bxs-trash"></i></a>'
                                ]).draw(false);
                            }else if(my_response.action == 'remove_row'){
                                myrow = $("[data-nik-sap='"+my_response.data.NIK_SAP+"']").parents('tr');
                                t2.row(myrow).remove().draw();
                            }

                            $("#userModal").modal('toggle');

                        }else{
                            $('#modal_message').text(my_response.message);
                            $('#modal_alert').show();

                        }

                    }, 'json');
                });

            });

        </script>
@endpush
