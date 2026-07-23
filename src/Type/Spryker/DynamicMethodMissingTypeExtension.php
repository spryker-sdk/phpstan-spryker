<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

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
    private AnnotationsMethodsClassReflectionExtension $annotationsMethodsClassReflectionExtension;

    /**
     * @var class-string
     */
    protected string $className;

    /**
     * @var array<string>
     */
    protected array $methodNames;

    /**
     * @param class-string $className
     * @param array<string> $methodNames
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
     * @return class-string
     */
    public function getClass(): string
    {
        return $this->className;
    }

    /**
     * @inheritDoc
     */
    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        if (in_array($methodReflection->getName(), $this->methodNames, true)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        return $this->getTypeFromAnnotationsMethodClassReflection($methodReflection, $methodCall, $scope);
    }

    /**
     * @throws \PHPStan\ShouldNotHappenException
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
