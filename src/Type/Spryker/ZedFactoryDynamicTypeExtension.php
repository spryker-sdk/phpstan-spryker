<?php declare(strict_types = 1);

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PHPStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Zed\Kernel\AbstractFactory;

class ZedFactoryDynamicTypeExtension extends ClassDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{

	/** @var bool[] */
	protected $methodResolves = [
		'getQueryContainer' => true,
		'getFacade' => true,
	];

	public function getClass(): string
	{
		return AbstractFactory::class;
	}

}
