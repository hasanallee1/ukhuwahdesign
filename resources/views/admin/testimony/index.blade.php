@extends('template.main')

@section('content')
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Testimoni</h3>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <button id="add" class="btn btn-sm btn-outline-primary ">
                        <i class="fa fa-plus"></i> Tambah Data
                    </button>
                </div>
                <table id="tbl_testimoni" class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Nama</th>
                            <th width="25%">Pekerjaan</th>
                            <th width="40%">Testimoni</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            {{-- <div class="card-footer">
        Footer
      </div> --}}
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

        {{-- modal add --}}
        <div class="modal fade" id="modal-add">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Testimoni</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- form start -->
                        <form>

                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Masukkan Nama">
                                <small class="text-danger" id="name_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="jobs">Pekerjaan</label>
                                <input type="text" class="form-control" id="jobs" name="jobs"
                                    placeholder="Masukkan Pekerjaan">
                                <small class="text-danger" id="jobs_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="testimony">Testimoni</label>
                                <textarea name="testimony" id="testimony" class="form-control" cols="30" rows="3"
                                    placeholder="Masukkan Testimoi">

                                </textarea>
                                <small class="text-danger" id="testimony_error"></small>
                            </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" onclick="save()" class="btn btn-primary">Save changes</button>
                    </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        {{-- modal edit --}}
        <div class="modal fade" id="modal-edit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Kategori</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- form start -->
                        <form>

                            <div class="form-group">
                                <label for="name_edit">Nama</label>
                                <input type="text" class="form-control" id="name_edit" name="name_edit"
                                    placeholder="Masukkan Nama">
                                <input type="hidden" id="id_testimony" name="id_testimony">
                                <small class="text-danger" id="name_edit_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="jobs_edit">Pekerjaan</label>
                                <input type="text" class="form-control" id="jobs_edit" name="jobs_edit"
                                    placeholder="Masukkan Pekerjaan">
                                <small class="text-danger" id="jobs_edit_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="testimony_edit">Testimoni</label>
                                <textarea name="testimony_edit" id="testimony_edit" class="form-control" cols="30" rows="3"
                                    placeholder="Masukkan Testimoni"></textarea>

                                <small class="text-danger" id="testimony_edit_error"></small>
                            </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" onclick="update()" class="btn btn-primary">Update</button>
                    </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    </section>
@endsection

@push('scripts')
    <script>
        var token = $("meta[name='csrf-token']").attr("content");

        $(document).ready(function() {
            $('#tbl_testimoni').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ url()->current() }}',
                columns: [{
                        data: null,
                        sortable: false,
                        className: "text-center",
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'jobs',
                        name: 'jobs'
                    },
                    {
                        data: 'testimony',
                        name: 'testimony'
                    },
                    {
                        data: 'action'
                    },
                ]
            });
        });

        $('#add').click(function(e) {
            e.preventDefault();
            $('#name').val();
            $('#jobs').val();
            $('#testimony').val();
            $('#modal-add').modal('show');
        });

        function save() {
            // define variabel
            var name = $('#name').val();
            var jobs = $('#jobs').val();
            var testimony = $('#testimony').val();

            // ajax process
            $.ajax({
                type: "POST",
                url: "{{ route('admin.testimony.store') }}",
                data: ({
                    name: name,
                    jobs: jobs,
                    testimony: testimony,
                    _token: token
                }),
                dataType: "json",
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses !',
                        text: `${response.message}`,
                    });

                    //remove error response 
                    $('#name_error').html('');
                    $('#jobs_error').html('');
                    $('#testimony_error').html('');

                    //clear form
                    $('#name').val();
                    $('#jobs').val();
                    $('#testimony').val();

                    $('#modal-add').modal('hide');

                    $('#tbl_testimoni').DataTable().ajax.reload();
                },
                error: function(error) {
                    if (error.responseJSON.name) {
                        $('#name_error').html(error.responseJSON.name[0]);
                    } else {
                        $('#name_error').html('');
                    }
                    if (error.responseJSON.jobs) {
                        $('#jobs_error').html(error.responseJSON.jobs[0]);
                    } else {
                        $('#jobs_error').html('');
                    }
                    if (error.responseJSON.testimony) {
                        $('#testimony_error').html(error.responseJSON.testimony[0]);
                    } else {
                        $('#testimony_error').html('');
                    }
                }
            });
        }

        //edit
        $('#tbl_testimoni').on('click', '.edit', function() {
            var id = $(this).data('id');

            $.ajax({
                type: "GET",
                url: "{{ route('admin.testimony.index') }}/" + id,
                cache: false,
                dataType: "json",
                success: function(response) {
                    //fill data to form
                    $('#id_testimony').val(response.data.id);
                    $('#name_edit').val(response.data.name);
                    $('#jobs_edit').val(response.data.jobs);
                    $('#testimony_edit').val(response.data.testimony);

                    // open modal
                    $('#modal-edit').modal('show');
                }
            });
        });


        // proses update
        function update() {
            var id = $('#id_testimony').val();
            var name = $('#name_edit').val();
            var jobs = $('#jobs_edit').val();
            var testimony = $('#testimony_edit').val();


            $.ajax({
                type: "PUT",
                url: "{{ route('admin.testimony.index') }}/" + id,
                data: ({
                    name: name,
                    jobs: jobs,
                    testimony: testimony,
                    _token: token
                }),
                dataType: "json",
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses !',
                        text: `${response.message}`,
                    });

                    //clear form
                    $('#name_edit').val('');
                    $('#jobs_edit').val('');
                    $('#testimony_edit').val('');

                    $('#modal-edit').modal('hide');

                    $('#tbl_testimoni').DataTable().ajax.reload();
                },
                error: function(error) {
                    if (error.responseJSON.name) {
                        $('#name_edit_error').html(error.responseJSON.name[0]);
                    } else {
                        $('#name_edit_error').html('');
                    }
                    if (error.responseJSON.jobs) {
                        $('#jobs_edit_error').html(error.responseJSON.jobs[0]);
                    } else {
                        $('#jobs_edit_error').html('');
                    }
                    if (error.responseJSON.testimony) {
                        $('#testimony_edit_error').html(error.responseJSON.testimony[0]);
                    } else {
                        $('#testimony_edit_error').html('');
                    }
                }
            });
        }


        //delete 
        $('#tbl_testimoni').on('click', '.delete', function() {

            var id = $(this).data('id');
            var token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: "Data testimoni akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('admin.testimony.index') }}/" + id,
                        data: ({
                            id: id,
                            _token: token
                        }),
                        dataType: "JSON",
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                `${response.message}`,
                                'success'
                            )

                            //if success reload ajax table
                            $('#tbl_testimoni').DataTable().ajax.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            })
                        }
                    });

                }
            })

        });


        // function delete() {
        //     // alert(id);
        // }
    </script>
@endpush
