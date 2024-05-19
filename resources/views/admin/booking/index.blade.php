@extends('admin.list_template')

@section('title', 'Reservas')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm">
        <h1>Reservas</h1>
    </div>
    <div class="col-sm text-right">
        <button class="btn btn-info">Nuevo</button>
    </div>
</div>
@stop

@section('content')

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="the_table">
                    <caption>Lista de Reservas</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Titular</th>
                            <th scope="col" nowrap>Fecha de Salida</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Titular</th>
                            <th scope="col" nowrap>Fecha de Salida</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Estado</th>
                            <th></th>
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
        properties.ajax = "{{ route('api.datatable.bookings') }}"
        properties.columns = [
            {data: 'id'},
            {data: 'titular'},
            {data: 'f_salida'},
            {data: 'total_amount'},
            {
                data: 'status_id',
                render: (data, type, row) => row.status_id == 1 ? '<span class="badge badge-success"> Pagada </span>' : '<span class="badge badge-warning"> Pendiente </span>',
            },
            {
                data: null,
                defaultContent: '<a class="btn btn-sm btn-info" href="reserva/3" title="Más Información"> Más Info <i class="fa fa-info"></i></a> <a class="btn btn-sm btn-warning" href="reserva/3/edit" title="Editar registro"> Edita <i class="fas fa-pencil"></i></a>'
            }
        ]

        $('#the_table').DataTable(properties)        
    });
</script>
@stop

