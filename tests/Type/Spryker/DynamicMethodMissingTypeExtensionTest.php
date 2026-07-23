<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension;
use PHPUnit\Framework\TestCase;
use SprykerSdk\PHPStanSpryker\Type\Spryker\DynamicMethodMissingTypeExtension;

class DynamicMethodMissingTypeExtensionTest extends TestCase
{
    public function testGivenDependenciesWhenConstructedThenAnInstanceOfTheExtensionIsReturned(): void
    {
        // Arrange & Act
        $instance = new DynamicMethodMissingTypeExtension(
            new AnnotationsMethodsClassReflectionExtension(),
            'test',
            [],
        );

        // Assert
        $this->assertInstanceOf(DynamicMethodMissingTypeExtension::class, $instance);
    }
}
