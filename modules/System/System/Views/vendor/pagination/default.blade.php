<input type="hidden" name="_currentPage" id="_currentPage" value="{{$paginator->currentPage()}}">
<div class="col-sm-3">
    <div class="dataTables_info"><span class="page-link">{{Lang::get('System::Common.page_list')}} {{$paginator->count()}}/ {{$paginator->total()}} {{Lang::get('System::Common.page_namelist')}}</span></div>
</div>
<div class="col-sm-6">
    <div class="main_paginate">
@if ($paginator->hasPages())
        <ul class="pagination pagination-sm" style="margin: 0;white-space: nowrap;text-align: center;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">{{Lang::get('System::Common.page_next')}}</span></li>
            @else
                <li class="page-item"><a page="{{$paginator->currentPage() - 1}}" class="page-link" rel="prev">{{Lang::get('System::Common.page_next')}}</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a page="{{ $page }}" class="page-link">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a page="{{$paginator->currentPage() + 1}}" class="page-link" rel="next">{{Lang::get('System::Common.page_prev')}}</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">{{Lang::get('System::Common.page_prev')}}</span></li>
            @endif
        </ul>
@endif
    </div>
</div>
<div class="col-sm-3">
    <div class="left_paginate">
        <div class="col-sm-6">
            <span class="page-link">{{Lang::get('System::Common.page_viewselect')}}</span>
        </div>
        <div class="col-sm-6">
            <select id="cbo_nuber_record_page" class="col-sm-1 form-control input-sm" name="cbo_nuber_record_page">
                <option id="15" name="15" value="15">15</option>
                <option id="50" name="50" value="50">50</option>
                <option id="100" name="100" value="100">100</option>
            </select>
        </div>
    </div>
</div>