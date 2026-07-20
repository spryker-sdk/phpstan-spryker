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

    public function testGivenABusinessExpanderReturningVoidWhenAnalysedThenAnErrorIsReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/ExpanderReturnRule/CommentExpander.php';

        // Act & Assert
        $this->analyse([$fixture], [
            [
                'Expander method Pyz\Zed\FixtureModule\Business\Expander\CommentExpander::expandInPlace() returns void; expanders must return the enriched transfer object.',
                16,
            ],
        ]);
    }

    public function testGivenACommunicationExpanderReturningVoidWhenAnalysedThenNothingIsReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/ExpanderReturnRule/FormExpander.php';

        // Act & Assert
        $this->analyse([$fixture], []);
    }
}
