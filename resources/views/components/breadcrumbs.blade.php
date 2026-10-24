@props(['crumbs'])

@php
    $clickable = ! request()->is('reserva', 'reserva/*');
@endphp
<section class="container mt-4">
    <nav aria-label="breadcrumb" style="min-height: 24px;">
        <ol class="breadcrumb">
            @foreach ($crumbs as $index => $crumb)
                @php $isCurrent = $index === array_key_last($crumbs); @endphp
                <li class="breadcrumb-item{{ $isCurrent ? ' active' : '' }}" @if ($isCurrent) aria-current="page" @endif>
                    @if ($isCurrent)
                        {{ $crumb['name'] }}
                    @elseif ($clickable)
                        <a href="{{ $crumb['url'] }}" title="{{ $crumb['name'] }}">{{ $crumb['name'] }}</a>
                    @else
                        <span>{{ $crumb['name'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
</section>
