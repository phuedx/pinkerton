<?php

require_once __DIR__ . '/src/main/Phuedx/Pinkerton/Spy.php';
require_once __DIR__ . '/src/main/Phuedx/Pinkerton/Pinkerton.php';

function spyOn($callable)
{
    return _pinkerton()->spyOn($callable);
}

function stopSpyingOn($callable)
{
    _pinkerton()->stopSpyingOn($callable);
}

function _pinkerton()
{
    static $pinkerton;

    if ( ! $pinkerton) {
        $pinkerton = new \Phuedx\Pinkerton\Pinkerton();
    }

    return $pinkerton;
}
