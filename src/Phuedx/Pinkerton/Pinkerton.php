<?php

namespace Phuedx\Pinkerton;

class Pinkerton
{
    private static $investigations = array();

    public static function spyOn($function)
    {
        if ( ! function_exists($function)) {
            throw new \InvalidArgumentException("The {$function} function doesn't exist.");
        }

        $spy = new Spy($function);
        $handler = function () use ($spy) {
            $arguments = func_get_args();

            return call_user_func_array($spy, $arguments);
        };
        uopz_function($function, $handler);

        self::$investigations[] = $function;

        return $spy;
    }

    public static function stopSpyingOn($function)
    {
        if ( ! in_array($function, self::$investigations)) {
            throw new \InvalidArgumentException("The {$function} function isn't being spied on.");
        }

        uopz_restore($function);
    }
}
