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
                        <h3 class="card-title">MASTER DATA PESANAN</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">


                        <table id="items-table" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Customer_Name</th>
                                    <th>Tanggal_Pesan</th>
                                    <th>Status</th>
                                    <th>Tanggal_Mulai</th>
                                    <th>Tanggal_Jatuh Tempo</th>
                                    <th>Total_Price</th>
                                    <th>Down_Payment</th>
                                    <th>Belum_Dibayar</th>
                                    <th>Penalty</th>
                                    <th>Status_Pembayaran_Penuh</th>
                                    <th>Notes</th>
                                    <th>Tanggal_Pengambilan</th>
                                    <th>User_Pengambilan</th>
                                    <th>User_Pesan</th>
                                    <th>User_Verifikator</th>
                                    <th>User_Edit</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Items</th>
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
                ajax: '{!! route('getAllOrders') !!}', // Pastikan route ini benar
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, // Ini untuk nomor urut
                    {
                        data: 'customer_name',

                    },
                    {
                        data: 'order_date',

                    },

                    {
                        data: 'status',

                    },
                    {
                        data: 'start_date',

                    },
                    {
                        data: 'end_date',

                    },
                    {
                        data: 'total_price',
                        render: function(data) {
                            return formattedNumber(data);
                        }

                    },
                    {
                        data: 'down_payment',
                        render: function(data) {
                            return formattedNumber(data);
                        }

                    },
                    {
                        data: 'belum_dibayar',
                        render: function(data) {
                            return formattedNumber(data);
                        }

                    },
                    {
                        data: 'penalty',
                        render: function(data) {
                            return formattedNumber(data);
                        }

                    },
                    {
                        data: 'full_payment_status',

                    },
                    {
                        data: 'notes',

                    },
                    {
                        data: 'tanggal_pengambilan',

                    },
                    {
                        data: 'user_ambil',
                    },
                    {
                        data: 'user_pesan',
                    },
                    {
                        data: 'user_verify',

                    },
                    {
                        data: 'user_update',

                    },
                    {
                        data: 'created_at',

                    },
                    {
                        data: 'updated_at',

                    },
                    {
                        data: null,
                        title: '#',
                        render: function(data, type, row) {
                            return `<button type="button" class="detail-btn btn btn-info btn-block btn-sm" data-id="${row.id}">
                                        <i class="fas fa-info-circle"></i>
                                    </button>`;
                        }
                    }

                ],
                order: [
                    [18, 'desc']
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
            });
        });
        $('#items-table').on('click', '.detail-btn', async function() {
            const idOrder = $(this).data('id');

            await $.ajax({
                url: "{{ route('getOrderDetailById') }}",
                data: {
                    id: idOrder
                },
                type: 'POST',
                success: function(data) {
                    // console.log(data);
                    $('#modal_title').text(idOrder);
                    $('#tableBodyOrderDetail').html('')
                    $('#modal-items').modal('show');

                    data.forEach(element => {
                        $('#tableBodyOrderDetail').append(`
                            <tr>
                                <td>${element.name}</td>
                                <td>${element.ukuran||''}</td>
                                <td>${element.quantity}</td>
                                <td>${formattedNumber(element.price)}</td> 
                                <td>${formattedNumber(element.discount)}</td>
                                <td>${formattedNumber(element.price_after_discount)}</td>
                                <td>${formattedNumber(element.total_price)}</td>
                            </tr>
                        `);
                    });

                },
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
                }
            })


        });
    </script>
@endsection



@section('modals')
    <div class="modal fade" id="modal-items">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Informasi Pesanan <span id="modal_title"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="orderDetailsTable">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Ukuran</th>
                                    <th>Quantity</th>
                                    <th>Harga</th>
                                    <th>Diskon %</th>
                                    <th>Harga Setelah diskon</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyOrderDetail">
                                <!-- Data order details akan di-append di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
