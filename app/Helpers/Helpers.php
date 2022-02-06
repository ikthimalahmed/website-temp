<?php


if (! function_exists('is_public_path')) {
    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
    function is_public_path($fullRoute)
    {
        $publicPaths = explode(',',  config('access_control.public_paths'));
        return in_array(trim($fullRoute), array_map('trim', $publicPaths));
    }

    function dddd(...$args){
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        http_response_code(500);

        foreach ($args as $x) {
            (new Symfony\Component\VarDumper\VarDumper)->dump($x);
        }

        die(1);
    }
}