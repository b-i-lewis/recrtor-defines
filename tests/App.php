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
}