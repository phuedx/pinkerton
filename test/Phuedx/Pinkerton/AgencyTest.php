<?php

namespace Phuedx\Pinkerton\Test;

require __DIR__ . '/fixtures/dummy-functions.php';

class AgencyTest extends \PHPUnit_Framework_TestCase
{
    private $agency;

    public function setUp()
    {
        $this->agency = new \Phuedx\Pinkerton\Agency();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testItShouldThrowIfTheCallableIsntCallable()
    {
        $this->agency->investigate("the callable isn't callable");
    }

    public function testItShouldntThrowIfTheCallableIsCallable()
    {
        $this->agency->investigate('DummyFunction');
    }

    public function testItShouldSpyOnTheCallable()
    {
        $spy = $this->agency->investigate('DummyFunction2');

        $this->assertTrue($spy instanceof \Phuedx\Pinkerton\Spy);

        $spy->andCallThrough();

        dummyFunction2();

        $this->assertEquals(1, $spy->callCount);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testItShouldThrowIfItsNotSpyingOnTheCallable()
    {
        $this->agency->stopInvestigating("it's not spying on the callable");
    }

    public function testItShouldStopSpyingOnTheCallable()
    {
        $spy = $this->agency->investigate('DummyFunction3');

        $this->agency->stopInvestigating('DummyFunction3');
        
        dummyFunction3();

        $this->assertEquals(0, $spy->callCount);
    }
}
