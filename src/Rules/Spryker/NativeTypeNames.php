<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Rules\Spryker;

use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;

final class NativeTypeNames
{
    /**
     * Resolves every class name referenced by a native type node (plain, nullable, union, intersection).
     *
     * @param \PhpParser\Node\Identifier|\PhpParser\Node\Name|\PhpParser\Node\ComplexType $typeNode
     *
     * @return list<string>
     */
    public static function resolve(Identifier|Name|ComplexType|null $typeNode, Scope $scope): array
    {
        if ($typeNode === null || $typeNode instanceof Identifier) {
            return [];
        }

        if ($typeNode instanceof Name) {
            return [$scope->resolveName($typeNode)];
        }

        if ($typeNode instanceof NullableType) {
            return static::resolve($typeNode->type, $scope);
        }

        if ($typeNode instanceof UnionType || $typeNode instanceof IntersectionType) {
            $classNames = [];
            foreach ($typeNode->types as $innerType) {
                foreach (static::resolve($innerType, $scope) as $className) {
                    $classNames[] = $className;
                }
            }

            return $classNames;
        }

        return [];
    }

    public static function shortName(string $className): string
    {
        $separatorPosition = strrpos($className, '\\');

        return $separatorPosition === false ? $className : substr($className, $separatorPosition + 1);
    }
}
