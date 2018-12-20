<?php declare(strict_types = 1);

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PHPStan\Type\Spryker;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Cache\Cache;
use PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ErrorType;
use PHPStan\Type\Type;

class DynamicMethodMissingTypeExtension implements DynamicMethodReturnTypeExtension
{

	/** @var \PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension */
	private $annotationsMethodsClassReflectionExtension;

	/** @var \PHPStan\Cache\Cache */
	private $cache;

	/** @var string */
	protected $className;

	/** @var string[] */
	protected $methodNames;

	public function __construct(
		AnnotationsMethodsClassReflectionExtension $annotationsMethodsClassReflectionExtension,
		Cache $cache,
		string $className,
		array $methodNames
	)
	{
		$this->annotationsMethodsClassReflectionExtension = $annotationsMethodsClassReflectionExtension;
		$this->cache = $cache;
		$this->className = $className;
		$this->methodNames = $methodNames;
	}

	public function getClass(): string
	{
		return $this->className;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		if (in_array($methodReflection->getName(), $this->methodNames, true)) {
			return true;
		}

		return false;
	}

	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		$cacheKey = $this->generateCacheKey($methodReflection, $scope);
		$type = $this->getCachedValue($cacheKey);

		if ($type instanceof Type) {
			return $type;
		}

		$type = $this->getTypeFromAnnotationsMethodClassReflection($methodReflection, $scope);
		$this->saveCachedValue($cacheKey, $type);

		return $type;
	}

	/**
	 * @param \PHPStan\Reflection\MethodReflection $methodReflection
	 * @param \PHPStan\Analyser\Scope $scope
	 *
	 * @throws \PHPStan\ShouldNotHappenException
	 *
	 * @return \PHPStan\Type\Type
	 */
	protected function getTypeFromAnnotationsMethodClassReflection(MethodReflection $methodReflection, Scope $scope): Type
	{
		if (!$scope->isInClass()) {
			throw new \PHPStan\ShouldNotHappenException();
		}

		if (!$this->annotationsMethodsClassReflectionExtension->hasMethod($scope->getClassReflection(), $methodReflection->getName())) {
			return new ErrorType();
		}

		$annotationMethod = $this->annotationsMethodsClassReflectionExtension->getMethod($scope->getClassReflection(), $methodReflection->getName());

		return ParametersAcceptorSelector::selectSingle($annotationMethod->getVariants())->getReturnType();
	}

	protected function saveCachedValue(string $cacheKey, Type $value): void
	{
		$this->cache->save($cacheKey, $value);
	}

	protected function getCachedValue(string $cacheKey): ?Type
	{
		return $this->cache->load($cacheKey);
	}

	protected function generateCacheKey(MethodReflection $methodReflection, Scope $scope): string
	{
		$filePath = $scope->getFile();

		return sprintf('%s-%d-%s', $filePath, filemtime($filePath), $methodReflection->getName());
	}

}
