@extends('admin.list_template')

@section('title', 'Facturas')

@section('content_header')
    <h1>Facturas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.facturas.create') }}" class="btn btn-primary">Nuevo</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="the_table" class="table table-striped table-bordered table-hover" style="width:99%">
                    <caption>Lista de Facturas</caption>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Booking ID</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Booking ID</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Actions</th>
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
        properties.ajax = "{{ route('api.datatable.invoices') }}"
        properties.columns = [
            {data: 'id'},
            {data: 'booking_id'},
            {data: 'created_user_id'},
            {data: 'created_at'},
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