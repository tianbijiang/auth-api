<?php
/**
 * Created by PhpStorm.
 * User: savimonty
 * Date: 6/2/15
 * Time: 8:55 PM
 */

namespace App\Providers\SessionProvider;


use Illuminate\Session\SessionManager;

class MySessionManager extends SessionManager
{
    /**
     * All session drivers will now use our session storage class
     *
     * @param type $handler
     * @return \Extensions\Session\CustomSessionStore
     */
    protected function buildSession($handler)
    {
        return new MySessionStore($this->app['config']['session.cookie'], $handler);
    }
}