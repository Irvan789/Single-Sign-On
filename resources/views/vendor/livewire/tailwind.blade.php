@php
    if (!isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet =
        $scrollTo !== false
            ? <<<JS
               window.overlayScrollbars.elements().viewport.scrollTo({ top: 0, behavior: "smooth" })
            JS
            : '';
@endphp

<div class="contents">
    @if ($paginator->hasPages())
        <nav
            role="navigation"
            aria-label="Pagination Navigation"
            class="flex items-center justify-between"
        >
            <div class="flex flex-1 items-center justify-between">
                <p class="text-sm/5 text-[#544636]">
                    <span>{!! __('Showing') !!}</span>
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    <span>{!! __('to') !!}</span>
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    <span>{!! __('of') !!}</span>
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    <span>{!! __('results') !!}</span>
                </p>

                <div>
                    <span class="relative z-0 inline-flex gap-1 rtl:flex-row-reverse">
                        <span>
                            @if ($paginator->onFirstPage())
                                <div
                                    aria-disabled="true"
                                    aria-label="{{ __('pagination.previous') }}"
                                >
                                    <span
                                        class="rounded-xs relative inline-flex size-8 cursor-not-allowed items-center text-sm/5 font-medium text-[#544636]/60"
                                        aria-hidden="true"
                                    >
                                        <svg
                                            class="size-4.5 mx-auto"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </span>
                                </div>
                            @else
                                <button
                                    type="button"
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    class="rounded-xs relative inline-flex size-8 cursor-pointer items-center text-sm/5 font-medium text-[#3d3530cb] transition duration-150 ease-in-out hover:bg-[#544636] hover:text-[#f0ede8]"
                                    aria-label="{{ __('pagination.previous') }}"
                                >
                                    <svg
                                        class="size-4.5 mx-auto"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            @endif
                        </span>

                        <div class="hidden sm:inline-flex sm:gap-x-1">
                            @foreach ($elements as $element)
                                @if (is_string($element))
                                    <div aria-disabled="true">
                                        <div
                                            class="relative inline-flex size-8 cursor-default items-center text-sm/5 font-medium">
                                            <span class="mx-auto"> {{ $element }} </span>
                                        </div>
                                    </div>
                                @endif

                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        <div
                                            wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                            @if ($page == $paginator->currentPage())
                                                <div
                                                    class="rounded-xs relative inline-flex size-8 cursor-default items-center justify-center bg-[#544636] text-sm/5 font-medium text-[#f0ede8]">
                                                    {{ $page }}
                                                </div>
                                            @else
                                                <button
                                                    type="button"
                                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                                    class="rounded-xs relative inline-flex size-8 cursor-pointer items-center justify-center text-sm/5 font-medium transition duration-150 ease-in-out hover:bg-[#544636] hover:text-[#f0ede8]"
                                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                                >
                                                    {{ $page }}
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>

                        <span>
                            @if ($paginator->hasMorePages())
                                <button
                                    type="button"
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    class="rounded-xs relative inline-flex size-8 cursor-pointer items-center text-sm/5 font-medium text-[#544636] transition duration-150 ease-in-out hover:bg-[#544636] hover:text-[#f0ede8]"
                                    aria-label="{{ __('pagination.next') }}"
                                >
                                    <svg
                                        class="size-4.5 mx-auto"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            @else
                                <div
                                    aria-disabled="true"
                                    aria-label="{{ __('pagination.previous') }}"
                                >
                                    <span
                                        class="rounded-xs relative inline-flex size-8 cursor-not-allowed items-center text-sm/5 font-medium text-[#544636]/60"
                                        aria-hidden="true"
                                    >
                                        <svg
                                            class="size-4.5 mx-auto"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </span>
                                </div>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
