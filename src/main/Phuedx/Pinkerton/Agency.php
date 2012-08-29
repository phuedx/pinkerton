<?php

namespace Phuedx\Pinkerton;

class Agency
{
    private static $state = array();

    public static function getSpy($callable)
    {
        return self::$state[$callable]['spy'];
    }

    public function investigate($callable)
    {
        if ( ! is_callable($callable)) {
            throw new \InvalidArgumentException("The agency can't investigate something that can't be called.");
        }

        $newCallable = uniqid($callable);
        runkit_function_copy($callable, $newCallable);

        $spy = new Spy($newCallable);
        self::$state[$callable] = array(
            'callable' => $callable,
            'new_callable' => $newCallable,
            'spy' => $spy,
        );

        $code = <<<'PHP'
$arguments = func_get_args();
$spy = \Phuedx\Pinkerton\Agency::getSpy('%s');

return call_user_func_array($spy, $arguments);
PHP;

        runkit_function_redefine($callable, '', sprintf($code, $callable));

        return $spy;
    }

    public function stopInvestigating($callable)
    {
        if ( ! isset(self::$state[$callable])) {
            throw new \InvalidArgumentException("The agency isn't investigating that callable.");
        }

        $state = self::$state[$callable];

        runkit_function_remove($state['callable']);
        runkit_function_copy($state['new_callable'], $state['callable']);
        runkit_function_remove($state['new_callable']);
    }
}
