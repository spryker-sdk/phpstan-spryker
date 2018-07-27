<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PhpStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

class QueryContainerDynamicTypeExtension extends AbstractSprykerDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var array
     */
    protected $methodResolves = [
        'getFactory' => true,
    ];

    /**
     * @return string
     */
    public function getClass(): string
    {
        return AbstractQueryContainer::class;
    }
}
