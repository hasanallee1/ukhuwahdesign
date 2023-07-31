@extends('template.main')

@section('content')
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah User</h3>
            </div>
            <div class="card-body">
                <form id="form-add">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama">
                        <small class="text-danger" id="name_error"></small>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                        <small class="text-danger" id="email_error"></small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="Password">
                                <small class="text-danger" id="password_error"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Ulangi Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Password">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="roles">Role</label>
                        <div>
                            @foreach ($roles as $role)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" data-id="{{ $role->id }}"
                                        id="check-{{ $role->id }}" value="{{ $role->name }}" name="roles[]">
                                    <label class="form-check-label"
                                        for="check-{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button class="btn btn-sm btn-primary mr-1 btn-submit" type="button" onclick="save()"><i
                            class="fa fa-paper-plane"></i>
                        SIMPAN</button>
                    <button class="btn btn-sm btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i>
                        RESET</button>
                </form>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->





    </section>
@endsection

@push('scripts')
    <script>
        function save() {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.user.store') }}",
                data: $('#form-add').serialize(),
                dataType: "json",
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses !',
                        text: `${response.message}`,
                        timer: 1500
                    }).then(function() {
                        $('#name_error').html('');
                        //clear form
                        $('#form-add')[0].reset();

                        window.location = "{{ route('admin.user.index') }}";
                    });

                    //remove error response 

                    // $('#tbl_roles').DataTable().ajax.reload();
                },
                error: function(error) {
                    if (error.responseJSON.name) {
                        $('#name_error').html(error.responseJSON.name[0]);
                    } else {
                        $('#name_error').html('');
                    }

                    if (error.responseJSON.email) {
                        $('#email_error').html(error.responseJSON.email[0]);
                    } else {
                        $('#email_error').html('');
                    }

                    if (error.responseJSON.password) {
                        $('#password_error').html(error.responseJSON.password[0]);
                    } else {
                        $('#password_error').html('');
                    }
                }
            });
        }
    </script>
@endpush
