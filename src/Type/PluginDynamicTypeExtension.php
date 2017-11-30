<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\PhpStan\Type;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class PluginDynamicTypeExtension extends AbstractSprykerDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var array
     */
    protected $methodResolves = [
        'getFacade' => true,
        'getFactory' => true,
        'getQueryContainer' => true,
        'getConfig' => true,
    ];

    /**
     * @return string
     */
    public static function getClass(): string
    {
        return AbstractPlugin::class;
    }
}
