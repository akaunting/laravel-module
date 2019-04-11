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
    function module_path($alias)
    {
        $module = app('module')->find($alias);

        return $module->getPath();
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
     9
     * @return string
     */
    function public_path($path = '')
    {
        return app()->make('path.public') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}
