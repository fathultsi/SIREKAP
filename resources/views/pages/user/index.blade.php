@extends('layouts.main')

@section('DataTables')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('templates/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

            </div>

        </div>



        <div class="row">
            <div class="col-12 ">
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill"
                                    href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home"
                                    aria-selected="true">Daftar Pengguna</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill"
                                    href="#custom-tabs-three-profile" role="tab"
                                    aria-controls="custom-tabs-three-profile" aria-selected="false">Tambah Pengguna</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel"
                                aria-labelledby="custom-tabs-three-home-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Data Master Pengguna</h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="user-table"
                                            class="table table-hover table-bordered table-striped table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Pengguna</th>
                                                    <th>email</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Waktu input</th>
                                                    <th>Terakhir diedit</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel"
                                aria-labelledby="custom-tabs-three-profile-tab">
                                <form id="addForm">
                                    <div class="form-group">
                                        <label for="addname">Nama Pengguna</label>
                                        <input type="text" class="form-control" id="addname" name="name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="addEmail">Email</label>
                                        <input type="email" class="form-control" id="addEmail" name="email" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="addPhone">Role</label>
                                        <select name="role" id="addRole" class="form-control">
                                            <option default value="karyawan">Karyawan</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="addPassword">Password</label>
                                        <input type="password" class="form-control" id="addPassword" name="password"
                                            required>
                                    </div>

                                    <button type="submit" class="btn btn-primary" id="add_btn">Simpan</button>

                                </form>
                            </div>

                        </div>
                    </div>
                    <!-- /.card -->
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
    <script src="{{ asset('templates/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('templates/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var tableuser, references = [];
            tableuser = $('#user-table').DataTable({
                processing: true,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'role'
                    },
                    {
                        data: 'is_active',
                        render: function(data, type, row) {
                            if (data == 1) {
                                return `<span class="badge bg-success">Aktif</span>`;
                            } else {
                                return `<span class="badge bg-danger">Tidak Aktif</span>`;
                            }
                        }
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'updated_at'
                    },
                    {
                        data: null,
                        title: '#',
                        render: function(data, type, row) {

                            return `
                             <div class="d-flex justify-content-between">
                                <button type="button" class="edit-btn btn btn-outline-warning btn-sm" data-id="${row.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            `;
                        }
                    }
                ],
            });

            function getAlluser() {
                $.ajax({
                    url: "{{ route('getUsers') }}",
                    type: 'GET',
                    success: function(data) {
                        references = data
                        tableuser.clear().rows.add(data).draw();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: ', error);
                        console.error('Details: ', xhr.responseText);
                        swal.fire('Error', xhr.responseText, 'error');
                    }
                });
            }

            getAlluser();


            // Event delegation untuk tombol hapus
            $('#user-table').on('click', '.delete-btn', function() {
                const user_Id = $(this).data('id');
                swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "User ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/users/${user_Id}`,
                            method: 'DELETE',
                            success: function(response) {
                                getAlluser(); // Reload data
                                swal.fire('Success', response.message, 'success');
                            },
                            error: function(xhr, status, error) {
                                swal.fire('Error', xhr.responseText, 'error');
                            }
                        });
                    }



                })
            });
            $('#user-table').on('click', '.edit-btn', function() {
                const user_Id = $(this).data('id');

                const kate_ = references.find(kate_ => kate_.id == user_Id);

                $('#user_Id').val(kate_.id);
                $('#editName').val(kate_.name);
                $('#editEmail').val(kate_.email);
                $('#editRole').val(kate_.role).trigger('change');
                $('#editIs_active').val(kate_.is_active).trigger('change');


                $('#modal_title').text(kate_.name);

                $('#modal-user').modal('show');
            });


            $('#addForm').on('submit', async function(event) {
                event.preventDefault();

                const formData = $(this).serialize();

                $('#add_btn').prop('disabled', true);
                $('#add_btn').html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );

                await $.ajax({
                    url: '/users',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        swal.fire('Success', response.message, 'success');
                        // Update UI atau reload data jika perlu
                        $('#addForm').trigger('reset');
                        getAlluser(); // Reload data
                    },
                    error: function(xhr) {
                        // Mengatasi kesalahan validasi
                        // Jika terjadi error
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON
                                .errors; // Ambil error dari response JSON
                            displayErrors(errors); // Fungsi untuk menampilkan error di UI
                        } else {
                            swal.fire('Error', xhr.responseText, 'error');
                        }

                        $('#add_btn').attr('disabled', false);
                        $('#add_btn').html('Simpan');
                    }
                });

                $('#add_btn').attr('disabled', false);
                $('#add_btn').html('Simpan');
            });
            $('#editForm').on('submit', async function(event) {
                event.preventDefault();

                const user_Id = $('#user_Id').val();
                const formData = $(this).serialize();

                $('#editBtn').prop('disabled', true);
                $('#editBtn').html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );

                await $.ajax({
                    url: `/users/${user_Id}`,
                    method: 'PUT',
                    data: formData,
                    success: function(response) {

                        console.log(response);
                        swal.fire('Success', response.message, 'success');
                        // Update UI atau reload data jika perlu
                        $('#modal-user').modal('hide');
                        getAlluser(); // Reload data
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: ', error);
                        console.error('Details: ', xhr.responseText);
                        swal.fire('Error', xhr.responseText, 'error');
                        $('#editBtn').attr('disabled', false);
                        $('#editBtn').html('Simpan');
                    }
                });

                $('#editBtn').attr('disabled', false);
                $('#editBtn').html('Simpan');
            });





        });

        function displayErrors(errors) {
            // Menyusun pesan error untuk ditampilkan dalam SweetAlert
            var errorMessages = '';

            // Loop untuk setiap field error dan gabungkan pesan error
            $.each(errors, function(field, messages) {
                var errorMessage = messages.join(', '); // Gabungkan pesan error jika ada lebih dari satu
                errorMessages += '<b>' + field.charAt(0).toUpperCase() + field.slice(1) + ':</b> ' + errorMessage +
                    '<br>';
            });

            // Menampilkan pesan error dalam SweetAlert
            swal.fire({
                title: 'Terjadi Kesalahan',
                html: errorMessages, // Menampilkan pesan error
                icon: 'error',
                confirmButtonText: 'Tutup'
            });
        }
    </script>
@endsection

@section('modals')
    <div class="modal fade" id="modal-user">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Pengguna <span id="modal_title"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm">
                    <div class="modal-body">
                        <input type="hidden" id="user_Id" name="id">
                        <div class="form-group">
                            <label for="editName">Nama Pengguna</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="editRole">Role</label>
                            <select name="role" id="editRole" class="form-control">
                                <option value="karyawan">Karyawan</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editIs_active">Status</label>
                            <select name="is_active" id="editIs_active" class="form-control">
                                <option value="0">Tidak Aktif</option>
                                <option value="1">Aktif</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editPassword">Ubah Password</label>
                            <input type="password" class="form-control" id="editPassword" name="password">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="editBtn">Simpan</button>
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
