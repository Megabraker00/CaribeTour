@extends('admin.list_template')

@section('title', 'Tours')

@section('content_header')
    <h1>Tours</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.tour.create') }}" class="btn btn-primary">Nuevo</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="the_table" style="width:99%">
                    <caption>Lista de Tours</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Categoría</th>
                            <th scope="col" nowrap>Estado</th>
                            <th scope="col" nowrap>Precio desde</th>
                            <th nowrap></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Categoría</th>
                            <th scope="col" nowrap>Estado</th>
                            <th scope="col" nowrap>Precio desde</th>
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
        let properties = dtProperties()
        properties.ajax = "{{ route('api.datatable.tours') }}"
        properties.columns = [
            {data: 'id'},
            {data: 'name'},
            {data: 'category'},
            {data: 'status_id'},
            {data: 'precio'},
            {
                data: null,
                render: (data, type, row) => '<div class="btn-group" role="group">' +
                       '<a role="button" class="btn btn-sm btn-info" href="tours/' + row.id + '" title="Más Información">Más Info</a>' +
                       '<a role="button" class="btn btn-sm btn-warning" href="tours/' + row.id + '/edit" title="Editar registro"> Editar <i class="fas fa-pencil"></i></a>' +
                   '</div>',
            }
        ]
        properties.columnDefs = [
            {targets: [1,2,5], className: 'text-nowrap'},
        ]

        $('#the_table').DataTable(properties)
    });
</script>
@stop