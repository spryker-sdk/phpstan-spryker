<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPUnit\Framework\TestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\DynamicMethodMissingPhpDocAnnotationRule;

class DynamicMethodMissingPhpDocAnnotationRuleTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new DynamicMethodMissingPhpDocAnnotationRule('test', []);
        $this->assertInstanceOf(DynamicMethodMissingPhpDocAnnotationRule::class, $instance);
    }
}
