<?php

namespace PHPStan\Rules\Spryker;

use PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension;
use PHPStan\Rules\Rule;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use Spryker\Zed\Kernel\AbstractFactory;

class MissingPhpDocAnnotationRule implements Rule
{
    /** @var \PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension */
    private $annotationsMethodsClassReflectionExtension;

    public function __construct(
        AnnotationsMethodsClassReflectionExtension $annotationsMethodsClassReflectionExtension
    ) {
        $this->annotationsMethodsClassReflectionExtension = $annotationsMethodsClassReflectionExtension;
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

        if (!in_array($node->name->name, ['getQueryContainer'])) {
            return [];
        }

        foreach ($scope->getClassReflection()->getParentClassesNames() as $className) {
            if ($className !== AbstractFactory::class) {
                continue;
            }

            $name = $node->name->name;

            if (!$this->annotationsMethodsClassReflectionExtension->hasMethod($scope->getClassReflection(), $name)) {
                return ['Method has not annotation'];
            }
        }

        return [];
    }
}