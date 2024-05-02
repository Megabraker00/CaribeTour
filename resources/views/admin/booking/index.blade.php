@extends('admin.list_template')

@section('title', 'Reservas')

@section('content_header')
    <h1>Reservas</h1>
@stop



@section('content')

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="bookings">
                    <caption>Lista de Reservas</caption>
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

    <script>

    window.addEventListener('load', function() {
        $('#bookings').DataTable({
                'ajax': "{{route('datatable.bookings')}}",
                'columns': [
                    {data: 'id'},
                    {data: 'titular'},
                    {data: 'f_salida'},
                    {data: 'total_amount'},
                    {data: 'status_id'},
                ]
            });
    });
    </script>

@stop

