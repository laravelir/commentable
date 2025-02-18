<?php

namespace Laravelir\Commentable\Traits;

trait RouteKeyNameUUID
{
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
