@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    {!! Form::open(['route' => 'plugins.toc.settings.post']) !!}
        <div class="max-width-1200">
            <div class="flexbox-annotated-section">

                <div class="flexbox-annotated-section-annotation">
                    <div class="annotated-section-title pd-all-20">
                        <h2>{{ trans('plugins/toc::toc.settings.title') }}</h2>
                    </div>
                    <div class="annotated-section-description pd-all-20 p-none-t">
                        <p class="color-note">{{ trans('plugins/toc::toc.settings.description') }}</p>
                    </div>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content pd-all-20">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5>{{ trans('plugins/toc::toc.settings.is_enabled') }}</h5>
                            </div>
                            <div class="col-md-9">
                                {!! Form::customSelect('is_enabled', [
                                    'yes' => trans('core/base::base.yes'),
                                    'no' => trans('core/base::base.no'),
                                ], ToCHelper::config('is_enabled')) !!}
                                <span class="help-ts">
                                    {{ trans('plugins/toc::toc.settings.is_enabled_help') }}
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5>{{ trans('plugins/toc::toc.settings.headings') }}</h5>
                            </div>
                            <div class="col-md-9">
                                @for ($i = 1; $i <= 6; $i++)
                                    <div class="mb-3 form-check">
                                        {!! Form::checkbox('heading_levels[]', $i, in_array($i, ToCHelper::config('heading_levels')), ['id' => 'heading-levels-' . $i]) !!}
                                        <label class="form-check-label" for="heading-levels-{{ $i }}">{{ trans('plugins/toc::toc.settings.heading', ['num' => $i]) }}</label>
                                    </div>
                                @endfor
                                <span class="help-ts">
                                    {{ trans('plugins/toc::toc.settings.headings_help') }}
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5>{{ trans('plugins/toc::toc.settings.position') }}</h5>
                            </div>
                            <div class="col-md-9">
                                {!! Form::customSelect('position', [
                                    'before-first-heading' => 'Before first heading',
                                    'after-first-heading' => 'After first heading',
                                    'top' => 'Top',
                                    'bottom' => 'Bottom',
                                ], ToCHelper::config('position'), ['id' => 'position']) !!}
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5>{{ trans('plugins/toc::toc.settings.display_toc_when') }}</h5>
                            </div>
                            <div class="col-md-9">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        {!! Form::number('start', ToCHelper::config('start'), [
                                            'id' => 'start',
                                            'min' => '0',
                                            'class' => 'form-control' . ($errors->has('start') ? ' is-invalid' : ''),
                                            'required' => true,
                                        ]) !!}
                                    </div>
                                    <div class="col-auto">
                                        <span>{{ trans('plugins/toc::toc.settings.or_more_headings_are_presend') }}</span>
                                    </div>
                                </div>

                                @error('start')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $errors->first('start') }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5>{{ trans('plugins/toc::toc.settings.css_container_class') }}</h5>
                            </div>
                            <div class="col-md-9">
                                {!! Form::text('css_container_class', ToCHelper::config('css_container_class'), [
                                    'class' => 'form-control' . ($errors->has('css_container_class') ? ' is-invalid' : ''),
                                    'id' => 'css_container_class',
                                ]) !!}

                                @if ($errors->has('css_container_class'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('css_container_class') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5>{{ trans('plugins/toc::toc.settings.anchor_prefix') }}</h5>
                            </div>
                            <div class="col-md-9">
                                {!! Form::text('anchor_prefix', ToCHelper::config('anchor_prefix'), [
                                        'class' => 'form-control' . ($errors->has('anchor_prefix') ? ' is-invalid' : ''),
                                        'id' => 'anchor_prefix',
                                    ]) !!}

                                @if ($errors->has('anchor_prefix'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('anchor_prefix') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5>{{ trans('plugins/toc::toc.settings.show_hierarchy') }}</h5>
                            </div>
                            <div class="col-md-9">
                                {!! Form::customSelect('show_hierarchy', [
                                    'yes' => trans('core/base::base.yes'),
                                    'no' => trans('core/base::base.no'),
                                ], ToCHelper::config('show_hierarchy')) !!}
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5>{{ trans('plugins/toc::toc.settings.ordered_list') }}</h5>
                            </div>
                            <div class="col-md-9">
                                {!! Form::customSelect('ordered_list', [
                                    'yes' => trans('core/base::base.yes'),
                                    'no' => trans('core/base::base.no'),
                                ], ToCHelper::config('ordered_list')) !!}
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5>{{ trans('plugins/toc::toc.settings.show_options_in_form') }}</h5>
                            </div>
                            <div class="col-md-9">
                                {!! Form::customSelect('show_options_in_form', [
                                    'yes' => trans('core/base::base.yes'),
                                    'no' => trans('core/base::base.no'),
                                ], ToCHelper::config('show_options_in_form')) !!}
                                <span class="help-ts">
                                    {{ trans('plugins/toc::toc.settings.show_options_in_form_help') }}
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5>{{ trans('plugins/toc::toc.settings.context_meta_box_in_form') }}</h5>
                            </div>
                            <div class="col-md-9">
                                {!! Form::customSelect('context_meta_box_in_form', [
                                    'side' => trans('plugins/toc::toc.settings.context.sidebar'),
                                    'top' => trans('plugins/toc::toc.settings.context.top'),
                                    'advanced' => trans('plugins/toc::toc.settings.context.advanced'),
                                ], ToCHelper::config('context_meta_box_in_form')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flexbox-annotated-section border-0">
                <div class="flexbox-annotated-section-annotation">
                    &nbsp;
                </div>
                <div class="flexbox-annotated-section-content">
                    <button class="btn btn-info" type="submit">{{ trans('core/setting::setting.save_settings') }}</button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

    @if (setting('plugin_toc_settings'))
        {!! Form::open([
                'route' => 'plugins.toc.settings.restore-factory',
                'onSubmit' => 'return confirm("' . trans('plugins/toc::toc.settings.restore_factory.confirmation') . '")',
                'class' => 'mt-4'
            ]) !!}
            <div class="container py-4">
                <div class="row">
                    <div class="col-12">
                        <div class="accordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="restore-factory-label">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#restore-factory" aria-expanded="false" aria-controls="restore-factory">
                                        <i class="fas fa-undo-alt"></i>
                                        <span>{{ trans('plugins/toc::toc.settings.restore_factory.title') }}</span>
                                    </button>
                                </h2>
                                <div id="restore-factory" class="accordion-collapse collapse" aria-labelledby="restore-factory-label">
                                    <div class="accordion-body">
                                        <div class="alert alert-warning">
                                            {{ trans('plugins/toc::toc.settings.restore_factory.description') }}
                                        </div>
                                        <button type="submit" class="btn btn-outline-danger">{{ trans('core/base::tables.restore') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    @endif
@endsection
