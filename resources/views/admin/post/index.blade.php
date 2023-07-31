@extends('template.main')

@section('content')
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Postingan</h3>
            </div>
            <div class="card-body">
                @can('posts.create')
                    <div class="row mb-2">
                        <button id="add" class="btn btn-sm btn-outline-primary ">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button>
                    </div>
                @endcan
                <table id="tbl_post" class="table table-bordered" width="100%">
                    <thead class="text-center">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Judul</th>
                            <th width="15%">Slug</th>
                            <th width="15%">Deskripsi</th>
                            <th width="25%">Gambar</th>
                            <th width="15%">Kategori</th>
                            <th width="10%">Aksi</th>
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
                        <form id="formAdd" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Judul</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Masukkan Judul">
                                <small class="text-danger" id="title_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="jobs">Kategori</label>
                                <select name="category" id="category" class="form-control">
                                    @foreach ($category as $a)
                                        <option value="{{ $a->id }}">{{ $a->category }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger" id="jobs_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea name="description" id="description" cols="30" rows="5" class="form-control"
                                    placeholder="Masukkan deskripsi"></textarea>
                                {{-- <input type="text" class="form-control" id="description" name="description"
                                    placeholder="Masukkan Judul"> --}}
                                <small class="text-danger" id="description_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="image">Gambar</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <small class="text-danger" id="image_error"></small>
                            </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
                        <h4 class="modal-title">Edit Postingan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- form start -->
                        <form id="formEdit" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title_edit">Judul</label>
                                <input type="text" class="form-control" id="title_edit" name="title"
                                    placeholder="Masukkan Judul">
                                <input type="hidden" name="post_id" id="post_id">
                                <input type="hidden" name="old_image" id="old_image">
                                <small class="text-danger" id="title_edit_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="jobs">Kategori</label>
                                <select name="category" id="category_edit" class="form-control">
                                    @foreach ($category as $a)
                                        <option value="{{ $a->id }}">{{ $a->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description_edit">Deskripsi</label>
                                <textarea name="description" id="description_edit" cols="30" rows="5" class="form-control"
                                    placeholder="Masukkan deskripsi"></textarea>
                                <small class="text-danger" id="description_error"></small>
                            </div>
                            <div class="form-group">
                                <label for="image">Gambar</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image_edit" name="image">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <small class="text-danger" id="image_edit_error"></small>
                                <img src="" class="img-preview img-fluid mt-5 mb-3 col-sm-5">
                            </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
            $('#tbl_post').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ url()->current() }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: "text-center",
                        orderable: false,
                        searchable: false

                    },
                    {
                        data: 'title',
                        name: 'posts.title'
                    },
                    {
                        data: 'slug',
                        name: 'posts.slug'
                    },
                    {
                        data: 'description',
                        name: 'posts.description'
                    },
                    {
                        data: 'image',
                        orderable: false,
                        sortable: false,
                        className: "text-center"
                    },
                    {
                        data: 'category',
                        name: 'categories.category'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        sortable: false
                    },
                ]
            });
        });

        // $('.custom-file-label').change(function() {
        //     var i = $(this).prev('label').clone();
        //     var file = $('.custom-file-label')[0].files[0].name;
        //     $(this).prev('label').text(file);
        // });

        $('#add').click(function(e) {
            e.preventDefault();
            $('#title').val();
            $('#category').val();
            $('#description').val();
            $('#image').val();
            $('#modal-add').modal('show');
        });


        $('#formAdd').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('admin.post.store') }}",
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses !',
                        text: `${response.message}`,
                    });

                    $('#title').val('');
                    $('.custom-file-label').html('Choose file');

                    $('#modal-add').modal('hide');

                    $('#tbl_post').DataTable().ajax.reload();
                },
                error: function(error) {
                    if (error.responseJSON.title) {
                        $('#title_error').html(error.responseJSON.title[0]);
                    } else {
                        $('#title_error').html('');
                    }
                    if (error.responseJSON.description) {
                        $('#description_error').html(error.responseJSON.description[0]);
                    } else {
                        $('#description_error').html('');
                    }
                    if (error.responseJSON.image) {
                        $('#image_error').html(error.responseJSON.image[0]);
                    } else {
                        $('#image_error').html('');
                    }

                }
            });
        });


        //edit
        $('#tbl_post').on('click', '.edit', function() {
            var id = $(this).data('id');

            $.ajax({
                type: "GET",
                url: "{{ route('admin.post.show') }}",
                cache: false,
                data: ({
                    id
                }),
                dataType: "json",
                success: function(response) {
                    //fill data to form
                    $('#post_id').val(response.data.id);
                    $('#title_edit').val(response.data.title);
                    $('#old_image').val(response.data.image);
                    $('.img-preview').attr('src', "{{ asset('storage/post-image') }}" + '/' + response
                        .data
                        .image);
                    $('#category_edit').val(response.data.category_id);
                    $('#description_edit').val(response.data.description);
                    $('#jobs_edit').val(response.data.jobs);

                    // open modal
                    $('#modal-edit').modal('show');
                }
            });
        });


        // proses update
        $('#formEdit').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "{{ route('admin.post.update') }}",
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses !',
                        text: `${response.message}`,
                    });

                    $('#title').val('');
                    $('.custom-file-label').html('Choose file');

                    $('#modal-edit').modal('hide');

                    $('#tbl_post').DataTable().ajax.reload();
                    $('#title_edit').val();
                    $('#category_edit').val();
                    $('#description_edit').val();
                    $('#image').val();
                    $('#old_image').val();
                },
                error: function(error) {
                    if (error.responseJSON.title_edit) {
                        $('#title_edit_error').html(error.responseJSON.title_edit[0]);
                    } else {
                        $('#title_edit_error').html('');
                    }
                    if (error.responseJSON.image_edit) {
                        $('#image_edit_error').html(error.responseJSON.image_edit[0]);
                    } else {
                        $('#image_edit_error').html('');
                    }

                }
            });


        });


        //delete 
        $('#tbl_post').on('click', '.delete', function() {

            var id = $(this).data('id');
            var token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: "Data post akan dihapus",
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
                        url: "{{ route('admin.post.delete') }}",
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
                            $('#tbl_post').DataTable().ajax.reload();
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

    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
@endpush
