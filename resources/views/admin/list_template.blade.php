@extends('adminlte::page')

@section('title', 'Lista')

@section('content_header')
    <h1>Lista</h1>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap4.css">
@endsection

@section('content')

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="the_table">
                    <caption>Lista de ...</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Titular</th>
                            <th scope="col" nowrap>Fecha de Salida</th>
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
                stateSave:true,
                processing: true,
                //serverSide: true,
                //lengthMenu: [5, 10, 20, 100, 200, 500], // opciones para mostrar cantidad de registros
                pageLength: 5, // canditad de registros por pagina
                order: [[ 0, 'desc' ]], // por defecto ordene la primera columna en forma descendente
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                columns: [],
                columnDefs: [
                    {orderable: false, targets: [-1]}, // no se pueda ordenar por la ultima columna
                ]
            }
        }
    </script>
    @yield('custom-js')
@endsection