<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Cache\Cache;
use PHPStan\Cache\CacheStorage;
use PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension;
use SprykerSdk\PHPStanSpryker\Type\Spryker\DynamicMethodMissingTypeExtension;
use PHPUnit\Framework\TestCase;

class DynamicMethodMissingTypeExtensionTest extends TestCase
{
    /**
     * @return void
     */
    public function testInstance(): void
    {
        $instance = new DynamicMethodMissingTypeExtension(
            new AnnotationsMethodsClassReflectionExtension(),
            new Cache($this->createMock(CacheStorage::class)),
            'test',
            []
        );
        $this->assertInstanceOf(DynamicMethodMissingTypeExtension::class, $instance);
    }
}
