<?php

namespace Phuedx\Pinkerton;

class Spy
{
    public $callCount;
    public $calls;
    private $function;
    private $callThrough;

    public function __construct($function)
    {
        if ( ! is_callable($function)) {
            throw new \InvalidArgumentException("The function isn't callable.");
        }

        $this->function = $function;
        $this->callThrough = false;
        $this->callCount = 0;
        $this->calls = array();
    }

    public function __invoke()
    {
        ++$this->callCount;

        $arguments = func_get_args();
        $this->calls[] = $arguments;

        if ($this->callThrough) {
            return call_user_func_array($this->function, $arguments); 
        }

        return null;
    }

    public function andCallThrough()
    {
        $this->callThrough = true;
    }
}
