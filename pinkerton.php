<?php

require_once __DIR__ . '/vendor/autoload.php';

function spyOn($callable)
{
    static $agency;

    if ( ! $agency) {
        $agency = new \Phuedx\Pinkerton\Agency();
    }

    return $agency->investigate($callable);
}
