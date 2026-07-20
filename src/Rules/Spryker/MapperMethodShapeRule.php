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
 * Persistence mappers only transform: public methods are map[Source]To[Target](source, target)
 * — the caller provides the target object. See .claude/rules/mapper-pattern.md.
 *
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
 */
final class MapperMethodShapeRule implements Rule
{
    /**
     * @var string
     */
    private const MAP_METHOD_PATTERN = '#^map([A-Z0-9_]|$)#';

    /**
     * @var int
     */
    private const MIN_PARAMETER_COUNT = 2;

    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    /**
     * @param \PHPStan\Node\InClassMethodNode $node
     * @param \PHPStan\Analyser\Scope $scope
     *
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $node->getClassReflection();
        $className = $classReflection->getName();
        if (!str_ends_with($className, 'Mapper') || !str_contains($className, '\\Persistence\\')) {
            return [];
        }

        if ($classReflection->isAbstract() || $classReflection->isInterface()) {
            return [];
        }

        $methodNode = $node->getOriginalNode();
        $methodName = $methodNode->name->toString();
        if (!$methodNode->isPublic() || str_starts_with($methodName, '__')) {
            return [];
        }

        $errors = [];

        if (preg_match(static::MAP_METHOD_PATTERN, $methodName) !== 1) {
            $errors[] = RuleErrorBuilder::message(sprintf(
                'Persistence mapper %s declares public method %s(); mapper methods must be named map[Source]To[Target] (.claude/rules/mapper-pattern.md).',
                $className,
                $methodName,
            ))
                ->identifier('sprykerSuite.mapperShape')
                ->build();
        }

        if (count($methodNode->getParams()) < static::MIN_PARAMETER_COUNT) {
            $errors[] = RuleErrorBuilder::message(sprintf(
                'Persistence mapper method %s::%s() takes fewer than 2 parameters; mappers must accept both the source and the caller-provided target object (.claude/rules/mapper-pattern.md).',
                $className,
                $methodName,
            ))
                ->identifier('sprykerSuite.mapperShape')
                ->build();
        }

        return $errors;
    }
}
