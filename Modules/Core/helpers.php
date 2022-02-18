<?php

use Illuminate\Support\Str;

if (! function_exists('report_exception')) {
    /**
     * @param $exception
     * @param string $severity
     */
    function report_exception($exception, $severity = 'info')
    {
        if (\App::environment('production')) {
            //\Bugsnag::notifyException($exception, null, $severity);
        }
        \Log::error($exception);
    }
}

