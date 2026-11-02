@extends('admin.list_template')

@section('title', 'Clientes')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm">
        <h1>Clientes</h1>
    </div>
    <div class="col-sm text-right">
        @can('write-admin')
            <button class="btn btn-info">Nuevo</button>
        @endcan
    </div>
</div>
@stop

@section('content')

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="the_table" style="width:99%">
                    <caption>Lista de Clientes</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col" nowrap>Apellidos</th>
                            <th scope="col" nowrap>DNI o Pasaporte</th>
                            <th nowrap></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col" nowrap>Apellidos</th>
                            <th scope="col" nowrap>DNI o Pasaporte</th>
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
        properties.ajax = "{{ route('api.datatable.clients') }}"
        properties.columns = [
            {data: 'id'},
            {data: 'name'},
            {data: 'last_name'},
            {data: 'dni_passport'},
            {
                data: null,
                render: (data, type, row) => {
                    let buttons = '<div class="btn-group" role="group">' +
                       '<a role="button" class="btn btn-sm btn-info" href="clientes/' + row.id + '" title="Más Información">Más Info</a>';
                    if (canWrite) {
                        buttons += '<a role="button" class="btn btn-sm btn-warning" href="clientes/' + row.id + '/edit" title="Editar registro">Editar</a>';
                    }
                    return buttons + '</div>';
                },
            }
        ]

        $('#the_table').DataTable(properties)

        /**
         * hace que la fila sea clicable
         */
        /*
        $('#the_table tbody').on('click', 'tr', function () {
            // Obtener los datos de la fila
            var data = $('#the_table').DataTable().row(this).data();
            
            // Redirigir a la página del cliente
            if (data && data.id) {
                window.location.href = 'clientes/' + data.id;
            }
        });
        */
    });
</script>
@stop