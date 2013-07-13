<?php

namespace Phuedx\Pinkerton;

class Pinkerton
{
    private static $investigations = array();

    public static function getSpy($function)
    {
        return self::$investigations[$function]['spy'];
    }

    public static function spyOn($function)
    {
        if ( ! function_exists($function)) {
            throw new \InvalidArgumentException("The {$function} function doesn't exist.");
        }

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

    public static function stopSpyingOn($function)
    {
        if ( ! isset(self::$investigations[$function])) {
            throw new \InvalidArgumentException("The {$function} function isn't being spied on.");
        }

        $investigation = self::$investigations[$function];

        runkit_function_remove($function);
        runkit_function_copy($investigation['new_function_name'], $function);
        runkit_function_remove($investigation['new_function_name']);
    }
}
