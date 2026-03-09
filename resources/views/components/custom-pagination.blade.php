@if ($paginator->hasPages())
    <div class="pagination-wrapper">
        <nav class="custom-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="page-link disabled">‹</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link">‹</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="page-link dots">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="page-link">›</a>
            @else
                <span class="page-link disabled">›</span>
            @endif
        </nav>
        
        <div class="pagination-info">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>
    </div>

    <style>
        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .custom-pagination {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        
        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #fff;
            color: #334155;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .page-link:hover:not(.disabled):not(.active) {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }
        
        .page-link.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #fff;
        }
        
        .page-link.disabled {
            color: #94a3b8;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .page-link.dots {
            border: none;
            background: transparent;
            cursor: default;
        }
        
        .pagination-info {
            font-size: 13px;
            color: #64748b;
        }
    </style>
@endif
