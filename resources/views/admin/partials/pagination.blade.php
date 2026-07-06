@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-500">
            Showing <span class="font-medium text-slate-700">{{ $paginator->firstItem() }}</span>
            to <span class="font-medium text-slate-700">{{ $paginator->lastItem() }}</span>
            of <span class="font-medium text-slate-700">{{ $paginator->total() }}</span> results
        </p>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-300" aria-disabled="true">
                    <i class="fas fa-chevron-left text-xs"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" title="Previous page"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-colors hover:border-primary hover:text-primary">
                    <i class="fas fa-chevron-left text-xs"></i>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-8 min-w-8 items-center justify-center px-1 text-sm text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg bg-primary px-2 text-sm font-medium text-white">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg border border-slate-200 bg-white px-2 text-sm text-slate-600 transition-colors hover:border-primary hover:text-primary">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" title="Next page"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-colors hover:border-primary hover:text-primary">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            @else
                <span class="inline-flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-300" aria-disabled="true">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
