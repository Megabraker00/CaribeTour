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
                <table class="table table-striped table-hover" id="the-table">
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
        window.addEventListener('load', function() {
            $('table.table').DataTable({
                destroy: true;
                order: [[ 0, 'desc' ]],
            })
        })
    </script>
@endsection
