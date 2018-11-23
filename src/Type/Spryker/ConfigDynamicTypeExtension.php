<?php declare(strict_types = 1);

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PHPStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Shared\Kernel\AbstractBundleConfig;

class ConfigDynamicTypeExtension extends ClassDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{

	/** @var bool[] */
	protected $methodResolves = [
		'getSharedConfig' => true,
	];

	public function getClass(): string
	{
		return AbstractBundleConfig::class;
	}

}
