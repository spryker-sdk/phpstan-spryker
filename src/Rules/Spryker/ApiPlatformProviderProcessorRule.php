<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Rules\Spryker;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * API Platform state providers/processors: the constructor must receive the Symfony serializer,
 * and process() must declare an ?object (or object|null) return type.
 *
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\InClassNode>
 */
final class ApiPlatformProviderProcessorRule implements Rule
{
    private const string PROVIDER_INTERFACE = 'ApiPlatform\\State\\ProviderInterface';

    private const string PROCESSOR_INTERFACE = 'ApiPlatform\\State\\ProcessorInterface';

    private const string SERIALIZER_INTERFACE = 'Symfony\\Component\\Serializer\\SerializerInterface';

    /**
     * Resolves serializer interfaces among a class's constructor dependencies.
     */
    public function __construct(private readonly ReflectionProvider $reflectionProvider)
    {
    }

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param \PHPStan\Node\InClassNode $node
     *
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classNode = $node->getOriginalNode();
        if (!$classNode instanceof Class_ || $classNode->isAbstract() || $classNode->name === null) {
            return [];
        }

        $classReflection = $node->getClassReflection();
        $className = $classReflection->getName();
        if (!str_contains($className, 'Provider') && !str_contains($className, 'Processor')) {
            return [];
        }

        $isProvider = $classReflection->implementsInterface(static::PROVIDER_INTERFACE);
        $isProcessor = $classReflection->implementsInterface(static::PROCESSOR_INTERFACE);
        if (!$isProvider && !$isProcessor) {
            return [];
        }

        $errors = [];

        if (!$this->constructorReceivesSerializer($classNode, $scope)) {
            $errors[] = RuleErrorBuilder::message(sprintf(
                'API Platform state class %s must receive %s via its constructor.',
                $className,
                static::SERIALIZER_INTERFACE,
            ))
                ->identifier('sprykerSuite.apiPlatformSerializer')
                ->build();
        }

        if ($isProcessor) {
            $processMethod = $classNode->getMethod('process');
            if ($processMethod !== null && !static::isNullableObjectReturnType($processMethod->returnType)) {
                $errors[] = RuleErrorBuilder::message(sprintf(
                    'API Platform processor %s::process() must declare an ?object (or object|null) return type.',
                    $className,
                ))
                    ->identifier('sprykerSuite.apiPlatformProcessReturn')
                    ->line($processMethod->getStartLine())
                    ->build();
            }
        }

        return $errors;
    }

    private function constructorReceivesSerializer(Class_ $classNode, Scope $scope): bool
    {
        $constructor = $classNode->getMethod('__construct');
        if ($constructor === null) {
            return false;
        }

        foreach ($constructor->getParams() as $param) {
            foreach (NativeTypeNames::resolve($param->type, $scope) as $typeClassName) {
                if ($typeClassName === static::SERIALIZER_INTERFACE) {
                    return true;
                }

                if (
                    $this->reflectionProvider->hasClass($typeClassName)
                    && $this->reflectionProvider->getClass($typeClassName)->is(static::SERIALIZER_INTERFACE)
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param \PhpParser\Node\Identifier|\PhpParser\Node\Name|\PhpParser\Node\ComplexType $returnType
     */
    private static function isNullableObjectReturnType(Identifier|Name|ComplexType|null $returnType): bool
    {
        if ($returnType instanceof NullableType) {
            return $returnType->type instanceof Identifier && $returnType->type->toLowerString() === 'object';
        }

        if (!$returnType instanceof UnionType || count($returnType->types) !== 2) {
            return false;
        }

        $names = [];
        foreach ($returnType->types as $innerType) {
            if (!$innerType instanceof Identifier) {
                return false;
            }

            $names[] = $innerType->toLowerString();
        }

        sort($names);

        return $names === ['null', 'object'];
    }
}
