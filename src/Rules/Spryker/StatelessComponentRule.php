<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Rules\Spryker;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Business models, Services and Client components must be stateless: no property writes after
 * construction, no static mutable state.
 *
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\InClassNode>
 */
final class StatelessComponentRule implements Rule
{
    private const string SCOPE_NAMESPACE_PATTERN = '#\\\\(Business|Service|Client)\\\\#';

    private const string EXCLUDED_CLASS_NAME_PATTERN = '#(Config|Factory|DependencyProvider)$#';

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

        $className = $node->getClassReflection()->getName();
        if (preg_match(static::SCOPE_NAMESPACE_PATTERN, $className) !== 1) {
            return [];
        }

        if (preg_match(static::EXCLUDED_CLASS_NAME_PATTERN, $className) === 1) {
            return [];
        }

        $errors = [];

        foreach ($classNode->getProperties() as $property) {
            if (!$property->isStatic()) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(sprintf(
                'Static property %s::$%s is shared mutable state; Business/Service/Client components must be stateless.',
                $className,
                $property->props[0]->name->toString(),
            ))
                ->identifier('sprykerSuite.componentStaticProperty')
                ->line($property->getStartLine())
                ->build();
        }

        $nodeFinder = new NodeFinder();
        foreach ($classNode->getMethods() as $method) {
            if ($method->name->toLowerString() === '__construct' || $method->stmts === null) {
                continue;
            }

            /** @var list<\PhpParser\Node\Expr> $propertyWrites */
            $propertyWrites = $nodeFinder->find(
                $method->stmts,
                static fn (Node $innerNode): bool => self::isPropertyWrite($innerNode),
            );

            foreach ($propertyWrites as $propertyWrite) {
                $errors[] = RuleErrorBuilder::message(sprintf(
                    'Property written outside the constructor in %s::%s(); components in Business/Service/Client must not hold mutable state between calls.',
                    $className,
                    $method->name->toString(),
                ))
                    ->identifier('sprykerSuite.componentPropertyWrite')
                    ->line($propertyWrite->getStartLine())
                    ->build();
            }
        }

        return $errors;
    }

    private static function isPropertyWrite(Node $node): bool
    {
        if (
            !$node instanceof Assign
            && !$node instanceof AssignRef
            && !$node instanceof AssignOp
        ) {
            return false;
        }

        $target = $node->var;
        while ($target instanceof ArrayDimFetch) {
            $target = $target->var;
        }

        if ($target instanceof StaticPropertyFetch) {
            return true;
        }

        if (!$target instanceof PropertyFetch) {
            return false;
        }

        return $target->var instanceof Variable && $target->var->name === 'this';
    }
}
