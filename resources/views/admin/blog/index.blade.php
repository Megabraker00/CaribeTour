@extends('admin.list_template')

@section('title', 'Blog')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm">
        <h1>Blog</h1>
    </div>
    <div class="col-sm text-right">
        @can('write-admin')
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-info">Nuevo</a>
        @endcan
    </div>
</div>
@stop

@section('content')
    @include('admin.partials.flash')

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="the_table" style="width:99%">
                    <caption>Lista de posts</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Título</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Autor</th>
                            <th nowrap></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Título</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Autor</th>
                            <th nowrap></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@stop

@section('custom-js')
<script>
    $(document).ready(function() {
        const canWrite = @json(auth()->user()?->can('write-admin'));
        let properties = dtProperties()
        properties.ajax = "{{ route('api.datatable.blogs') }}"
        properties.columns = [
            {data: 'id'},
            {data: 'name'},
            {data: 'slug'},
            {
                data: 'status_name',
                render: (data) => data ? '<span class="badge badge-secondary">' + data + '</span>' : '—',
            },
            {data: 'author'},
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    let showUrl = "{{ url('admin/blogs') }}/" + row.id;
                    let editUrl = showUrl + "/edit";
                    let buttons = '<div class="btn-group" role="group">' +
                        '<a role="button" class="btn btn-sm btn-info" href="' + showUrl + '" title="Más Información">Más Info</a>';
                    if (canWrite) {
                        buttons += '<a role="button" class="btn btn-sm btn-warning" href="' + editUrl + '" title="Editar registro"> Editar <i class="fas fa-pencil"></i></a>';
                    }
                    return buttons + '</div>';
                }
            }
        ]
        properties.columnDefs = [
            {targets: [-1], orderable: false, searchable: false, className: 'text-nowrap'},
        ]

        $('#the_table').DataTable(properties)
    });
</script>
@stop
