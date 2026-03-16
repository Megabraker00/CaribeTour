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
                            <th scope="col">Ref. externa</th>
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
                            <th scope="col">Ref. externa</th>
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
            {data: 'external_ref'},
            {data: 'titular'},
            {data: 'f_salida'},
            {data: 'total_amount'},
            {
                data: 'status_name',
                render: (data) => data ? '<span class="badge badge-secondary">' + data + '</span>' : '—',
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    let showUrl = "{{ url('admin/reservas') }}/" + row.id;
                    return '<a class="btn btn-sm btn-info" href="' + showUrl + '" title="Más Información"><i class="fa fa-info"></i> Más Info</a>';
                }
            }
        ]

        $('#the_table').DataTable(properties)        
    });
</script>
@stop

