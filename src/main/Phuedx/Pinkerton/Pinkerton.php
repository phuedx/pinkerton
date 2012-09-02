<?php

namespace Phuedx\Pinkerton;

class Pinkerton
{
    private static $investigations = array();

    public static function getSpy($callable)
    {
        return self::$investigations[$callable]['spy'];
    }

    public function spyOn($callable)
    {
        if (function_exists($callable)) {
            return $this->spyOnFunction($callable);
        }

        throw new \InvalidArgumentException("The function or method doesn't exist.");
    }

    public function stopSpyingOn($callable)
    {
        $this->stopSpyingOnFunction($callable);
    }

    private function spyOnFunction($function)
    {
        $newFunction = uniqid($function);
        runkit_function_copy($function, $newFunction);

        $spy = new Spy($newFunction);
        self::$investigations[$function] = array(
            'spy' => $spy,
            'new_function_name' => $newFunction,
        );

        $code = <<<'PHP'
$arguments = func_get_args();
$spy = \Phuedx\Pinkerton\Pinkerton::getSpy('%s');

return call_user_func_array($spy, $arguments);
PHP;

        runkit_function_redefine($function, '', sprintf($code, $function));

        return $spy;
    }

    private function stopSpyingOnFunction($function)
    {
        if ( ! isset(self::$investigations[$function])) {
            throw new \InvalidArgumentException("The function or method isn't being spied on.");
        }

        $investigation = self::$investigations[$function];

        runkit_function_remove($function);
        runkit_function_copy($investigation['new_function_name'], $function);
        runkit_function_remove($investigation['new_function_name']);
    }
}
