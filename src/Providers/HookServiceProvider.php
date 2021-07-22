<?php

namespace Botble\ToC\Providers;

use Botble\Base\Models\BaseModel;
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

    /**
     * @param string $screen
     * @param BaseModel $object
     */
    public function addToCContent($screen, $object)
    {
        if ($object && ToCHelper::isSupportedModel(get_class($object))) {
            $showToC = MetaBox::getMetaData($object, 'show_toc_in_content',
                true) ?: config('plugins.toc.general.default_option_in_form');

            if ($showToC == 'yes' || !ToCHelper::config('show_option_in_form')) {
                Theme::asset()
                    ->usePath(false)
                    ->add('toc-css', 'vendor/core/plugins/toc/css/toc.css');

                Theme::asset()
                    ->container('footer')
                    ->usePath(false)
                    ->add('toc-js', 'vendor/core/plugins/toc/js/toc.js', ['jquery']);

                $object->content = ToCHelper::theContent($object->content);
            }
        }
    }

    /**
     * @param string $context
     * @param BaseModel $object
     */
    function addFieldsInFormScreen($context, $object)
    {
        if ($object && ToCHelper::isSupportedModel(get_class($object)) && ToCHelper::config('show_option_in_form')) {
            $contextConfig = ToCHelper::config('context_sidebar_in_form');

            if ($context == $contextConfig) {
                $title = __('Show Table of Content?');
                MetaBox::addMetaBox(
                    'additional_toc_fields',
                    $title,
                    function () {
                        $args = func_get_args();
                        $showToC = config('plugins.toc.general.default_option_in_form');
                        if (!empty($args[0])) {
                            $data = $args[0];
                            $showToC = MetaBox::getMetaData($data, 'show_toc_in_content', true);
                        }

                        return view('plugins/toc::options-in-form', compact('showToC'))->render();
                    },
                    get_class($object),
                    $context
                );
            }
        }
    }

    /**
     * @param string $type
     * @param Request $type
     * @param BaseModel $object
     */
    function saveFieldsInFormScreen($type, Request $request, $object)
    {
        if ($object && ToCHelper::isSupportedModel(get_class($object))) {
            $showToC = $request->input('show_toc_in_content');
            if (in_array($showToC, ['yes', 'no'])) {
                MetaBox::saveMetaBoxData($object, 'show_toc_in_content', $showToC);
            }
        }
    }
}
