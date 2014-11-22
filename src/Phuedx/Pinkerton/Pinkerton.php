<?php

namespace Phuedx\Pinkerton;

class Pinkerton
{
    private static $investigations = array();

    public static function spyOn($function)
    {
        if (is_array($function)) {
            return self::spyOnMethod($function);
        }

        return self::spyOnFunction($function);
    }

    private static function spyOnMethod($function) {
        list($class, $method) = $function;
        $className = is_object($class) ? get_class($class) : $class;
        $methodString = "{$className}::{$method}";

        if ( ! method_exists($className, $method)) {
            throw new \InvalidArgumentException("The {$methodString} method doesn't exist.");
        }

        $spy = new Spy($function);
        $handler = self::createHandler($spy);
        uopz_function($className, $method, $handler);

        self::$investigations[$methodString] = true;

        return $spy;
    }

    private static function spyOnFunction($function) {
        if ( ! function_exists($function)) {
            throw new \InvalidArgumentException("The {$function} function doesn't exist.");
        }

        $spy = new Spy($function);
        $handler = self::createHandler($spy);
        uopz_function($function, $handler);

        self::$investigations[$function] = true;

        return $spy;
    }

    private static function createHandler($spy) {
        return function () use ($spy) {
            $arguments = func_get_args();

            return call_user_func_array($spy, $arguments);
        };
    }

    public static function stopSpyingOn($function)
    {
        if (is_array($function)) {
            return self::stopSpyingOnMethod($function);
        }

        return self::stopSpyingOnFunction($function);
    }

    private static function stopSpyingOnMethod($function)
    {
        list($class, $method) = $function;
        $className = is_object($class) ? get_class($class) : $class;
        $methodString = "{$className}::{$method}";

        if ( ! isset(self::$investigations[$methodString])) {
            throw new \InvalidArgumentException("The {$methodString} method isn't being spied on.");
        }

        uopz_restore($className, $method);
    }

    private static function stopSpyingOnFunction($function)
    {
        if ( ! isset(self::$investigations[$function])) {
            throw new \InvalidArgumentException("The {$function} function isn't being spied on.");
        }

        uopz_restore($function);
    }
}
