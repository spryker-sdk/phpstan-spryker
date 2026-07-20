<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\ExpanderReturnRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\SprykerSdk\PHPStanSpryker\Rules\Spryker\ExpanderReturnRule>
 */
class ExpanderReturnRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
     */
    protected function getRule(): Rule
    {
        return new ExpanderReturnRule();
    }

    /**
     * @return void
     */
    public function testBusinessExpanderMayNotReturnVoid(): void
    {
        $this->analyse([__DIR__ . '/../../Fixtures/ExpanderReturnRule/CommentExpander.php'], [
            [
                'Expander method Pyz\Zed\FixtureModule\Business\Expander\CommentExpander::expandInPlace() returns void; expanders must return the enriched transfer object (.claude/rules/expander-pattern.md).',
                16,
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testCommunicationExpanderIsExempt(): void
    {
        $this->analyse([__DIR__ . '/../../Fixtures/ExpanderReturnRule/FormExpander.php'], []);
    }
}
