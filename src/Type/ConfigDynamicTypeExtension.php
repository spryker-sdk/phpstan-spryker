<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PhpStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Shared\Kernel\AbstractBundleConfig;

class ConfigDynamicTypeExtension extends AbstractSprykerDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var array
     */
    protected $methodResolves = [
        'getSharedConfig' => true,
    ];

    /**
     * @return string
     */
    public function getClass(): string
    {
        return AbstractBundleConfig::class;
    }
}
