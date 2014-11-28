<?php

require_once __DIR__.'/Phuedx/Pinkerton/Spy.php';
require_once __DIR__.'/Phuedx/Pinkerton/Pinkerton.php';

use Phuedx\Pinkerton\Pinkerton;

function spyOn($callable)
{
    return Pinkerton::spyOn($callable);
}

function stopSpyingOn($callable)
{
    Pinkerton::stopSpyingOn($callable);
}

function createSpy()
{
    return new Phuedx\Pinkerton\Spy();
}
