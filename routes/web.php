<?php

use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\Route;
use Plugin\ToC\Http\Controllers\ToCController;

Route::group(['controller' => ToCController::class, 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group([
            'prefix' => 'settings/toc',
            'permission' => 'settings.options',
            'as' => 'plugins.toc.',
        ], function () {
            Route::get('', [
                'as' => 'settings',
                'uses' => 'settings',
            ]);

            Route::post('edit', [
                'as' => 'settings.post',
                'uses' => 'postSettings',
            ]);

            Route::post('restore-factory', [
                'as' => 'settings.restore-factory',
                'uses' => 'restoreFactory',
            ]);
        });
    });
});
