<?php

namespace Plugin\ToC\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Supports\SettingStore;
use Plugin\ToC\Forms\Settings\ToCSettingForm;
use Plugin\ToC\Http\Requests\ToCSettingRequest;
use Plugin\ToC\Plugin;
use Illuminate\Support\Arr;

class ToCController extends BaseController
{
    public function __construct(protected SettingStore $settingStore)
    {
    }

    public function settings()
    {
        page_title()->setTitle(trans('plugins/toc::toc.settings.title'));

        if (version_compare('7.0.0', get_core_version(), '>')) {
            return view('plugins/toc::settings-v6');
        }

        $this->breadcrumb()
            ->add(trans('core/setting::setting.title'), route('settings.index'))
            ->add(trans('plugins/toc::toc.settings.title'));

        return ToCSettingForm::create()->renderForm();
    }

    public function postSettings(ToCSettingRequest $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $validated = $request->validated();

        foreach ($validated as $key => $value) {
            if (config('plugins.toc.general.' . $key) == $value) {
                Arr::forget($validated, $key);
            }
        }

        $this->settingStore->set('plugin_toc_settings', $validated ? json_encode($validated) : '');

        $this->settingStore->save();

        return $response
            ->setPreviousUrl(route('plugins.toc.settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function restoreFactory(BaseHttpResponse $response): BaseHttpResponse
    {
        Plugin::remove();

        return $response
            ->setPreviousUrl(route('plugins.toc.settings'))
            ->setMessage(trans('plugins/toc::toc.settings.restore_factory.successfully'));
    }
}
