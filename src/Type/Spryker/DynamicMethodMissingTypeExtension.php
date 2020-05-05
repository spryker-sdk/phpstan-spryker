<?php declare(strict_types = 1);

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PHPStan\Type\Spryker;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Cache\Cache;
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
     * @var \PHPStan\Cache\Cache
     */
    private $cache;

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
     * @param \PHPStan\Cache\Cache $cache
     * @param string $className
     * @param string[] $methodNames
     */
    public function __construct(
        AnnotationsMethodsClassReflectionExtension $annotationsMethodsClassReflectionExtension,
        Cache $cache,
        string $className,
        array $methodNames
    ) {
        $this->annotationsMethodsClassReflectionExtension = $annotationsMethodsClassReflectionExtension;
        $this->cache = $cache;
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
        [$cacheKey, $variableCacheKey] = $this->generateCacheKeys($methodReflection, $scope);
        $type = $this->getCachedValue($cacheKey, $variableCacheKey);

        if ($type instanceof Type) {
            return $type;
        }

        $type = $this->getTypeFromAnnotationsMethodClassReflection($methodReflection, $scope);
        $this->saveCachedValue($cacheKey, $variableCacheKey, $type);

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
            throw new ShouldNotHappenException();
        }

        if (!$this->annotationsMethodsClassReflectionExtension->hasMethod($scope->getClassReflection(), $methodReflection->getName())) {
            return new ErrorType();
        }

        $annotationMethod = $this->annotationsMethodsClassReflectionExtension->getMethod($scope->getClassReflection(), $methodReflection->getName());

        return ParametersAcceptorSelector::selectSingle($annotationMethod->getVariants())->getReturnType();
    }

    /**
     * @param string $cacheKey
     * @param string $variableCacheKey
     * @param \PHPStan\Type\Type $value
     *
     * @return void
     */
    protected function saveCachedValue(string $cacheKey, string $variableCacheKey, Type $value): void
    {
        $this->cache->save($cacheKey, $variableCacheKey, $value);
    }

    /**
     * @param string $cacheKey
     * @param string $variableCacheKey
     *
     * @return \PHPStan\Type\Type|null
     */
    protected function getCachedValue(string $cacheKey, string $variableCacheKey): ?Type
    {
        return $this->cache->load($cacheKey, $variableCacheKey);
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PHPStan\Analyser\Scope $scope
     *
     * @return string[]
     */
    protected function generateCacheKeys(MethodReflection $methodReflection, Scope $scope): array
    {
        $filePath = $scope->getFile();
        $modifiedTime = filemtime($filePath);

        if ($modifiedTime === false) {
            $modifiedTime = time();
        }

        $cacheKey = sprintf('%s-%d-%s', $filePath, filemtime($filePath), $methodReflection->getName());
        $variableCacheKey = sprintf('%d-filemtime', $modifiedTime);

        return [$cacheKey, $variableCacheKey];
    }
}
