# Pinkerton [![Build Status](https://secure.travis-ci.org/phuedx/pinkerton.png?branch=master)](http://travis-ci.org/phuedx/pinkerton)

Emulating [Jasmine's](http://pivotal.github.com/jasmine/) `spyOn` function with [runkit](https://github.com/zenovich/runkit/).

## What does it do?

```php
<?php

require_once '/path/to/pinkerton.php';

function some_function($someParameter)
{
    // ...
}

$spy = spyOn('some_function');
$spy->andCallThrough();

some_function('The wizard quickly jinxed the gnomes before they vaporized.');

var_dump($spy->callCount); // int(1)
var_dump($spy->calls[0]); // array('The wizard quickly jinxed the gnomes before they vaporized.')
```

## How does it do it?

To spy on the function *F*:

1. Create a copy *F'* of *F* (using `runkit_function_copy`)
2. Create a spy *S* that wraps *F'*
3. Create a new function *F''* that invokes *S*
4. Replace *F* with *F''* (using `runkit_function_redefine`)

## TODO

* `spyOn($class, $method)`
* `spyOn($classInstance, $method)`

## License

**Pinkerton** is licensed under the MIT license and is copyright (c) 2012 Sam Smith. See the LICENSE file for full copyright and license information.