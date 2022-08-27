<?php

namespace Tests;

class App
{
    public static function getDefine(string $name)
    {
        if (defined($name)) {
            return constant($name);
        }
        return $name;
    }

    public static function suppressedFunction($name, $args = [])
    {
        if (function_exists($name)) {
            try {
                if (!empty($args)) {
                    return $name(...$args);
                } else {
                    return $name();
                }
            } catch (\Throwable $e) {
                self::reportException($e);
            }
        }

        return null;
    }

    public static function reportException(\Throwable $e) : void
    {
        //foo
    }
}