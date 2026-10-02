@extends('admin.list_template')

@section('title', $catalog['label'])

@section('content_header')
<div class="row mb-2">
    <div class="col-sm">
        <h1>{{ $catalog['label'] }}</h1>
    </div>
    <div class="col-sm text-right">
        <a href="{{ route('admin.catalog.create', $catalog['kind']) }}" class="btn btn-info">Nuevo</a>
    </div>
</div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="the_table" style="width:99%">
                    <caption>Lista de {{ $catalog['label'] }}</caption>
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
        const showBase = "{{ url('admin/'.$catalog['kind']) }}";
        let properties = dtProperties()
        properties.ajax = "{{ route('api.datatable.catalog', $catalog['kind']) }}"
        properties.columns = [
            {data: 'id'},
            {data: 'name'},
            {data: 'category'},
            {data: 'status_name'},
            {
                data: 'precio',
                render: (data) => {
                    if (data === null || data === undefined || data === '') {
                        return '—';
                    }
                    const n = Number(data);
                    if (Number.isNaN(n)) {
                        return '—';
                    }
                    return new Intl.NumberFormat('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n) + '\u00a0€';
                },
                type: 'num',
            },
            {
                data: null,
                render: (data, type, row) => '<div class="btn-group" role="group">' +
                       '<a role="button" class="btn btn-sm btn-info" href="' + showBase + '/' + row.id + '" title="Más Información">Más Info</a>' +
                       '<a role="button" class="btn btn-sm btn-warning" href="' + showBase + '/' + row.id + '/edit" title="Editar registro"> Editar <i class="fas fa-pencil"></i></a>' +
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
