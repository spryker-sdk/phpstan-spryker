<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PhpStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Yves\Kernel\AbstractFactory;

class YvesFactoryDynamicTypeExtension extends AbstractSprykerDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var array
     */
    protected $methodResolves = [
        'getConfig' => true,
        'getClient' => true,
    ];

    /**
     * @return string
     */
    public function getClass(): string
    {
        return AbstractFactory::class;
    }
}
