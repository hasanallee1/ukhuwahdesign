@extends('template.main')

@section('content')
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Role</h3>
            </div>
            <div class="card-body">
                <form id="form-edit">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="name">Role</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}"
                            placeholder="Masukkan Role">
                        <input type="hidden" id="role_id" name="role_id" value="{{ $role->id }}">
                        <small class="text-danger" id="name_error"></small>
                    </div>
                    <div class="form-group">
                        <label for="permissions">Permissions</label>
                        <div>
                            @foreach ($permissions as $permission)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input" data-id="{{ $permission->id }}"
                                        id="check-{{ $permission->id }}" value="{{ $permission->name }}"
                                        name="permissions[]" @if ($role->permissions->contains($permission)) checked @endif>
                                    <label class="form-check-label"
                                        for="check-{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button class="btn btn-sm btn-primary mr-1 btn-submit" type="button" onclick="update()"><i
                            class="fa fa-paper-plane"></i>
                        UPDATE</button>
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
        // update
        function update() {
            var id = $('#role_id').val();

            $.ajax({
                type: "PUT",
                url: "{{ route('admin.role.index') }}/" + id,
                data: $('#form-edit').serialize(),
                dataType: "json",
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses !',
                        text: `${response.message}`,
                    }).then(function() {
                        $('#name_error').html('');
                        //clear form
                        $('#form-edit')[0].reset();

                        window.location = "{{ route('admin.role.index') }}";
                    });


                },
                error: function(error) {
                    if (error.responseJSON.name) {
                        $('#name_error').html(error.responseJSON.name[0]);
                    } else {
                        $('#name_error').html('');
                    }
                }
            });
        }
    </script>
@endpush
