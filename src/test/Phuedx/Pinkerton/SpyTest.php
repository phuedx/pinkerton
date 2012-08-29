<?php

namespace Phuedx\Pinkerton\Test;

use Phuedx\Pinkerton\Spy;

class SpyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testItShouldThrowIfPassedAFunctionThatIsntCallable()
    {
        new Spy("a function that isn't callable");
    }

    public function testItShouldntThrowIfPassedAFunctionThatIsCallable()
    {
        new Spy(function () {});
    }

    public function testItShouldntCallThroughByDefault()
    {
        $called = false;
        $spy = new Spy(function () use (&$called) {
            $called = true;
        });

        $this->assertFalse($called);
    }

    public function testItShouldCallThrough()
    {
        $called = false;
        $spy = new Spy(function () use (&$called) {
            $called = true;
        });

        $spy->andCallThrough();
        $spy();

        $this->assertTrue($called);
    }

    public function testItReturnsTheResultOfTheFunctionWhenItCallsThrough()
    {
        $result = M_PI;
        $spy = new Spy(function () use ($result) {
            return $result;
        });

        $spy->andCallThrough();

        $this->assertEquals($result, $spy());
    }

    public function testItPassesTheArgumentsWhenItCallsThrough()
    {
        $expectedLeft = 'left';
        $expectedRight = 'right';
        $actualLeft = $actualRight = null;
        $spy = new Spy(function ($left, $right) use (&$actualLeft, &$actualRight) {
            $actualLeft = $left;
            $actualRight = $right;
        }); 

        $spy->andCallThrough();
        $spy($expectedLeft, $expectedRight);

        $this->assertEquals($actualLeft, $expectedLeft);
        $this->assertEquals($actualRight, $expectedRight);
    }

    public function testTheCallCountIs0ByDefault()
    {
        $spy = new Spy(function () {});

        $this->assertEquals(0, $spy->callCount);
    }

    public function testItShouldIncrementTheCallCountWhenItDoesntCallThrough()
    {
        $spy = new Spy(function () {});
        $spy();

        $this->assertEquals(1, $spy->callCount);
    }

    public function testItShouldIncrementTheCallCountWhenItCallsThrough()
    {
        $spy = new Spy(function () {});
        $spy->andCallThrough();
        $spy();

        $this->assertEquals(1, $spy->callCount);
    }

    public function testThereAreNoRecordedCallsByDefault()
    {
        $spy = new Spy(function () {});

        $this->assertEquals(array(), $spy->calls);
    }

    public function testItShouldRecordCallsWhenItDoesntCallThrough()
    {
        $arguments = array("when it doesn't call through");
        $spy = new Spy(function () {});

        call_user_func_array($spy, $arguments);

        $this->assertEquals(1, count($spy->calls));
        $this->assertEquals($arguments, $spy->calls[0]);
    }

    public function testItShouldRecordCallsWhenItCallsThrough()
    {
        $arguments = array('when it calls through');
        $spy = new Spy(function () {});
        $spy->andCallThrough();

        call_user_func_array($spy, $arguments);

        $this->assertEquals(1, count($spy->calls));
        $this->assertEquals($arguments, $spy->calls[0]);
    }
}
