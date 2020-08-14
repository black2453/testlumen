<?php

function setSession($account)
{
    $_SESSION['account'] = $account;
}

function getSession($key)
{
    if (isset($_SESSION[$key])) {
        if (isset($item) && isset($_SESSION[$key][$item])) {
            return $_SESSION[$key][$item];
        }

        return $_SESSION[$key];
    }

    return null; //not found
}

function forgetSession()
{
    unset($_SESSION['account']);
}

function public_path($path = '')
{
//    return app()->publicPath($path);
    return base_path('public/'.$path);
}
