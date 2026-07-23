<?php

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Fixtures\Properties;

class ClassWithTypedProperties
{
    public string $typed = 'good';

    /**
     * @var list<string>
     */
    private array $typedArrayWithShapeDocblock = ['good'];

    public function useProperties(): int
    {
        return strlen($this->typed) + count($this->typedArrayWithShapeDocblock);
    }
}
