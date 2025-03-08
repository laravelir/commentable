<?php

if (!function_exists('test')) {
    function test()
    {
        return true;
    }
}

//  function checkCommentableModelValidity(Model $model): bool
// {
//     throw_unless(is_a($model, CommentableContract::class), InvalidModelException::make('Model must use the ' . CommentableContract::class . ' interface'));

//     return true;
// }


//  function checkCommenterModelValidity(Authenticatable $model): bool
// {
//     throw_unless(is_a($model, CommenterContract::class), InvalidModelException::make('Model must use the ' . CommenterContract::class . ' interface'));

//     return true;
// }

//  function isDefaultTheme(): bool
// {
//     return config('comments.theme') === 'default';
// }

//  function isGithubTheme(): bool
// {
//     return config('comments.theme') === 'github';
// }

//  function isModernTheme(): bool
// {
//     return config('comments.theme') === 'modern';
// }

//  function getAuthGuard(): StatefulGuard
// {
//     if (SecureGuestMode::enabled()) {
//         return Auth::guard('guest');
//     }

//     if (config('comments.auth_guard') === 'default') {
//         return Auth::guard(Auth::getDefaultDriver());
//     }

//     return config('comments.auth_guard');
// }
