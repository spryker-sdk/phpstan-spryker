<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PHPStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Yves\Kernel\Form\AbstractType;

class YvesFormTypeDynamicTypeExtension extends AbstractSprykerDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var array
     */
    protected $methodResolves = [
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
