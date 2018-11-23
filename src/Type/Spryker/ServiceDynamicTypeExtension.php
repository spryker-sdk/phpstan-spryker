<?php declare(strict_types = 1);

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PHPStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Service\Kernel\AbstractService;

class ServiceDynamicTypeExtension extends ClassDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{

	/** @var bool[] */
	protected $methodResolves = [
		'getFactory' => true,
		'getConfig' => true,
	];

	public function getClass(): string
	{
		return AbstractService::class;
	}

}
