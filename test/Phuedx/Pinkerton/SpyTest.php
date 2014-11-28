<?php

namespace test\Phuedx\Pinkerton;

use Phuedx\Pinkerton\Spy;

class SpyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage The function or method isn't callable.
     */
    public function test_it_should_throw_if_the_callable_isnt_callable()
    {
        new Spy("the callable isn't callable");
    }

    public function test_it_shouldnt_have_a_call_count_by_default()
    {
        $spy = new Spy('microtime');

        $this->assertEquals($spy->callCount, 0);
    }

    public function test_it_should_increment_the_call_count_when_invoked()
    {
        $spy = new Spy('microtime');
        $spy();

        $this->assertEquals($spy->callCount, 1);
    }

    public function test_it_shouldnt_have_any_recorded_arguments_by_default()
    {
        $spy = new Spy('microtime');

        $this->assertEquals(count($spy->calls), 0);
    }

    public function test_it_should_record_the_arguments_when_invoked()
    {
        $spy = new Spy('microtime');
        $spy(true);

        $this->assertEquals($spy->calls, [
            [
                'args' => [true],
            ],
        ]);
    }

    public function test_it_shouldnt_call_through_to_the_callable_by_default()
    {
        $spy = new Spy('microtime');

        $this->assertNull($spy());
    }

    public function test_it_should_call_through_to_the_callable()
    {
        $spy = new Spy('microtime');
        $spy->andCallThrough();

        $this->assertNotNull($spy());
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage The function or method isn't callable.
     */
    public function test_it_should_throw_if_the_fake_isnt_callable()
    {
        $spy = new Spy('microtime');
        $spy->andCallFake("the fake isn't callable");
    }

    public function test_it_should_call_the_fake()
    {
        $arguments = null;

        $spy = new Spy('microtime');
        $spy->andCallFake(function () use (&$arguments) {
            $arguments = func_get_args();

            return 'it should call the fake.';
        });

        $result = $spy(1, 'two');

        $this->assertEquals($result, 'it should call the fake.');
        $this->assertEquals($arguments, [1, 'two']);
    }

    public function test_it_shouldnt_have_a_most_recent_call_by_default()
    {
        $spy = new Spy('microtime');

        $this->assertEquals($spy->mostRecentCall, null);
    }

    public function test_it_should_have_a_most_recent_call_when_invoked()
    {
        $spy = new Spy('microtime');
        $spy('three', 4);

        $arguments = $spy->mostRecentCall['args'];

        $this->assertEquals($arguments, ['three', 4]);
    }

    public function test_it_should_replace_the_most_recent_call_when_invoked_more_than_once()
    {
        $spy = new Spy('microtime');
        $spy(5, 'six');
        $spy('seven', 8);

        $arguments = $spy->mostRecentCall['args'];

        $this->assertEquals($arguments, ['seven', 8]);
    }

    public function test_it_should_provide_a_fluent_interface()
    {
        $spy = new Spy('microtime');

        $this->assertEquals($spy->andCallThrough(), $spy);
        $this->assertEquals($spy->andCallFake(function () {}), $spy);
    }

    public function test_it_shouldnt_throw_if_the_callable_isnt_given()
    {
        new Spy();
    }

    public function test_it_shouldnt_trigger_an_error_if_the_callable_isnt_given_and_calling_through_to_the_callable()
    {
        $spy = new Spy();
        $spy->andCallThrough();
        $spy();
    }

    public function test_it_should_return_the_value()
    {
        $spy = new Spy();
        $spy->andReturn('it should return the value');

        $this->assertEquals($spy(), 'it should return the value');
    }
}
