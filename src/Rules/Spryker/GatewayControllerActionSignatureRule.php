<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Rules\Spryker;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Gateway controller actions are the Client-to-Zed RPC boundary: exactly one transfer parameter,
 * transfer (or nullable transfer) return.
 *
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
 */
final class GatewayControllerActionSignatureRule implements Rule
{
    private const string ABSTRACT_GATEWAY_CONTROLLER = 'Spryker\\Zed\\Kernel\\Communication\\Controller\\AbstractGatewayController';

    private const string ABSTRACT_TRANSFER = 'Spryker\\Shared\\Kernel\\Transfer\\AbstractTransfer';

    /**
     * Resolves whether the parameter and return types are transfer classes.
     */
    public function __construct(private readonly ReflectionProvider $reflectionProvider)
    {
    }

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
        $methodNode = $node->getOriginalNode();
        $methodName = $methodNode->name->toString();
        if (!str_ends_with($methodName, 'Action') || !$methodNode->isPublic()) {
            return [];
        }

        $classReflection = $node->getClassReflection();
        if ($classReflection->isAbstract() || $classReflection->isInterface()) {
            return [];
        }

        if (!$classReflection->is(static::ABSTRACT_GATEWAY_CONTROLLER)) {
            return [];
        }

        if ($this->hasValidParameter($methodNode, $scope) && $this->hasValidReturnType($methodNode, $scope)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Gateway action %s::%s() must accept exactly one transfer object parameter and declare a transfer (or nullable transfer) return type.',
                $classReflection->getName(),
                $methodName,
            ))
                ->identifier('sprykerSuite.gatewayActionSignature')
                ->build(),
        ];
    }

    private function hasValidParameter(ClassMethod $methodNode, Scope $scope): bool
    {
        $params = $methodNode->getParams();
        if (count($params) !== 1) {
            return false;
        }

        $paramType = $params[0]->type;
        if (!$paramType instanceof Name) {
            return false;
        }

        return $this->isTransferClass($scope->resolveName($paramType));
    }

    private function hasValidReturnType(ClassMethod $methodNode, Scope $scope): bool
    {
        $returnType = $methodNode->returnType;

        if ($returnType instanceof Name) {
            return $this->isTransferClass($scope->resolveName($returnType));
        }

        if ($returnType instanceof NullableType && $returnType->type instanceof Name) {
            return $this->isTransferClass($scope->resolveName($returnType->type));
        }

        if ($returnType instanceof UnionType) {
            return $this->isNullableTransferUnion($returnType, $scope);
        }

        return false;
    }

    private function isNullableTransferUnion(UnionType $unionType, Scope $scope): bool
    {
        if (count($unionType->types) !== 2) {
            return false;
        }

        $hasNull = false;
        $hasTransfer = false;
        foreach ($unionType->types as $innerType) {
            if ($innerType instanceof Identifier && $innerType->toLowerString() === 'null') {
                $hasNull = true;

                continue;
            }

            if ($innerType instanceof Name && $this->isTransferClass($scope->resolveName($innerType))) {
                $hasTransfer = true;
            }
        }

        return $hasNull && $hasTransfer;
    }

    private function isTransferClass(string $className): bool
    {
        if (!$this->reflectionProvider->hasClass($className)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        return $classReflection->getName() !== static::ABSTRACT_TRANSFER && $classReflection->is(static::ABSTRACT_TRANSFER);
    }
}
