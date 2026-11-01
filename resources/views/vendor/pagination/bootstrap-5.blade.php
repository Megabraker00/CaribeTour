@if ($paginator->hasPages())
    <style>
        .site-pagination {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .site-pagination-summary {
            margin: 0;
            color: #6b6560;
            font-size: 0.95rem;
        }

        .site-pagination .pagination {
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.4rem;
            margin: 0;
        }

        .site-pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.7rem;
            color: #444;
            background-color: #fff;
            border: 1px solid #e7e1d8;
            border-radius: 0.5rem;
            font-weight: 600;
            line-height: 1;
            text-decoration: none;
            box-shadow: 0 1px 2px rgba(68, 68, 68, 0.06);
        }

        .site-pagination .page-item:not(:first-child) .page-link,
        .site-pagination .page-item:first-child .page-link,
        .site-pagination .page-item:last-child .page-link {
            margin-left: 0;
            border-radius: 0.5rem;
        }

        .site-pagination .page-link:hover {
            color: #ff9000;
            background-color: #fff6eb;
            border-color: #ff9000;
        }

        .site-pagination .page-link:focus {
            z-index: 3;
            color: #444;
            background-color: #fff;
            border-color: #ff9000;
            outline: 0;
            box-shadow: 0 0 0 0.25rem #ff900026;
        }

        .site-pagination .page-item.active .page-link,
        .site-pagination .page-item.active .page-link:hover,
        .site-pagination .page-item.active .page-link:focus {
            color: #fff;
            background-color: #ff9000;
            border-color: #ff9000;
        }

        .site-pagination .page-item.active .page-link:hover {
            background-color: #F38A00;
            border-color: #F38A00;
        }

        .site-pagination .page-item.disabled .page-link {
            color: #b9b3aa;
            background-color: #faf8f6;
            border-color: #eeeae4;
            box-shadow: none;
        }
    </style>
    <nav class="site-pagination" aria-label="Paginación">
        <p class="site-pagination-summary">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} de {{ $paginator->total() }}</p>

        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-label="Anterior"><i class="bi bi-chevron-left" aria-hidden="true"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Anterior"><i class="bi bi-chevron-left" aria-hidden="true"></i></a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Siguiente"><i class="bi bi-chevron-right" aria-hidden="true"></i></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-label="Siguiente"><i class="bi bi-chevron-right" aria-hidden="true"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
