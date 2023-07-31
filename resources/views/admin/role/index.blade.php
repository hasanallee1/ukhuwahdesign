@extends('template.main')

@section('content')
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Role</h3>
            </div>
            <div class="card-body">
                @can('roles.create')
                    <div class="row mb-2">
                        <a href="{{ route('admin.role.create') }}" id="add" class="btn btn-sm btn-outline-primary ">
                            <i class="fa fa-plus"></i> Tambah Data
                        </a>
                    </div>
                @endcan
                <table id="tbl_roles" class="table table-bordered" width="100%">
                    <thead class="text-center">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Name</th>
                            <th width="60%">Permission</th>
                            <th width="20%">Action</th>
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




    </section>
@endsection

@push('scripts')
    <script>
        var token = $("meta[name='csrf-token']").attr("content");

        $(document).ready(function() {
            $('#tbl_roles').DataTable({
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
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'roles.name'
                    },
                    {
                        data: 'permission',
                        orderable: false,
                        sortable: false,
                        searchable: false
                        // name: 'name'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        sortable: false
                        // name: 'name'
                    }
                ]
            });
        });


        //delete 
        $('#tbl_roles').on('click', '.delete', function() {

            var id = $(this).data('id');
            var token = $("meta[name='csrf-token']").attr("content");

            Swal.fire({
                title: 'Apakah anda yakin ?',
                text: "Data Role akan dihapus",
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
                        url: "{{ route('admin.role.index') }}/" + id,
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
                            $('#tbl_roles').DataTable().ajax.reload();
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
