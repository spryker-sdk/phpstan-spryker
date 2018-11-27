<?php declare(strict_types = 1);

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PHPStan\Type\Spryker;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ErrorType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

abstract class ClassDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{
    /** @var \PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension */
    private $annotationsMethodsClassReflectionExtension;

	/** @var bool[] */
	protected $methodResolves = [];

    public function __construct(AnnotationsMethodsClassReflectionExtension $annotationsMethodsClassReflectionExtension)
    {
        $this->annotationsMethodsClassReflectionExtension = $annotationsMethodsClassReflectionExtension;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
	{
	    if (isset($this->methodResolves[$methodReflection->getName()])) {
			return true;
		}

		return false;
	}

	/**
	 * @param \PHPStan\Reflection\MethodReflection $methodReflection
	 * @param \PhpParser\Node\Expr\MethodCall $methodCall
	 * @param \PHPStan\Analyser\Scope $scope
	 *
	 * @throws \Exception
	 * @throws \PHPStan\ShouldNotHappenException
	 *
	 * @return \PHPStan\Type\Type
	 */
	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		if (!$scope->isInClass()) {
			throw new \PHPStan\ShouldNotHappenException();
		}

		if (!$this->annotationsMethodsClassReflectionExtension->hasMethod($scope->getClassReflection(), $methodReflection->getName())) {
            return new ErrorType();
        }

        $annotationMethod = $this->annotationsMethodsClassReflectionExtension->getMethod($scope->getClassReflection(), $methodReflection->getName());

        return $annotationMethod->getVariants()[0]->getReturnType();

	}

}
