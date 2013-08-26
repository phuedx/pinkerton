<?php

namespace Phuedx\Pinkerton;

class Spy
{
    public $callCount;
    public $calls;
    public $mostRecentCall;
    private $callable;
    private $callThrough;
    private $fake;

    public function __construct($callable = null)
    {
        if ($callable && ! is_callable($callable)) {
            throw new \InvalidArgumentException("The function or method isn't callable.");
        }

        $this->callable = $callable;
        $this->callCount = 0;
        $this->calls = array();
        $this->callThrough = false;
    }

    public function __invoke()
    {
        ++$this->callCount;

        $arguments = func_get_args();
        $call = array(
            'args' => $arguments,
        );
        $this->calls[] = $call;
        $this->mostRecentCall = $call;

        if ($this->callable && $this->callThrough) {
            return call_user_func_array($this->callable, $arguments); 
        }

        if ($this->fake) {
            return call_user_func_array($this->fake, $arguments);
        }

        return null;
    }

    public function andCallThrough()
    {
        $this->callThrough = true;

        return $this;
    }

    public function andCallFake($fake)
    {
        if ( ! is_callable($fake)) {
            throw new \InvalidArgumentException("The function or method isn't callable.");
        }

        $this->fake = $fake;

        return $this;
    }
}
