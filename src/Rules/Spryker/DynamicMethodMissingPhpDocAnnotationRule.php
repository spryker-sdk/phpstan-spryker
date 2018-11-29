<?php declare(strict_types = 1);

namespace PHPStan\Rules\Spryker;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\ErrorType;
use PHPStan\Type\ObjectType;

class DynamicMethodMissingPhpDocAnnotationRule implements Rule
{

	/** @var string */
	protected $className;

	/** @var string[] */
	protected $methodNames;

	public function __construct(string $className, array $methodNames)
	{
		$this->className = $className;
		$this->methodNames = $methodNames;
	}

	public function getNodeType(): string
	{
		return \PhpParser\Node\Expr\MethodCall::class;
	}

	/**
	 * @param \PhpParser\Node\Expr\MethodCall $node
	 * @param \PHPStan\Analyser\Scope $scope
	 * @return string[]
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node->name instanceof Node\Identifier) {
			return [];
		}

		if (!in_array($node->name->name, $this->methodNames, true)) {
			return [];
		}

		$type = $scope->getType($node->var);
		$isAscendant = (new ObjectType($this->className))->isSuperTypeOf($type);

		if (!$isAscendant->yes()) {
			return [];
		}

		$type = $scope->getType($node);

		if (!$type instanceof ErrorType) {
			return [];
		}

		return [sprintf('Missing @method annotation for "%s()" in the PHPDoc for class', $node->name->name)];
	}

}
