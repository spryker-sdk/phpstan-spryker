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
 * Repositories are read-only, EntityManagers are write-only: the public method verb must match the
 * side of the split. See .claude/rules/persistence-repository.md and persistence-entity-manager.md.
 *
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
 */
final class RepositoryReadWriteSplitRule implements Rule
{
    /**
     * @var string
     */
    private const REPOSITORY_CLASS_PATTERN = '#\\\\Persistence\\\\[^\\\\]*Repository$#';

    /**
     * @var string
     */
    private const ENTITY_MANAGER_CLASS_PATTERN = '#\\\\Persistence\\\\[^\\\\]*EntityManager$#';

    /**
     * Base verbs (find|get|has|count|is|expand) extended with read verbs already used by core
     * Repository interfaces: check, exists, search, are, verify, iterate, aggregate, filter.
     *
     * @var string
     */
    private const REPOSITORY_METHOD_PATTERN = '#^(find|get|has|count|is|expand|check|exists|search|are|verify|iterate|aggregate|filter)([A-Z0-9_]|$)#';

    /**
     * Base verbs (create|update|delete|save) extended with write verbs already used by core
     * EntityManager interfaces: remove, add, set, persist, invalidate, upsert, unset, revoke,
     * reset, commit, clear, bulk, assign, write, store, mark, hide, fill, close, activate,
     * deactivate.
     *
     * @var string
     */
    private const ENTITY_MANAGER_METHOD_PATTERN = '#^(create|update|delete|save|remove|add|set|persist|invalidate|upsert|unset|revoke|reset|commit|clear|bulk|assign|write|store|mark|hide|fill|close|activate|deactivate)([A-Z0-9_]|$)#';

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

        $isRepository = preg_match(static::REPOSITORY_CLASS_PATTERN, $className) === 1;
        $isEntityManager = !$isRepository && preg_match(static::ENTITY_MANAGER_CLASS_PATTERN, $className) === 1;
        if (!$isRepository && !$isEntityManager) {
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

        if ($isRepository && preg_match(static::REPOSITORY_METHOD_PATTERN, $methodName) !== 1) {
            return [
                RuleErrorBuilder::message(sprintf(
                    'Repository %s declares public method %s(); repositories are read-only and public methods must start with a read verb (find|get|has|count|is|expand|check|exists|search|are|verify|iterate|aggregate|filter) (.claude/rules/persistence-repository.md).',
                    $className,
                    $methodName,
                ))
                    ->identifier('sprykerSuite.repositoryWriteMethod')
                    ->build(),
            ];
        }

        if ($isEntityManager && preg_match(static::ENTITY_MANAGER_METHOD_PATTERN, $methodName) !== 1) {
            return [
                RuleErrorBuilder::message(sprintf(
                    'EntityManager %s declares public method %s(); entity managers are write-only and public methods must start with a write verb (create|update|delete|save|remove|add|set|persist|...) (.claude/rules/persistence-entity-manager.md).',
                    $className,
                    $methodName,
                ))
                    ->identifier('sprykerSuite.entityManagerReadMethod')
                    ->build(),
            ];
        }

        return [];
    }
}
