@extends('template.main')

@section('content')
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Kategori</h3>
            </div>
            <div class="card-body">
                @can('categories.create')
                    <div class="row mb-2">
                        <button id="add" class="btn btn-sm btn-outline-primary ">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button>
                    </div>
                @endcan
                <table id="tbl_kategori" class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Action</th>
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
                        <h4 class="modal-title">Tambah Kategori</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- form start -->
                        <form>

                            <div class="form-group">
                                <label for="category">Kategori</label>
                                <input type="text" class="form-control" id="category" name="category"
                                    placeholder="Masukkan kategori">
                                <small class="text-danger" id="category_error"></small>
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
                                <label for="category">Kategori</label>
                                <input type="hidden" id="id_kategori" name="id_kategori">
                                <input type="text" class="form-control" id="category_edit" name="category_edit"
                                    placeholder="Masukkan kategori">
                                <small class="text-danger" id="category_edit_error"></small>
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
        $(document).ready(function() {
            $('#tbl_kategori').DataTable({
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
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },
                    {
                        data: 'action'
                    },
                ]
            });
        });

        $('#add').click(function(e) {
            e.preventDefault();
            $('#category').val();
            $('#modal-add').modal('show');
        });

        function save() {
            // define variabel
            var category = $('#category').val();
            var token = $("meta[name='csrf-token']").attr("content");

            // ajax process
            $.ajax({
                type: "POST",
                url: "{{ route('admin.category.store') }}",
                data: ({
                    category: category,
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
                    $('#category_error').html('');

                    //clear form
                    $('#category').val('');

                    $('#modal-add').modal('hide');

                    $('#tbl_kategori').DataTable().ajax.reload();
                },
                error: function(error) {
                    if (error.responseJSON.category) {
                        $('#category_error').html(error.responseJSON.category[0]);
                    } else {
                        $('#category_error').html('');
                    }
                }
            });
        }

        //edit
        $('#tbl_kategori').on('click', '.edit', function() {
            var id = $(this).data('id');

            $.ajax({
                type: "GET",
                url: "{{ route('admin.category.index') }}/" + id,
                cache: false,
                // dataType: "json",
                success: function(response) {

                    // alert('hi');
                    //fill data to form
                    $('#id_kategori').val(response.data.id);
                    $('#category_edit').val(response.data.category);

                    // open modal
                    $('#modal-edit').modal('show');
                }
            });
        });


        // proses update
        function update() {
            var id = $('#id_kategori').val();
            var category = $('#category_edit').val();
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                type: "PUT",
                url: "{{ route('admin.category.index') }}/" + id,
                data: ({
                    category: category,
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
                    $('#category_edit_error').html('');

                    //clear form
                    $('#category_edit').val('');

                    $('#modal-edit').modal('hide');

                    $('#tbl_kategori').DataTable().ajax.reload();
                },
                error: function(error) {
                    if (error.responseJSON.category[0]) {
                        $('#category_edit_error').html(error.responseJSON.category[0]);
                    }
                }
            });
        }


        //delete 
        $('#tbl_kategori').on('click', '.delete', function() {

            var id = $(this).data('id');
            var token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: "Data Kategori akan dihapus",
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
                        url: "{{ route('admin.category.index') }}/" + id,
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
                            $('#tbl_kategori').DataTable().ajax.reload();
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
    </script>
@endpush
