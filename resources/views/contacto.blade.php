@extends('front_template')
@section('title', 'Contacto')
@section('description', 'Escríbenos para organizar tu viaje al Caribe. Te ayudamos con destinos, fechas y reservas.')

@section('content')
    <!-- container -->
    <section class="container my-4">

        <h1 class="mb-4">Contacto</h1>

        <!-- mapa -->
        <div class="row">

            <iframe class="w-100" title="Mapa de la oficina en Madrid" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3037.654620915151!2d-3.7030016492002553!3d40.41650141731886!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd42287e9fd06215%3A0xd5e109ff23e3d0c1!2sCentro%2C%20Madrid!5e0!3m2!1ses-419!2ses!4v1771006974508!5m2!1ses-419!2ses" height="450" style="border:0;" allow="local-network; loopback-network; fullscreen" referrerpolicy="no-referrer-when-downgrade"></iframe>

        </div>
        <!-- /mapa -->

        <!-- formulario de contacto -->
        <div class="row mt-4">

            <div class="col-md-6 pb-4">
                <h2>Encantados de escucharte</h2>
                <hr>

                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contacto.store') }}" method="post" data-disable-on-submit>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" maxlength="100" required placeholder="Tu nombre completo">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" maxlength="255" required placeholder="Tu correo electrónico">
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" maxlength="5000" required placeholder="Escribe tu mensaje aquí">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>

            <div class="col-md-6">
                <h2>Información de contacto</h2>
                <hr>

                <p><i class="bi bi-geo-alt-fill"></i> <a href="https://maps.google.com/?q=calle+falsa+123+alcobendas+madrid+28100" target="_blank"> Calle Falsa 123, Ciudad, País</a></p>
                <p><i class="bi bi-telephone-fill"></i> Teléfono: +1 234 567 890</p>
                <p><i class="bi bi-envelope-fill"></i> Correo electrónico: <a href="mailto:info@caribetour.es">info@caribetour.es</a></p>
                <p><i class="bi bi-clock-fill"></i> Horario de atención: Lunes a Viernes de 9:00 a 18:00</p>
            </div>

        </div>
        <!-- /formulario de contacto -->

    </section>
    <!-- /container -->
@endsection