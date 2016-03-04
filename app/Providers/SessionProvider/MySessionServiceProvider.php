<?php
/**
 * Created by PhpStorm.
 * User: savimonty
 * Date: 6/2/15
 * Time: 8:59 PM
 */

namespace App\Providers\SessionProvider;


use \Illuminate\Session\SessionServiceProvider;

class MySessionServiceProvider extends SessionServiceProvider
{
    protected function registerSessionManager()
    {
        $this->app['session'] = $this->app->share(function($app)
        {
            return new MySessionManager($app);
        });
    }
}