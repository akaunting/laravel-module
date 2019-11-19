<?php

if (!function_exists('module')) {
    /**
     * Get the Module instance
     *
     * @param string $alias
     *
     * @return mixed
     */
    function module($alias = null)
    {
        $module = app('module');

        if (is_null($alias)) {
            return $module;
        }

        return $module->get($alias);
    }
}

if (!function_exists('module_path')) {
    function module_path($alias, $path = '')
    {
        $module = app('module')->find($alias);

        return $module->getPath() . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     *
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param string $path
     *
     * @return string
     */
    function public_path($path = '')
    {
        return app()->make('path.public') . ($path ? '/' . $path : $path);
    }
}
