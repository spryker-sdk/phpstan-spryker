<?php declare(strict_types = 1);

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerSdk\PHPStanSpryker\Type\Spryker;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ErrorType;
use PHPStan\Type\Type;

class DynamicMethodMissingTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * @var \PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension
     */
    private $annotationsMethodsClassReflectionExtension;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string[]
     */
    protected $methodNames;

    /**
     * @param \PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension $annotationsMethodsClassReflectionExtension
     * @param string $className
     * @param string[] $methodNames
     */
    public function __construct(
        AnnotationsMethodsClassReflectionExtension $annotationsMethodsClassReflectionExtension,
        string $className,
        array $methodNames
    ) {
        $this->annotationsMethodsClassReflectionExtension = $annotationsMethodsClassReflectionExtension;
        $this->className = $className;
        $this->methodNames = $methodNames;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->className;
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     *
     * @return bool
     */
    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        if (in_array($methodReflection->getName(), $this->methodNames, true)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Analyser\Scope $scope
     *
     * @return \PHPStan\Type\Type
     */
    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        return $this->getTypeFromAnnotationsMethodClassReflection($methodReflection, $methodCall, $scope);
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Analyser\Scope $scope
     *
     * @throws \PHPStan\ShouldNotHappenException
     *
     * @return \PHPStan\Type\Type
     */
    protected function getTypeFromAnnotationsMethodClassReflection(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        if (!$scope->isInClass()) {
            throw new ShouldNotHappenException();
        }

        if (!$this->annotationsMethodsClassReflectionExtension->hasMethod($scope->getClassReflection(), $methodReflection->getName())) {
            return new ErrorType();
        }

        $annotationMethod = $this->annotationsMethodsClassReflectionExtension->getMethod($scope->getClassReflection(), $methodReflection->getName());

        return ParametersAcceptorSelector::selectFromArgs($scope, $methodCall->getArgs(), $annotationMethod->getVariants())->getReturnType();
    }
}
