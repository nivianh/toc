<div class="toc-container {{ $cssClasses ?? '' }} table-of-content">
    <p class="toc_title" data-show-text="{{ trans('plugins/toc::toc.show') }}" data-hide-text="{{ trans('plugins/toc::toc.hide') }}">
        {{ trans('plugins/toc::toc.post_content') }}
        <span class="toc_toggle">
            [<a href="#">{{ trans('plugins/toc::toc.show') }}</a>]
        </span>
    </p>
    <ul class="toc_list">{!! $items !!}</ul>
</div>
