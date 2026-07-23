<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Rules\Spryker;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Property>
 */
class PropertyNativeTypeHintRule implements Rule
{
    /**
     * @return class-string<\PhpParser\Node\Stmt\Property>
     */
    public function getNodeType(): string
    {
        return Property::class;
    }

    /**
     * @param \PhpParser\Node\Stmt\Property $node
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
        foreach ($node->props as $property) {
            $errors[] = RuleErrorBuilder::message(sprintf(
                'Class property %s::$%s has no native type hint; declare one (e.g. `private string $foo;`).',
                $className,
                $property->name->toString(),
            ))
                ->identifier('spryker.propertyMissingNativeType')
                ->build();
        }

        return $errors;
    }
}
