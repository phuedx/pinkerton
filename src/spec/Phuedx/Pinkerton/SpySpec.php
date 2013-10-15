<?php

namespace Phuedx\Pinkerton\Spec;

use Phuedx\Pinkerton\Spy;

class DescribeSpy extends \PHPSpec\Context
{
    public function itShouldThrowIfTheCallableIsntCallable()
    {
        $this->spec(function () {
            new Spy("the callable isn't callable");
        })->should->throwException('InvalidArgumentException', "The function or method isn't callable.");
    }

    public function itShouldntHaveACallCountByDefault()
    {
        $spy = new Spy('microtime');

        $this->spec($spy->callCount)->should->be(0);
    }

    public function itShouldIncrementTheCallCountWhenInvoked()
    {
        $spy = new Spy('microtime');
        $spy();

        $this->spec($spy->callCount)->should->be(1);
    }

    public function itShouldntHaveAnyRecordedArgumentsByDefault()
    {
        $spy = new Spy('microtime');

        $this->spec(count($spy->calls))->should->be(0);
    }

    public function itShouldRecordTheArgumentsWhenInvoked()
    {
        $spy = new Spy('microtime');
        $spy(true);

        //$this->spec($spy->calls[0])->should->be(array(
        //    'args' => true
        //));

        $this->spec($spy->calls[0]['args'][0])->should->be(true);
    }

    public function itShouldntCallThroughToTheCallableByDefault()
    {
        $spy = new Spy('microtime');

        $this->spec($spy())->should->be(null);
    }

    public function itShouldCallThroughToTheCallable()
    {
        $spy = new Spy('microtime');
        $spy->andCallThrough();

        $this->spec($spy())->shouldNot->be(null);
    }

    public function itShouldThrowIfTheFakeIsntCallable()
    {
        $this->spec(function () {
            $spy = new Spy('microtime');
            $spy->andCallFake("the fake isn't callable.");
        })->should->throwException('InvalidArgumentException', "The function or method isn't callable.");
    }

    public function itShouldCallTheFake()
    {
        $arguments = null;

        $spy = new Spy('microtime');
        $spy->andCallFake(function () use (&$arguments) {
            $arguments = func_get_args();

            return 'it should call the fake.'; 
        });

        $result = $spy(1, 'two');

        $this->spec($result)->should->be('it should call the fake.');

        //$this->spec($arguments)->should->be(array(1, 'two'));

        $this->spec($arguments[0])->should->be(1);
        $this->spec($arguments[1])->should->be('two');
    }

    public function itShouldntHaveAMostRecentCallByDefault()
    {
        $spy = new Spy('microtime');

        $this->spec($spy->mostRecentCall)->should->be(null);
    }

    public function itShouldHaveAMostRecentCallWhenInvoked()
    {
        $spy = new Spy('microtime');
        $spy('three', 4);

        $arguments = $spy->mostRecentCall['args'];

        $this->spec($arguments[0])->should->be('three');
        $this->spec($arguments[1])->should->be(4);
    }

    public function itShouldReplaceTheMostRecentCallWhenInvokedMoreThanOnce()
    {
        $spy = new Spy('microtime');
        $spy(5, 'six');
        $spy('seven', 8);

        $arguments = $spy->mostRecentCall['args'];

        $this->spec($arguments[0])->should->be('seven');
        $this->spec($arguments[1])->should->be(8);
    }

    public function itShouldProvideAFluentInterface()
    {
        $spy = new Spy('microtime');

        // `Phuedx\Pinkerton\Spy` is callable
        $this->spec(function () use ($spy) {
            return $spy->andCallThrough();
        })->should->be($spy);

        $this->spec(function () use ($spy) {
            return $spy->andCallFake(function () {});
        })->should->be($spy);
    }

    public function itShouldntThrowIfTheCallableIsntGiven()
    {
        new Spy();
    }

    public function itShouldntTriggerAnErrorIfTheCallableIsntGivenAndCallingThroughToTheCallable()
    {
        $spy = new Spy();
        $spy->andCallThrough();
        $spy();
    }

    public function itShouldReturnTheValue()
    {
        $spy = new Spy();
        $spy->andReturn('it should return the value');
        $this->spec($spy())->should->be('it should return the value');
    }
}
