<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\PersistenceSignatureTransferOnlyRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\SprykerSdk\PHPStanSpryker\Rules\Spryker\PersistenceSignatureTransferOnlyRule>
 */
class PersistenceSignatureTransferOnlyRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
     */
    protected function getRule(): Rule
    {
        return new PersistenceSignatureTransferOnlyRule();
    }

    /**
     * @return void
     */
    public function testGivenAnEntityManagerLeakingOrmEntitiesInParametersWhenAnalysedThenErrorsAreReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/PersistenceSignatureTransferOnlyRule/FixtureModuleEntityManager.php';

        // Act & Assert
        $this->analyse([$fixture], [
            [
                'Public persistence method Pyz\Zed\PersistenceFixtureModule\Persistence\FixtureModuleEntityManager::saveComment() takes Propel entity type Orm\Zed\Comment\Persistence\SpyComment as a parameter; Repository/EntityManager signatures must use transfer objects or primitives only (.claude/rules/persistence-repository.md, persistence-entity-manager.md).',
                12,
            ],
            [
                'Public persistence method Pyz\Zed\PersistenceFixtureModule\Persistence\FixtureModuleEntityManager::saveNullableComment() takes Propel entity type Orm\Zed\Comment\Persistence\SpyComment as a parameter; Repository/EntityManager signatures must use transfer objects or primitives only (.claude/rules/persistence-repository.md, persistence-entity-manager.md).',
                16,
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testGivenARepositoryInterfaceLeakingOrmEntitiesInReturnTypeWhenAnalysedThenAnErrorIsReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/PersistenceSignatureTransferOnlyRule/FixtureModuleRepositoryInterface.php';

        // Act & Assert
        $this->analyse([$fixture], [
            [
                'Public persistence method Pyz\Zed\PersistenceFixtureModule\Persistence\FixtureModuleRepositoryInterface::getCommentEntity() returns Propel entity type Orm\Zed\Comment\Persistence\SpyComment; Repository/EntityManager signatures must use transfer objects or primitives only (.claude/rules/persistence-repository.md, persistence-entity-manager.md).',
                14,
            ],
        ]);
    }
}
