<?php

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Fixtures\ClassConstants;

class ClassWithConstants
{
    public const UNTYPED = 'bad';

    /**
     * @var list<string>
     */
    protected const UNTYPED_WITH_SHAPE_DOCBLOCK = ['bad'];

    public const UNTYPED_MULTI_A = 1, UNTYPED_MULTI_B = 2;

    public const string TYPED = 'good';

    public const int TYPED_INT = 1;

    /**
     * @var list<string>
     */
    private const array TYPED_ARRAY_WITH_SHAPE_DOCBLOCK = ['good'];

    public function useConstants(): int
    {
        return self::TYPED_INT + count(self::TYPED_ARRAY_WITH_SHAPE_DOCBLOCK);
    }
}
