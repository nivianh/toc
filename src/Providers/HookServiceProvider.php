<?php

namespace Plugin\ToC\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use MetaBox;
use Theme;
use ToCHelper;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, [$this, 'addToCContent'], 56, 2);
        add_action(BASE_ACTION_META_BOXES, [$this, 'addFieldsInFormScreen'], 120, 3);
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveFieldsInFormScreen'], 75, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveFieldsInFormScreen'], 75, 3);
    }

    public function addToCContent(string $screen, mixed $object)
    {
        if ($object && ToCHelper::isSupportedModel(get_class($object))) {
            if (ToCHelper::config('show_options_in_form') == 'no') {
                $showToC = 'default';
            } else {
                $showToC = $object->getMetaData('show_toc_in_content', true) ?: 'default';
            }

            if ($showToC == 'yes' || ($showToC == 'default' && ToCHelper::config('is_enabled') == 'yes')) {
                Theme::asset()->add('toc-css', 'vendor/core/plugins/toc/css/toc.css');

                Theme::asset()
                    ->container('footer')
                    ->add('toc-js', 'vendor/core/plugins/toc/js/toc.js', ['jquery']);

                $object->content = ToCHelper::theContent($object->content);
            }
        }
    }

    public function addFieldsInFormScreen(string $context, mixed $object)
    {
        if ($object && ToCHelper::isSupportedModel(get_class($object)) && ToCHelper::config('show_options_in_form') == 'yes') {
            if ($context == ToCHelper::config('context_meta_box_in_form')) {
                MetaBox::addMetaBox(
                    'additional_toc_fields',
                    trans('plugins/toc::toc.show_toc'),
                    function () {
                        $args = func_get_args();
                        $showToC = 'default';
                        if (!empty($args[0])) {
                            $data = $args[0];
                            $showToC = $data->getMetaData('show_toc_in_content', true);
                        }

                        return view('plugins/toc::options-in-form', compact('showToC'))->render();
                    },
                    get_class($object),
                    $context
                );
            }
        }
    }

    public function saveFieldsInFormScreen(string $type, Request $request, mixed $object)
    {
        if ($object && ToCHelper::isSupportedModel(get_class($object)) && ToCHelper::config('show_options_in_form') == 'yes') {
            $showToC = $request->input('show_toc_in_content');
            if (in_array($showToC, ['default', 'yes', 'no'])) {
                MetaBox::saveMetaBoxData($object, 'show_toc_in_content', $showToC);
            }
        }
    }
}
