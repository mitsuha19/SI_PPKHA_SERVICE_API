@if ($paginator->hasPages())
    <navbar>
        <ul class="pagination custom-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled ms-3"><a>«</a></li>
            @else
                <li>
                    <a class="ms-3" href="{{ $paginator->previousPageUrl() }}" rel="prev">«</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled"><a>{{ $element }}</a></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active1"><a class="active">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next">»</a>
                </li>
            @else
                <li class="disabled1"><a>»</a></li>
            @endif
        </ul>
    </navbar>
@endif
