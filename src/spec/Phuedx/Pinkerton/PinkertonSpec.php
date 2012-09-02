<?php

namespace Phuedx\Pinkerton\Spec;

require_once __DIR__ . '/fixtures/dummy-functions.php';

use Phuedx\Pinkerton\Pinkerton;

class DescribePinkerton extends \PHPSpec\Context
{
    public function itShouldThrowWhenTryingToSpyOnAFunctionThatDoesntExist()
    {
        $this->spec(function () {
            $pinkerton = new Pinkerton();
            $pinkerton->spyOn("a function doesn't exist.");
        })->should->throwException('InvalidArgumentException', "The function or method doesn't exist.");
    }

    public function itShouldSpyOnAFunction()
    {
        $pinkerton = new Pinkerton();
        $spy = $pinkerton->spyOn('dummyFunction1');

        dummyFunction1();

        $this->spec($spy->callCount)->should->be(1);
    }

    public function itShouldPassTheFunctionArgumentsThroughToTheSpy()
    {
        $pinkerton = new Pinkerton();
        $spy = $pinkerton->spyOn('dummyFunction2');

        dummyFunction2('foo', 'bar');

        //$this->spec($spy->calls[0]['args'])->should->be(array('foo', 'bar'));

        $this->spec($spy->calls[0]['args'][0])->should->be('foo');
        $this->spec($spy->calls[0]['args'][1])->should->be('bar');
    }

    public function itShouldThrowWhenTryingToStopSpyingOnAFunctionThatIsntBeingSpiedOn()
    {
        $this->spec(function () {
            $pinkerton = new Pinkerton();
            $pinkerton->stopSpyingOn('dummyFunction4');
        })->should->throwException('InvalidArgumentException', "The function or method isn't being spied on.");
    }

    public function itShouldStopSpyingOnAFunction()
    {
        $pinkerton = new Pinkerton();
        $spy = $pinkerton->spyOn('dummyFunction3');
        $pinkerton->stopSpyingOn('dummyFunction3');

        dummyFunction3();

        $this->spec($spy->callCount)->should->be(0);
    }
}
