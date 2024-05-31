@extends('admin.list_template')

@section('title', 'Tours')

@section('content_header')

<div class="row mb-2">
    <div class="col-sm">
        <h1>Tours</h1>
    </div>
    <div class="col-sm text-right">
        <a href="{{ route('admin.tour.create') }}" class="btn btn-info">Nuevo</a>
    </div>
</div>

@stop

@section('content')

    <div class="card">
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
                render: (data, type, row) => '<div class="row" role="group">' +
                       '<a class="btn btn-sm btn-info" href="tours/' + row.id + '" title="Más Información">Más Info</a>' +
                       '<a class="btn btn-sm btn-warning" href="tours/' + row.id + '/edit" title="Editar registro"> Editar <i class="fas fa-pencil"></i></a>' +
                   '</div>',
            }
        ]

        $('#the_table').DataTable(properties)
    });
</script>
@stop