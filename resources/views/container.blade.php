<div class="toc-container {{ $cssClasses ?? '' }} table-of-content">
    <p class="toc_title">
        {{ trans('plugins/toc::toc.name') }}
        <span class="toc_toggle d-none show-text">
            [<a href="#">{{ trans('plugins/toc::toc.show') }}</a>]
        </span>
        <span class="toc_toggle d-none hide-text">
            [<a href="#">{{ trans('plugins/toc::toc.hide') }}</a>]
        </span>
    </p>
    <ul class="toc_list">{!! $items !!}</ul>
</div>
