<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector;
use Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\CodeQuality\Rector\Switch_\SingularSwitchToIfRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/config',
        __DIR__.'/lang',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        privatization: true,
    )
    ->withPhpSets()
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ])
    ->withSkip([
        CompactToVariablesRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        ExplicitBoolCompareRector::class,
        SimplifyIfReturnBoolRector::class,
        CombinedAssignRector::class,
        InlineIfToExplicitIfRector::class,
        SingularSwitchToIfRector::class,
        DisallowedEmptyRuleFixerRector::class,
        RemoveDuplicatedCaseInSwitchRector::class,
        FirstClassCallableRector::class => [
            __DIR__.'/routes',
        ],

        // project specific
        // ...
    ]);
