@php
    $segments = request()->segments();
    $url = url('/');
    $clickable = ! request()->is('reserva', 'reserva/*');
@endphp
<section class="container mt-4">
    <nav aria-label="breadcrumb" style="min-height: 24px;">
        <ol class="breadcrumb">

            {{-- Home --}}
            <li class="breadcrumb-item">
                @if ($clickable)
                    <a href="{{ url('/') }}" title="Inicio">Inicio</a>
                @else
                    <span>Inicio</span>
                @endif
            </li>

            @foreach($segments as $index => $segment)

                @php
                    $url .= '/' . $segment;
                    $name = ucwords(str_replace('-', ' ', $segment));
                @endphp

                @if($index + 1 < count($segments))
                    <li class="breadcrumb-item">
                        @if ($clickable)
                            <a href="{{ $url }}" title="{{ $name }}">{{ $name }}</a>
                        @else
                            <span>{{ $name }}</span>
                        @endif
                    </li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $name }}
                    </li>
                @endif

            @endforeach
        </ol>
    </nav>
</section>