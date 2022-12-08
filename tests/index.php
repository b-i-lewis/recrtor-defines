<?php

$testVar = BAZ_DEFINE > 1 ? 1 : 0;

$fooVar = constant('BAZ_DEFINE');

$return = @file_exists('foo');

$test = @function_without_args();

@file_exists('foo', $return, $fooVar, $testVar);

if (@file_exists('foo')) {
    $foo = 'bar';
}
