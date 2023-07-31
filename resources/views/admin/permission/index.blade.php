@extends('template.main')

@section('content')
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Permission</h3>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <button id="add" class="btn btn-sm btn-outline-primary ">
                        <i class="fa fa-plus"></i> Tambah Data
                    </button>
                </div>
                <table id="tbl_permissions" class="table table-bordered" width="100%">
                    <thead class="text-center">
                        <tr>
                            <th width="5%">#</th>
                            <th width="95%">Permissions</th>
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
            $('#tbl_permissions').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                order: [
                    [1, 'asc']
                ],
                ajax: '{{ url()->current() }}',
                columns: [
                    // {
                    //     data: null,
                    //     sortable: false,
                    //     className: "text-center",
                    //     orderable: false,
                    //     render: function(data, type, row, meta) {
                    //         return meta.row + meta.settings._iDisplayStart + 1;
                    //     }
                    // },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: "text-center",
                        orderable: false,
                        searchable: false

                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                ]
            });
        });
    </script>
@endpush
