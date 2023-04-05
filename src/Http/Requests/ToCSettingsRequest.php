<?php

namespace Plugin\ToC\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ToCSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'is_enabled' => Rule::in(['yes', 'no']),
            'heading_levels' => 'array',
            'heading_levels.*' => Rule::in(['1', '2', '3', '4', '5', '6']),
            'position' => Rule::in(['before-first-heading', 'after-first-heading', 'top', 'bottom']),
            'start' => 'numeric|min:0',
            'css_container_class' => 'nullable|string|max:120',
            'show_options_in_form' => Rule::in(['yes', 'no']),
            'context_meta_box_in_form' => Rule::in(['side', 'top', 'advanced']),
            'show_hierarchy' => Rule::in(['yes', 'no']),
            'ordered_list' => Rule::in(['yes', 'no']),
            'anchor_prefix' => 'nullable|string|max:120',
        ];
    }
}
