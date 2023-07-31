<?php
if (!function_exists('setActive')) {
    /**
     * setActive
     *
     *@param mixed $path
     *@return void
     */

    function setActive($path)
    {
        return Request::is($path . '*') ? 'active' : '';
    }
}
