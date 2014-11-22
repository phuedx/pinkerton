<?php

namespace Test\Phuedx\Pinkerton;

require_once __DIR__ . '/fixtures/dummy-functions.php';

use Phuedx\Pinkerton\Pinkerton;

class PinkertonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage The dummyFunction0 function doesn't exist
     */
    public function test_it_should_throw_when_trying_to_spy_on_a_function_that_doesnt_exist()
    {
        Pinkerton::spyOn('dummyFunction0');
    }

    public function test_it_should_spy_on_a_function()
    {
        $spy = Pinkerton::spyOn('dummyFunction1');

        dummyFunction1();

        $this->assertEquals($spy->callCount, 1);
    }

    public function test_it_should_pass_the_function_arguments_through_to_the_spy()
    {
        $spy = Pinkerton::spyOn('dummyFunction2');

        dummyFunction2('foo', 'bar');

        $this->assertEquals($spy->calls, array(
            array(
                'args' => array('foo', 'bar'),
            ),
        ));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage The dummyFunction4 function isn't being spied on.
     */
    public function test_it_should_throw_when_trying_to_stop_spying_on_a_function_that_isnt_being_spied_on()
    {
        Pinkerton::stopSpyingOn('dummyFunction4');
    }

    public function test_it_should_stop_spying_on_a_function()
    {
        $spy = Pinkerton::spyOn('dummyFunction3');
        Pinkerton::stopSpyingOn('dummyFunction3');

        dummyFunction3();

        $this->assertEquals($spy->callCount, 0);
    }
}
