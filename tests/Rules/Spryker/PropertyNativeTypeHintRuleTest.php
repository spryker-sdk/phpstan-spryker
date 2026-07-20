<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\PropertyNativeTypeHintRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\SprykerSdk\PHPStanSpryker\Rules\Spryker\PropertyNativeTypeHintRule>
 */
class PropertyNativeTypeHintRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Property>
     */
    protected function getRule(): Rule
    {
        return new PropertyNativeTypeHintRule();
    }

    public function testGivenClassPropertiesWithoutNativeTypeHintsWhenAnalysedThenErrorsAreReported(): void
    {
        // Arrange
        $prefix = 'Class property SprykerSdk\PHPStanSpryker\Test\Fixtures\Properties\ClassWithProperties::$';
        $suffix = ' has no native type hint; declare one (e.g. `private string $foo;`).';

        // Act & Assert
        $this->analyse([__DIR__ . '/../../Fixtures/Properties/ClassWithProperties.php'], [
            [$prefix . 'untyped' . $suffix, 16],
            [$prefix . 'untypedMultiA' . $suffix, 18],
            [$prefix . 'untypedMultiB' . $suffix, 18],
        ]);
    }

    public function testGivenClassPropertiesWithNativeTypeHintsWhenAnalysedThenNothingIsReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/Properties/ClassWithTypedProperties.php';

        // Act & Assert
        $this->analyse([$fixture], []);
    }
}
