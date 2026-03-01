@php
    $segments = request()->segments();
    $url = url('/');
@endphp
<section class="container pt-4">
    <nav aria-label="breadcrumb" style="min-height: 24px;">
        <ol class="breadcrumb">

            {{-- Home --}}
            <li class="breadcrumb-item">
                <a href="{{ url('/') }}" title="Inicio">Inicio</a>
            </li>

            @foreach($segments as $index => $segment)

                @php
                    $url .= '/' . $segment;
                    $name = ucwords(str_replace('-', ' ', $segment));
                @endphp

                @if($index + 1 < count($segments))
                    <li class="breadcrumb-item">
                        <a href="{{ $url }}" title="{{ $name }}">{{ $name }}</a>
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