# Pinkerton [![Build Status](https://secure.travis-ci.org/phuedx/pinkerton.png?branch=master)](http://travis-ci.org/phuedx/pinkerton)

So you’re using a function in your code. Maybe it’s even an *internal* function. You’re crazy like that. It’s `microtime` isn’t it? You’re writing your tests and you want to make an assertion about how it’s called or stub its behaviour.

You should use **Pinkerton**.

**Pinkerton** allows you to spy on or stub the behaviour of functions using the [runkit](https://github.com/zenovich/runkit/) extension wrapped up in a port of [Jasmine's](http://pivotal.github.com/jasmine/) `spyOn` DSL.

## Spyin’

```php
<?php

function legacy_function($parameter1)
{
    // Do all of the things.
}

$spy = spyOn(‘legacy_function’)->andCallThrough();
$legacyParameter = 1;
legacy_function($legacyParameter);
var_dump($spy->mostRecentCall); // [‘args’ => [1]]
```

## Stubbin’

```php
$spy = spyOn(‘legacy_function’)->andCallFake(function() {
    return false;
});
var_dump(legacy_function($legacyParameter)); // false
```

## License

**Pinkerton** is licensed under the MIT license and is copyright (c) 2012-2013 Sam Smith. See the LICENSE file for full copyright and license information.
