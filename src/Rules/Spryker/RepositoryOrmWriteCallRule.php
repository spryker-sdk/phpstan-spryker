<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Rules\Spryker;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Companion to RepositoryReadWriteSplitRule: flags ->save()/->delete() calls on Orm entities or
 * queries inside a Repository (write operations belong in the EntityManager).
 *
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
final class RepositoryOrmWriteCallRule implements Rule
{
    private const string REPOSITORY_CLASS_PATTERN = '#\\\\Persistence\\\\[^\\\\]*Repository$#';

    /**
     * @var list<string>
     */
    private const array WRITE_METHOD_NAMES = ['save', 'delete'];

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     *
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Identifier) {
            return [];
        }

        $calledMethodName = $node->name->toLowerString();
        if (!in_array($calledMethodName, static::WRITE_METHOD_NAMES, true)) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection === null || $classReflection->isAbstract()) {
            return [];
        }

        $className = $classReflection->getName();
        if (preg_match(static::REPOSITORY_CLASS_PATTERN, $className) !== 1) {
            return [];
        }

        $receiverType = $scope->getType($node->var);
        foreach ($receiverType->getObjectClassNames() as $receiverClassName) {
            if (!str_starts_with($receiverClassName, 'Orm\\')) {
                continue;
            }

            return [
                RuleErrorBuilder::message(sprintf(
                    'Repository %s calls %s() on %s; repositories are read-only — move writes to the EntityManager.',
                    $className,
                    $calledMethodName,
                    $receiverClassName,
                ))
                    ->identifier('sprykerSuite.repositoryOrmWriteCall')
                    ->build(),
            ];
        }

        return [];
    }
}
