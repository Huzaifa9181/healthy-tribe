<?php

if (!function_exists('getModelDestroyRoute')) {
    function getModelDestroyRoute($model) {
        return route($model . '.destroy');
    }
}

?>