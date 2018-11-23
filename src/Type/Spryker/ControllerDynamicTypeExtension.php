<?php declare(strict_types = 1);

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PHPStan\Type\Spryker;

use PHPStan\Type\DynamicMethodReturnTypeExtension;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

class ControllerDynamicTypeExtension extends ClassDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{

	/** @var bool[] */
	protected $methodResolves = [
		'getFacade' => true,
		'getQueryContainer' => true,
		'getFactory' => true,
	];

	public function getClass(): string
	{
		return AbstractController::class;
	}

}
