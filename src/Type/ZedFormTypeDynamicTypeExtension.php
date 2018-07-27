<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PhpStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;

class ZedFormTypeDynamicTypeExtension extends AbstractSprykerDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var array
     */
    protected $methodResolves = [
        'getFacade' => true,
        'getFactory' => true,
        'getConfig' => true,
        'getQueryContainer' => true,
    ];

    /**
     * @return string
     */
    public function getClass(): string
    {
        return AbstractType::class;
    }
}
