@extends('admin.list_template')

@section('title', 'Facturas')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm">
        <h1>Facturas</h1>
    </div>
</div>
@stop

@section('content')
    @include('admin.partials.flash')

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="the_table" class="table table-striped table-bordered table-hover" style="width:99%">
                    <caption>Lista de Facturas</caption>
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Reserva</th>
                            <th>Tipo</th>
                            <th>Importe</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Número</th>
                            <th>Reserva</th>
                            <th>Tipo</th>
                            <th>Importe</th>
                            <th>Fecha</th>
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
        properties.ajax = "{{ route('api.datatable.invoices') }}"
        properties.columns = [
            {data: 'number'},
            {data: 'booking_ref'},
            {data: 'kind'},
            {data: 'amount_label'},
            {data: 'issued_on'},
            {
                data: null,
                render: (data, type, row) => '<a class="btn btn-sm btn-info" href="{{ url('admin/facturas') }}/' + row.id + '">Más Info</a>',
            }
        ]

        $('#the_table').DataTable(properties)
    });
</script>
@stop
