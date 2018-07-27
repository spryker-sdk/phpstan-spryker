<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PHPStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

class ControllerDynamicTypeExtension extends AbstractSprykerDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var array
     */
    protected $methodResolves = [
        'getFacade' => true,
        'getQueryContainer' => true,
        'getFactory' => true,
    ];

    /**
     * @return string
     */
    public function getClass(): string
    {
        return AbstractController::class;
    }
}
