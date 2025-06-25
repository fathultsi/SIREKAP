@extends('layouts.main')

@section('DataTables')
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endsection

@section('content')
    <style>
        #items-tablee {
            width: 100% !important;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">MASTER DATA PEMBUKUAN</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">


                        <table id="items-table" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Description</th>
                                    <th>DB_KR</th>
                                    <th>Amount</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts_DataTables')
    <script src="{{ asset('templates/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    {{-- <script src="{{ asset('templates/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script> --}}
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#items-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('getAllPembukuan') !!}', // Pastikan route ini benar
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, // Ini untuk nomor urut
                    {
                        data: 'description',

                    },
                    {
                        data: 'db_kr',

                    },

                    {
                        data: 'amount',
                        render: function(data) {
                            return formattedNumber(data);
                        }

                    },
                    {
                        data: 'created_at',

                    },
                    {
                        data: 'updated_at',

                    }
                ],
                error: function(xhr, status, error) {
                    // Mengatasi kesalahan validasi
                    if (xhr.responseJSON) {
                        // Error JSON dari Laravel, biasanya validasi error
                        const errorMessage = xhr.responseJSON.message;
                        swal.fire('Error', errorMessage, 'error');
                    } else {
                        // Error plain text atau HTML (error tidak dalam format JSON)
                        swal.fire('Error', xhr.responseText, 'error');
                    }
                },
                order: [
                    [4, 'desc']
                ],
            });
        });
    </script>
@endsection
