<?php

if (! function_exists('config')) {
    /**
     * Get current user model instance.
     *
     * @param string    $configPath     Path of config
     *
     * @return array|null
     */
    function config(string $configPath): ?array
    {

        return include('Configs/' . $configPath . '.php');
    }
}
