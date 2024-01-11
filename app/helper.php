<?php

use App\Models\general_setting;

if (!function_exists('generalSetting')) {
    function generalSetting(){
        return general_setting::first() ?? '';
    }
}

?>