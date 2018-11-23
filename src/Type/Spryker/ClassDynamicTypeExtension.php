<?php declare(strict_types = 1);

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PHPStan\Type\Spryker;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

abstract class ClassDynamicTypeExtension implements DynamicMethodReturnTypeExtension
{

	/** @var bool[] */
	protected $methodResolves = [];

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

		if (!$methodCall->name instanceof Identifier) {
			throw new \PHPStan\ShouldNotHappenException();
		}

		$docComment = $scope->getClassReflection()->getNativeReflection()->getDocComment();

		if ($docComment === false) {
			throw new \Exception('Please add PHPDoc block with @method annotation for "getFactory(), getQueryContainer() and/or getFacade()" if one  is used.');
		}

		preg_match_all('#@method\s+(?:(?P<IsStatic>static)\s+)?(?:(?P<Type>[^\(\*]+?)(?<!\|)\s+)?(?P<MethodName>[a-zA-Z0-9_]+)(?P<Parameters>(?:\([^\)]*\))?)#', $docComment, $matches, PREG_SET_ORDER);

		foreach ($matches as $match) {
			if ($match['MethodName'] === $methodCall->name->name) {
				return new ObjectType($match['Type']);
			}
		}

		throw new \Exception(sprintf('Missing @method annotation for "%s()"', $methodCall->name->name));
	}

}
