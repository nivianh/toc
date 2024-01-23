<?php

namespace Plugin\ToC\Forms\Settings;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Forms\SettingForm;
use Plugin\ToC\Facades\ToCHelper;
use Plugin\ToC\Http\Requests\ToCSettingRequest;

class ToCSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $headingLevelOptions = [];

        for ($i = 1; $i <= 6; $i++) {
            $headingLevelOptions[$i] = trans('plugins/toc::toc.settings.heading', ['num' => $i]);
        }

        $this
            ->setMethod('POST')
            ->setUrl(route('plugins.toc.settings.post'))
            ->setSectionTitle(trans('plugins/toc::toc.settings.title'))
            ->setSectionDescription(trans('plugins/toc::toc.settings.description'))
            ->setValidatorClass(ToCSettingRequest::class)
            ->add(
                'is_enabled',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/toc::toc.settings.is_enabled'))
                    ->value(ToCHelper::config('is_enabled') == 'yes')
                    ->attributes([
                        'data-bb-toggle' => 'collapse',
                        'data-bb-target' => '.toc-settings',
                    ])
                    ->toArray(),
            )
            ->add(
                'open_toc_setting',
                HtmlField::class,
                [
                    'html' => sprintf(
                        '<fieldset class="form-fieldset toc-settings" style="display: %s;" data-bb-value="1">',
                        ToCHelper::config('is_enabled') == 'yes' ? 'block' : 'none'
                    ),
                ]
            )
            ->add(
                'heading_levels[]',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(trans('plugins/toc::toc.settings.headings'))
                    ->choices($headingLevelOptions)
                    ->selected(old('heading_levels', ToCHelper::config('heading_levels')))
                    ->helperText(trans('plugins/toc::toc.settings.headings_help'))
                    ->toArray()
            )
            ->add(
                'position',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/toc::toc.settings.position'))
                    ->choices([
                        'before-first-heading' => trans('plugins/toc::toc.settings.before_first_heading'),
                        'after-first-heading' => trans('plugins/toc::toc.settings.after_first_heading'),
                        'top' => trans('plugins/toc::toc.settings.top'),
                        'bottom' => trans('plugins/toc::toc.settings.bottom'),
                    ])
                    ->selected(ToCHelper::config('position'))
                    ->toArray()
            )
            ->add(
                'start',
                NumberField::class,
                NumberFieldOption::make()
                    ->value(ToCHelper::config('start'))
                    ->label(trans('plugins/toc::toc.settings.display_toc_when'))
                    ->addAttribute('min', 0)
                    ->helperText(trans('plugins/toc::toc.settings.or_more_headings_are_presend'))
                    ->toArray()
            )
            ->add(
                'css_container_class',
                TextField::class,
                TextFieldOption::make()
                    ->value(ToCHelper::config('css_container_class'))
                    ->label(trans('plugins/toc::toc.settings.css_container_class'))
                    ->toArray()
            )
            ->add(
                'anchor_prefix',
                TextField::class,
                TextFieldOption::make()
                    ->value(ToCHelper::config('anchor_prefix'))
                    ->label(trans('plugins/toc::toc.settings.anchor_prefix'))
                    ->toArray()
            )
            ->add(
                'show_hierarchy',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->value(ToCHelper::config('show_hierarchy') == 'yes')
                    ->label(trans('plugins/toc::toc.settings.show_hierarchy'))
                    ->toArray()
            )
            ->add(
                'ordered_list',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->value(ToCHelper::config('ordered_list') == 'yes')
                    ->label(trans('plugins/toc::toc.settings.ordered_list'))
                    ->toArray()
            )
            ->add(
                'show_options_in_form',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->value(ToCHelper::config('show_options_in_form') == 'yes')
                    ->label(trans('plugins/toc::toc.settings.show_options_in_form'))
                    ->helperText(trans('plugins/toc::toc.settings.show_options_in_form_help'))
                    ->toArray()
            )
            ->add(
                'context_meta_box_in_form',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices([
                        'side' => trans('plugins/toc::toc.settings.context.sidebar'),
                        'top' => trans('plugins/toc::toc.settings.context.top'),
                        'advanced' => trans('plugins/toc::toc.settings.context.advanced'),
                    ])
                    ->selected(ToCHelper::config('context_meta_box_in_form'))
                    ->toArray()
            )
            ->add('close_toc_setting', HtmlField::class, [
                'html' => '<fieldset>',
            ]);
    }
}
