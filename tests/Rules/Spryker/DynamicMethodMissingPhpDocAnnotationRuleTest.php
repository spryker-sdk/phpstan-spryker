<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PHPStan\Test\Rules\Spryker;

use SprykerSdk\PHPStanSpryker\Rules\Spryker\DynamicMethodMissingPhpDocAnnotationRule;
use PHPUnit\Framework\TestCase;

class DynamicMethodMissingPhpDocAnnotationRuleTest extends TestCase
{
    /**
     * @return void
     */
    public function testInstance(): void
    {
        $instance = new DynamicMethodMissingPhpDocAnnotationRule('test', []);
        $this->assertInstanceOf(DynamicMethodMissingPhpDocAnnotationRule::class, $instance);
    }
}
