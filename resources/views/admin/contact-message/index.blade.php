@extends('admin.list_template')

@section('title', 'Mensajes')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm">
            <h1>Mensajes de contacto</h1>
        </div>
    </div>
@stop

@section('content')
    @include('admin.partials.flash')

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="the_table" style="width:99%">
                    <caption>Lista de mensajes</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Mensaje</th>
                            <th scope="col">Estado</th>
                            <th nowrap></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Mensaje</th>
                            <th scope="col">Estado</th>
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
        const csrf = @json(csrf_token());
        let properties = dtProperties()
        properties.ajax = "{{ route('api.datatable.contact-messages') }}"
        properties.columns = [
            {data: 'id'},
            {data: 'received_on'},
            {data: 'name'},
            {data: 'email'},
            {data: 'excerpt'},
            {
                data: 'status_label',
                render: (data) => data === 'Nuevo'
                    ? '<span class="badge badge-warning">Nuevo</span>'
                    : '<span class="badge badge-secondary">Leído</span>'
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    let showUrl = "{{ url('admin/mensajes') }}/" + row.id;
                    let buttons = '<div class="btn-group" role="group">' +
                        '<a role="button" class="btn btn-sm btn-info" href="' + showUrl + '" title="Ver mensaje">Ver</a>';
                    if (canWrite) {
                        buttons += '<form action="' + showUrl + '" method="POST" class="d-inline" onsubmit="return confirm(\'¿Eliminar este mensaje?\');">' +
                            '<input type="hidden" name="_token" value="' + csrf + '">' +
                            '<input type="hidden" name="_method" value="DELETE">' +
                            '<button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                            '</form>';
                    }
                    return buttons + '</div>';
                }
            }
        ]
        properties.columnDefs = [
            {targets: [-1], orderable: false, searchable: false, className: 'text-nowrap'},
        ]
        properties.createdRow = function(row, data) {
            if (data.read_at === null) {
                $(row).addClass('font-weight-bold');
            }
        }

        $('#the_table').DataTable(properties)
    });
</script>
@stop
