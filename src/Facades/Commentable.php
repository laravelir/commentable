<?php

namespace Laravelir\Commentable\Facades;

use Illuminate\Support\Facades\Facade;

class Commentable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'commentable';
    }
}
