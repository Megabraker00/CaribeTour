@extends('adminlte::page')

@section('title', 'Lista')

@section('content_header')
    <h1>Lista</h1>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap4.css">
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}"> --> 
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin') }}" class="btn btn-primary">Nuevo</a>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="the_table">
                    <caption>Lista de ...</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Titular</th>
                            <th scope="col" nowrap>Fecha de la Salida</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Estado</th>

                        </tr>
                    </thead>                    
                </table>
            </div>

        </div>
    </div>

@stop

@section('js')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap4.js"></script>
    <script>
        /**
         * datatable properties 
         */
        function dtProperties() {
            return {
                ajax: "",
                destroy: true,
                //bDestroy: true,
                //retrieve: true
                stateSave: true, // guarda el estado de la tabla
                processing: true,
                responsive: true,
                autoWidth: false,
                //scrollX: true,
                //serverSide: true,
                //lengthMenu: [5, 10, 50, 100, 200, 500], // opciones para mostrar cantidad de registros
                //pageLength: 5, // canditad de registros por pagina por defecto
                order: [[ 0, 'desc' ]], // por defecto ordene la primera columna en forma descendente
                //buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                //dom: "Bfrtilp",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa-solid fa-file-csv"></i>',
                        titleAttr: "Exportar a Excel",
                        className: "btn btn-success",
                    }
                ],
                columns: [],
                columnDefs: [
                    {orderable: false, targets: [-1]}, // no se pueda ordenar por la ultima columna, usa targets:'_all' para aplicarlo a todas las columnas
                    {className: 'text-center', targets: [0, -2]}, // tambien se puede especificar las columnas a la que queremos aplicar la clase, con tagets: [0, 3, 4], sólo se aplicará a la columna en la posición 0, 3 y 4, usa targets:'_all' para aplicarlo a todas las columnas
                    // {width: "15%", targets: [-1]}, // especificar el ancho de una o varias columnas
                    {searchable: false, targets: [3]}, // indicamos las columnas que no realizaran busquedas, usa targets:'_all' para aplicarlo a todas las columnas
                ],
                language: {
                    "decimal": ",",
                    "thousands": ".",
                    "info": "Mostrando desde _START_ al _END_ de un total de _TOTAL_ registros",
                    "infoEmpty": "Mostrando desde 0 al 0 de un total de 0 registros",
                    "infoPostFix": "",
                    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "loadingRecords": "Cargando...",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "searchPlaceholder": "Término de búsqueda",
                    "zeroRecords": "No se encontraron resultados",
                    "emptyTable": "Ningún dato disponible en esta tabla",
                    "aria": {
                        "sortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sortDescending": ": Activar para ordenar la columna de manera descendente"
                    },
                    //only works for built-in buttons, not for custom buttons
                    "buttons": {
                        "create": "Nuevo",
                        "edit": "Cambiar",
                        "remove": "Borrar",
                        "copy": "Copiar",
                        "csv": "fichero CSV",
                        "excel": "tabla Excel",
                        "pdf": "documento PDF",
                        "print": "Imprimir",
                        "colvis": "Visibilidad columnas",
                        "collection": "Colección",
                        "upload": "Seleccione fichero...."
                    },
                    "select": {
                        "rows": {
                            _: '%d filas seleccionadas',
                            0: 'clic fila para seleccionar',
                            1: 'una fila seleccionada'
                        }
                    }
                }       
            }
        }
    </script>
    @yield('custom-js')
@endsection