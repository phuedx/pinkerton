<?php

namespace Test\Phuedx\Pinkerton;

require_once __DIR__ . '/fixtures/dummy-functions.php';
require_once __DIR__ . '/fixtures/dummy-class.php';

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

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage The DummyClass1::dummyMethod0 method doesn't exist
     */
    public function test_it_should_throw_when_trying_to_spy_on_a_method_that_doesnt_exist()
    {
        Pinkerton::spyOn(array('DummyClass1', 'dummyMethod0'));
    }

    public function test_it_should_spy_on_a_method()
    {
        $spy = Pinkerton::spyOn(array('DummyClass1', 'dummyMethod1'));

        \DummyClass1::dummyMethod1();

        $this->assertEquals($spy->callCount, 1);
    }

    public function test_it_should_pass_the_method_arguments_through_to_the_spy()
    {
      $spy = Pinkerton::spyOn(array('DummyClass1', 'dummyMethod1'));

      \DummyClass1::dummyMethod1('foo', 'bar');

      $this->assertEquals($spy->calls, array(
          array(
              'args' => array('foo', 'bar'),
          ),
      ));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage The DummyClass1::dummyMethod2 method isn't being spied on.
     */
    public function test_it_should_throw_when_trying_to_stop_spying_on_a_method_that_isnt_being_spied_on()
    {
        Pinkerton::stopSpyingOn(array('DummyClass1', 'dummyMethod2'));
    }

    public function test_it_should_stop_spying_on_a_method()
    {
        $method = array('DummyClass1', 'dummyMethod1');
        $spy = Pinkerton::spyOn($method);
        Pinkerton::stopSpyingOn($method);

        \DummyClass1::dummyMethod1('foo', 'bar');

        $this->assertEquals($spy->callCount, 0);
    }

    public function test_it_should_spy_on_a_method_specifed_by_an_instance_of_a_class()
    {
        $dummyClass = new \DummyClass1();
        $method = array($dummyClass, 'dummyMethod3');
        $spy = Pinkerton::spyOn($method);

        $dummyClass->dummyMethod3();

        $this->assertEquals($spy->callCount, 1);
    }

    public function test_it_should_stop_spying_on_a_method_specified_by_an_instance_of_a_class()
    {
        $dummyClass = new \DummyClass1();
        $method = array($dummyClass, 'dummyMethod3');
        $spy = Pinkerton::spyOn($method);
        Pinkerton::stopSpyingOn($method);

        $dummyClass->dummyMethod3();

        $this->assertEquals($spy->callCount, 0);
    }
}
