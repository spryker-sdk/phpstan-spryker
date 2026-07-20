<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\ClassConstantNativeTypeHintRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\SprykerSdk\PHPStanSpryker\Rules\Spryker\ClassConstantNativeTypeHintRule>
 */
class ClassConstantNativeTypeHintRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\ClassConst>
     */
    protected function getRule(): Rule
    {
        return new ClassConstantNativeTypeHintRule();
    }

    public function testGivenUntypedConstantsWhenAnalysedThenEachIsReported(): void
    {
        // Arrange
        $prefix = 'Class constant SprykerSdk\PHPStanSpryker\Test\Fixtures\ClassConstants\ClassWithConstants::';
        $suffix = ' has no native type hint; declare one (e.g. `private const string FOO = ...`).';

        // Act & Assert
        $this->analyse([__DIR__ . '/../../Fixtures/ClassConstants/ClassWithConstants.php'], [
            [$prefix . 'UNTYPED' . $suffix, 9],
            [$prefix . 'UNTYPED_WITH_SHAPE_DOCBLOCK' . $suffix, 14],
            [$prefix . 'UNTYPED_MULTI_A' . $suffix, 16],
            [$prefix . 'UNTYPED_MULTI_B' . $suffix, 16],
        ]);
    }
}
