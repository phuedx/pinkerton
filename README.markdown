# Pinkerton [![Build Status](https://secure.travis-ci.org/phuedx/pinkerton.png?branch=master,develop)](http://travis-ci.org/phuedx/pinkerton)

So you’re calling some legacy function. Maybe it’s even an *internal* function – it’s `microtime` isn’t it? You’re writing your tests and you want to make an assertion about how it’s called or stub its behaviour.

You should use **Pinkerton**.

**Pinkerton** allows you to spy on or stub the behaviour of functions and methods using [**@krakjoe**'s UOPZ extension](https://github.com/krakjoe/uopz) wrapped up in a port of [Jasmine's](https://jasmine.github.io/) `spyOn` function.

## Spyin’

```php
function legacy_function($parameter)
{
    // Do all of the things.
}

$spy = spyOn('legacy_function')->andCallThrough();
$legacyParameter = 1;
legacy_function($legacyParameter);
var_dump($spy->mostRecentCall); // [‘args’ => [1]]
```

## Stubbin’

```php
$spy = spyOn('legacy_function')->andCallFake(function() {
    return false;
});
var_dump(legacy_function($legacyParameter)); // false
```

## Testin’

So you're writing a test for a method that accepts a `callable` argument. Wellp, you can pass in a **Pinkerton** spy, call the method with the spy as an argument, and then make some assertions about how it was called.

```php
class FooTest extends PHPUnit_Framework_TestCase
{
    public function test_bar_should_call_the_callable()
    {
        $spy = createSpy();
        $foo = new Foo();
        $foo->bar($spy);
        $this->assertEquals($spy->callCount, 1);
    }
}
```

## API

```php
/**
 * Spies on the function or method.
 *
 * The function or method is replaced with a handler that will invoke a spy
 * that wraps the original function or method.
 *
 * Note that when spying on a method, the method is replaced with the handler
 * for all instances of the class.
 *
 * @param callable $function
 * @return \Phuedx\Pinkerton\Spy The spy that will be invoked instead of the
 *  function or method
 */
function spyOn($function) {}

/**
 * Stops spying on the function or method.
 *
 * The original function or method is restored but the spy is unaffected.
 *
 * @param callable $function
 * @throws \InvalidArgumentException When the function or method isn't being
 *  spied on
 */
function stopSpyingOn($function) {}

/**
 * Creates a spy that doesn't wrap a function or method.
 *
 * @return \Phuedx\Pinkerton\Spy
 */
function createSpy() {}
```

## License

**Pinkerton** is licensed under the MIT license and is copyright (c) 2012-2014 Sam Smith. See the LICENSE file for full copyright and license information.
