<div class="form-group">
    {!! Form::customSelect(
        'show_toc_in_content',
        [
            'default' => trans('plugins/toc::toc.settings.default'),
            'yes' => trans('core/base::base.yes'),
            'no'  => trans('core/base::base.no'),
        ],
        $showToC,
        ['class' => 'form-control', 'id' => 'show_toc_in_content']
    ) !!}
</div>
