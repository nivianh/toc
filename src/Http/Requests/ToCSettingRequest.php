<?php

namespace Plugin\ToC\Http\Requests;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ToCSettingRequest extends Request
{
    public function rules(): array
    {
        $onOffRule = Rule::in(['yes', 'no']);

        if (version_compare('7.0.0', get_core_version(), '<')) {
            $onOffRule = new OnOffRule();
        }

        return [
            'is_enabled' => $onOffRule,
            'heading_levels' => 'array',
            'heading_levels.*' => Rule::in(['1', '2', '3', '4', '5', '6']),
            'position' => Rule::in(['before-first-heading', 'after-first-heading', 'top', 'bottom']),
            'start' => 'numeric|min:0',
            'css_container_class' => 'nullable|string|max:120',
            'show_options_in_form' => $onOffRule,
            'context_meta_box_in_form' => Rule::in(['side', 'top', 'advanced']),
            'show_hierarchy' => $onOffRule,
            'ordered_list' => $onOffRule,
            'anchor_prefix' => 'nullable|string|max:120',
        ];
    }
}
