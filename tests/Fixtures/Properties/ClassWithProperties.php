<?php

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Fixtures\Properties;

class ClassWithProperties
{
    public string $typed = 'good';

    /**
     * @var list<string>
     */
    private array $typedArrayWithShapeDocblock = ['good'];

    protected $untyped;

    protected $untypedMultiA, $untypedMultiB;

    public function useProperties(): int
    {
        return strlen($this->typed) + count($this->typedArrayWithShapeDocblock);
    }
}
