<?php

Route::group(['namespace' => 'Plugin\ToC\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'settings'], function () {
            Route::group([
                'prefix' => 'toc', 
                'permission' => 'settings.options',
                'as' => 'plugins.toc.',
            ], function () {
                Route::controller('ToCController')->group(function () {
                    Route::get('', [
                        'as' => 'settings',
                        'uses' => 'ToCController@settings',
                    ]);
        
                    Route::post('edit', [
                        'as' => 'settings.post',
                        'uses' => 'ToCController@postSettings',
                    ]);
    
                    Route::post('restore-factory', [
                        'as' => 'settings.restore-factory',
                        'uses' => 'ToCController@restoreFactory',
                    ]);
                });
            });
        });
    });
});
