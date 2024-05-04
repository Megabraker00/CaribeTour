@extends('admin.list_template')

@section('title', 'Clientes')

@section('content_header')
    <h1>Clientes</h1>
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
                            <th scope="col">Reserva</th>
                            <th nowrap></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col" nowrap>Apellidos</th>
                            <th scope="col" nowrap>DNI o Pasaporte</th>
                            <th scope="col">Reserva</th>
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
        properties.ajax = "{{ route('datatable.clients') }}"
        properties.columns = [
            {data: 'id'},
            {data: 'name'},
            {data: 'last_name'},
            {data: 'dni_passport'},
            {data: 'booking_id'},
            {
                data: null,
                render: (data, type, row) => '<div class="row" role="group">' +
                       '<a class="btn btn-sm btn-info" href="clientes/' + row.id + '" title="Más Información"> Más Info <i class="fa fa-info"></i></a>' +
                       '<a class="btn btn-sm btn-warning" href="clientes/' + row.id + '/edit" title="Editar registro"> Edita <i class="fas fa-pencil"></i></a>' +
                   '</div>',
                 //'<a class="btn btn-sm btn-info" href="clientes/'+ row.id +'" title="Más Información"> Más Info <i class="fa fa-info"></i></a> <a class="btn btn-sm btn-warning" href="clientes/'+ row.id +'/edit" title="Editar registro"> Edita <i class="fas fa-pencil"></i></a>',
                // defaultContent: '<a class="btn btn-sm btn-info" href="clientes/3" title="Más Información"> Más Info <i class="fa fa-info"></i></a> <a class="btn btn-sm btn-warning" href="clientes/3/edit" title="Editar registro"> Edita <i class="fas fa-pencil"></i></a>'
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