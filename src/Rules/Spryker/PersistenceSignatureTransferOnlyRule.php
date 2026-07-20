<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Rules\Spryker;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Public Repository/EntityManager signatures (classes AND interfaces) must not leak Propel
 * entities: no Orm\* or Spy* parameter/return types. Repository/EntityManager signatures —
 * especially interface signatures — must use transfer objects or primitives only.
 *
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
 */
final class PersistenceSignatureTransferOnlyRule implements Rule
{
    private const string PERSISTENCE_GATEWAY_CLASS_PATTERN = '#\\\\Persistence\\\\[^\\\\]*(Repository|EntityManager)(Interface)?$#';

    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    /**
     * @param \PHPStan\Node\InClassMethodNode $node
     *
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $node->getClassReflection();
        $className = $classReflection->getName();
        if (preg_match(static::PERSISTENCE_GATEWAY_CLASS_PATTERN, $className) !== 1) {
            return [];
        }

        if ($classReflection->isAbstract() && !$classReflection->isInterface()) {
            return [];
        }

        $methodNode = $node->getOriginalNode();
        $methodName = $methodNode->name->toString();
        if (!$methodNode->isPublic() || str_starts_with($methodName, '__')) {
            return [];
        }

        $errors = [];

        foreach ($methodNode->getParams() as $param) {
            foreach (NativeTypeNames::resolve($param->type, $scope) as $typeClassName) {
                if (!static::isOrmType($typeClassName)) {
                    continue;
                }

                $errors[] = RuleErrorBuilder::message(sprintf(
                    'Public persistence method %s::%s() takes Propel entity type %s as a parameter; Repository/EntityManager signatures must use transfer objects or primitives only.',
                    $className,
                    $methodName,
                    $typeClassName,
                ))
                    ->identifier('sprykerSuite.persistenceOrmSignature')
                    ->line($param->getStartLine())
                    ->build();
            }
        }

        foreach (NativeTypeNames::resolve($methodNode->returnType, $scope) as $typeClassName) {
            if (!static::isOrmType($typeClassName)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(sprintf(
                'Public persistence method %s::%s() returns Propel entity type %s; Repository/EntityManager signatures must use transfer objects or primitives only.',
                $className,
                $methodName,
                $typeClassName,
            ))
                ->identifier('sprykerSuite.persistenceOrmSignature')
                ->build();
        }

        return $errors;
    }

    private static function isOrmType(string $className): bool
    {
        return str_starts_with($className, 'Orm\\')
            || str_starts_with(NativeTypeNames::shortName($className), 'Spy');
    }
}
