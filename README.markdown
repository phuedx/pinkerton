# Pinkerton

Emulating [Jasmine](http://pivotal.github.com/jasmine/)'s `spyOn` function with [runkit](https://github.com/zenovich/runkit/).

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

Say we want to spy on the function *F*

1. Create a copy *C* of *F* (using `runkit_function_copy`)
2. Create a spy *S* that wraps *C*
3. Create a new function *F'* that invokes *S*
4. Replace *F* with *F'* (using `runkit_function_redefine`)

## TODO

* `spyOn($class, $method)`
* `spyOn($classInstance, $method)`

## License

**Pinkerton** is licensed under the MIT license and is copyright (c) 2011 Sam Smith. See the LICENSE file for full copyright and license information.