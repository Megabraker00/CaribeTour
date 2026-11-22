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
                <form action="{{ route('contacto.store') }}" method="post" data-disable-on-submit>
                    @csrf
                    <p class="text-muted">Todos los campos son obligatorios.</p>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" maxlength="100" required autocomplete="name" placeholder="Tu nombre completo" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                            @error('name')
                                <div id="name-error" class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" maxlength="255" required autocomplete="email" placeholder="Tu correo electrónico" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                            @error('email')
                                <div id="email-error" class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Asunto</label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject', $subject) }}" maxlength="150" required placeholder="Motivo de tu consulta" @error('subject') aria-invalid="true" aria-describedby="subject-error" @enderror>
                        @error('subject')
                            <div id="subject-error" class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if ($productSlug !== '')
                        <input type="hidden" name="product" value="{{ $productSlug }}">
                    @endif

                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" maxlength="5000" required placeholder="Escribe tu mensaje aquí" @error('message') aria-invalid="true" aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
                        @error('message')
                            <div id="message-error" class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>

            <div class="col-md-6">
                <h2>Información de contacto</h2>
                <hr>

                <p><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> <a href="https://maps.google.com/?q=calle+falsa+123+alcobendas+madrid+28100" target="_blank" rel="noopener noreferrer">Calle Falsa 123, Ciudad, País <small>(se abre en una pestaña nueva)</small></a></p>
                <p><i class="bi bi-telephone-fill" aria-hidden="true"></i> Teléfono: <a href="tel:+1234567890">+1 234 567 890</a></p>
                <p><i class="bi bi-envelope-fill" aria-hidden="true"></i> Correo electrónico: <a href="mailto:info@caribetour.es">info@caribetour.es</a></p>
                <p><i class="bi bi-clock-fill" aria-hidden="true"></i> Horario de atención: Lunes a Viernes de 9:00 a 18:00</p>
            </div>

        </div>
        <!-- /formulario de contacto -->

    </section>
    <!-- /container -->
@endsection