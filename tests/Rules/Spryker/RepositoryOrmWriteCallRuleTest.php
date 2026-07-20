<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\RepositoryOrmWriteCallRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\SprykerSdk\PHPStanSpryker\Rules\Spryker\RepositoryOrmWriteCallRule>
 */
class RepositoryOrmWriteCallRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
     */
    protected function getRule(): Rule
    {
        return new RepositoryOrmWriteCallRule();
    }

    /**
     * @return list<string>
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../rule-test.neon'];
    }

    /**
     * @return void
     */
    public function testRepositoryMayNotCallWriteMethodsOnOrmEntities(): void
    {
        $this->analyse([__DIR__ . '/../../Fixtures/RepositoryReadWriteSplitRule/FixtureModuleRepository.php'], [
            [
                'Repository Pyz\Zed\FixtureModule\Persistence\FixtureModuleRepository calls save() on Orm\Zed\Comment\Persistence\SpyComment; repositories are read-only — move writes to the EntityManager (.claude/rules/persistence-repository.md).',
                33,
            ],
            [
                'Repository Pyz\Zed\FixtureModule\Persistence\FixtureModuleRepository calls delete() on Orm\Zed\Comment\Persistence\SpyComment; repositories are read-only — move writes to the EntityManager (.claude/rules/persistence-repository.md).',
                38,
            ],
        ]);
    }
}
