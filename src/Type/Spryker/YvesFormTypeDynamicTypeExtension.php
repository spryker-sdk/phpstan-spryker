<?php declare(strict_types = 1);

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PHPStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Yves\Kernel\Form\AbstractType;

class YvesFormTypeDynamicTypeExtension extends ClassDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{

	/** @var bool[] */
	protected $methodResolves = [
		'getFactory' => true,
		'getConfig' => true,
		'getQueryContainer' => true,
	];

	public function getClass(): string
	{
		return AbstractType::class;
	}

}
