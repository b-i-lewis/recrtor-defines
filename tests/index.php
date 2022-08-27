<?php

$testVar = BAZ_DEFINE > 1 ? 1 : 0;

$fooVar = constant('BAZ_DEFINE');

$return = @file_exists('foo');

@file_exists('foo', $return, $fooVar, $testVar);

if (@file_exists('foo')) {
    $foo = 'bar';
}
