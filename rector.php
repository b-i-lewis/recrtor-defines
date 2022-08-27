<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Utils\Rector\Rector\RectorReplaceDefinesWithMethodCalls;

return static function (RectorConfig $rectorConfig): void
{
    $rectorConfig->paths([
        __DIR__ . '/tests/index.php'
    ]);

    $rectorConfig->ruleWithConfiguration(RectorReplaceDefinesWithMethodCalls::class, [
        'className' => \Tests\App::class,
        'methodName' => 'getDefine',
    ]);

    $rectorConfig->ruleWithConfiguration(\Utils\Rector\Rector\RectorReplaceErrorSuppression::class, [
        'className' => \Tests\App::class,
        'methodName' => 'suppressedFunction',
    ]);
};
