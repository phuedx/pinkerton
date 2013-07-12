<?php

namespace Phuedx\Pinkerton\Spec;

require_once __DIR__ . '/fixtures/dummy-functions.php';

use Phuedx\Pinkerton\Pinkerton;

class DescribePinkerton extends \PHPSpec\Context
{
    public function itShouldThrowWhenTryingToSpyOnAFunctionThatDoesntExist()
    {
        $this->spec(function () {
            Pinkerton::spyOn("a function doesn't exist.");
        })->should->throwException('InvalidArgumentException', "The function or method doesn't exist.");
    }

    public function itShouldSpyOnAFunction()
    {
        $spy = Pinkerton::spyOn('dummyFunction1');

        dummyFunction1();

        $this->spec($spy->callCount)->should->be(1);
    }

    public function itShouldPassTheFunctionArgumentsThroughToTheSpy()
    {
        $spy = Pinkerton::spyOn('dummyFunction2');

        dummyFunction2('foo', 'bar');

        //$this->spec($spy->calls[0]['args'])->should->be(array('foo', 'bar'));

        $this->spec($spy->calls[0]['args'][0])->should->be('foo');
        $this->spec($spy->calls[0]['args'][1])->should->be('bar');
    }

    public function itShouldThrowWhenTryingToStopSpyingOnAFunctionThatIsntBeingSpiedOn()
    {
        $this->spec(function () {
            Pinkerton::stopSpyingOn('dummyFunction4');
        })->should->throwException('InvalidArgumentException', "The function or method isn't being spied on.");
    }

    public function itShouldStopSpyingOnAFunction()
    {
        $spy = Pinkerton::spyOn('dummyFunction3');
        Pinkerton::stopSpyingOn('dummyFunction3');

        dummyFunction3();

        $this->spec($spy->callCount)->should->be(0);
    }
}
