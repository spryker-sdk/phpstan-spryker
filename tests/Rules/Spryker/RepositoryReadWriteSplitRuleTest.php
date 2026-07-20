<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\RepositoryReadWriteSplitRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\SprykerSdk\PHPStanSpryker\Rules\Spryker\RepositoryReadWriteSplitRule>
 */
class RepositoryReadWriteSplitRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
     */
    protected function getRule(): Rule
    {
        return new RepositoryReadWriteSplitRule();
    }

    public function testGivenARepositoryWithPublicMethodsNotUsingReadVerbsWhenAnalysedThenErrorsAreReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/RepositoryReadWriteSplitRule/FixtureModuleRepository.php';

        // Act & Assert
        $this->analyse([$fixture], [
            [
                'Repository Pyz\Zed\FixtureModule\Persistence\FixtureModuleRepository declares public method saveComment(); repositories are read-only and public methods must start with a read verb (find|get|has|count|is|expand|check|exists|search|are|verify|iterate|aggregate|filter).',
                31,
            ],
            [
                'Repository Pyz\Zed\FixtureModule\Persistence\FixtureModuleRepository declares public method removeComment(); repositories are read-only and public methods must start with a read verb (find|get|has|count|is|expand|check|exists|search|are|verify|iterate|aggregate|filter).',
                36,
            ],
        ]);
    }

    public function testGivenAnEntityManagerWithPublicMethodsNotUsingWriteVerbsWhenAnalysedThenErrorsAreReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/RepositoryReadWriteSplitRule/FixtureModuleEntityManager.php';

        // Act & Assert
        $this->analyse([$fixture], [
            [
                'EntityManager Pyz\Zed\FixtureModule\Persistence\FixtureModuleEntityManager declares public method findComment(); entity managers are write-only and public methods must start with a write verb (create|update|delete|save|remove|add|set|persist|...).',
                21,
            ],
            [
                'EntityManager Pyz\Zed\FixtureModule\Persistence\FixtureModuleEntityManager declares public method getCommentCount(); entity managers are write-only and public methods must start with a write verb (create|update|delete|save|remove|add|set|persist|...).',
                26,
            ],
        ]);
    }
}
