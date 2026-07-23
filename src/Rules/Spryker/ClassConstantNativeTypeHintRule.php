<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Rules\Spryker;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\ClassConst>
 */
class ClassConstantNativeTypeHintRule implements Rule
{
    /**
     * @return class-string<\PhpParser\Node\Stmt\ClassConst>
     */
    public function getNodeType(): string
    {
        return ClassConst::class;
    }

    /**
     * @param \PhpParser\Node\Stmt\ClassConst $node
     *
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->type !== null) {
            return [];
        }

        $className = $scope->isInClass() ? $scope->getClassReflection()->getDisplayName() : '';

        $errors = [];
        foreach ($node->consts as $const) {
            $errors[] = RuleErrorBuilder::message(sprintf(
                'Class constant %s::%s has no native type hint; declare one (e.g. `private const string FOO = ...`).',
                $className,
                $const->name->toString(),
            ))
                ->identifier('spryker.classConstantMissingNativeType')
                ->build();
        }

        return $errors;
    }
}
