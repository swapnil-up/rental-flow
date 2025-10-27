<?php

namespace App;

use Illuminate\Foundation\Application as BaseApplication;

class Application extends BaseApplication
{
    protected $namespace = 'App\\';

    public function path($path = '')
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'app' . 
               DIRECTORY_SEPARATOR . 'App' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}