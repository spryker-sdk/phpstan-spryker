<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\StatelessComponentRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\SprykerSdk\PHPStanSpryker\Rules\Spryker\StatelessComponentRule>
 */
class StatelessComponentRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule<\PHPStan\Node\InClassNode>
     */
    protected function getRule(): Rule
    {
        return new StatelessComponentRule();
    }

    /**
     * @return void
     */
    public function testStatefulModelReportsMutableState(): void
    {
        $this->analyse([__DIR__ . '/../../Fixtures/StatelessComponentRule/StatefulModel.php'], [
            [
                'Static property Pyz\Zed\FixtureModule\Business\Model\StatefulModel::$cache is shared mutable state; Business/Service/Client components must be stateless (.claude/rules/component-statelessness.md).',
                9,
            ],
            [
                'Property written outside the constructor in Pyz\Zed\FixtureModule\Business\Model\StatefulModel::remember(); components in Business/Service/Client must not hold mutable state between calls (.claude/rules/component-statelessness.md).',
                19,
            ],
            [
                'Property written outside the constructor in Pyz\Zed\FixtureModule\Business\Model\StatefulModel::accumulate(); components in Business/Service/Client must not hold mutable state between calls (.claude/rules/component-statelessness.md).',
                26,
            ],
            [
                'Property written outside the constructor in Pyz\Zed\FixtureModule\Business\Model\StatefulModel::append(); components in Business/Service/Client must not hold mutable state between calls (.claude/rules/component-statelessness.md).',
                31,
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testCleanAndExemptClassesReportNothing(): void
    {
        $this->analyse([
            __DIR__ . '/../../Fixtures/StatelessComponentRule/CleanModel.php',
            __DIR__ . '/../../Fixtures/StatelessComponentRule/FixtureModuleConfig.php',
        ], []);
    }
}
