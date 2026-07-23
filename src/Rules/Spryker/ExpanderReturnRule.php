<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Rules\Spryker;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Business-layer expanders must return the enriched transfer object, never void
 * (scoped to Business/**; Form/Request expanders in Communication/Yves/Glue mutate
 * builders and are legitimately void).
 *
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
 */
final class ExpanderReturnRule implements Rule
{
    private const string EXPAND_METHOD_PATTERN = '#^expand([A-Z0-9_]|$)#';

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
        $className = $node->getClassReflection()->getName();
        if (!str_ends_with($className, 'Expander') || !str_contains($className, '\\Business\\')) {
            return [];
        }

        $methodNode = $node->getOriginalNode();
        $methodName = $methodNode->name->toString();
        if (!$methodNode->isPublic() || preg_match(static::EXPAND_METHOD_PATTERN, $methodName) !== 1) {
            return [];
        }

        $returnType = $methodNode->returnType;
        if (!$returnType instanceof Identifier || $returnType->toLowerString() !== 'void') {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Expander method %s::%s() returns void; expanders must return the enriched transfer object.',
                $className,
                $methodName,
            ))
                ->identifier('sprykerSuite.expanderVoidReturn')
                ->build(),
        ];
    }
}
