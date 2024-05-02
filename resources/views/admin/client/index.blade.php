@extends('admin.list_template')

@section('title', 'Clientes')

@section('content_header')
    <h1>Clientes</h1>
@stop



@section('content')

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="clients">
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


    <script>    
        window.addEventListener('load', function() {
            let table = $('#clients').DataTable({
                ajax: "{{route('datatable.clients')}}",
                //lengthMenu: [5, 10, 20, 100, 200, 500], // opciones para mostrar cantidad de registros
                //pageLength: 5, // canditad de registros por pagina
                order: [[ 0, 'desc' ]], // por defecto ordene la primera columna en forma descendente
                columnDefs: [
                    {orderable: false, targets: [-1]}, // no se pueda ordenar por la ultima columna
                ],
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'last_name'},
                    {data: 'dni_passport'},
                    {data: 'booking_id'},
                    {
                        data: null,
                        defaultContent: '<a class="btn btn-sm btn-info" href="clientes/3" title="Más Información"> Más Info <i class="fa fa-info"></i></a> <a class="btn btn-sm btn-warning" href="clientes/3/edit" title="Editar registro"> Edita <i class="fas fa-pencil"></i></a>'
                    }
                ],
            });

            table.buttons().container().appendTo( $('.col-sm-6:eq(0)', table.table().container()));
        });
    </script>
@stop