@extends('admin.list_template')

@section('title', 'Clientes')

@section('content_header')
    <h1>Clientes</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="the_table">
                    <caption>Lista de Clientes</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col" nowrap>Apellidos</th>
                            <th scope="col">DNI o Pasaporte</th>
                            <th scope="col">Reserva</th>
                            <th></th>
                        </tr>
                    </thead>
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
                defaultContent: '<a class="btn btn-sm btn-info" href="clientes/3" title="Más Información"> Más Info <i class="fa fa-info"></i></a> <a class="btn btn-sm btn-warning" href="clientes/3/edit" title="Editar registro"> Edita <i class="fas fa-pencil"></i></a>'
            }
        ]

        $('#the_table').DataTable(properties)        
    });
</script>
@stop