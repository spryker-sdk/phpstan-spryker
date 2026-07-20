<?php

declare(strict_types=1);

namespace Pyz\Zed\FixtureModule\Business\Model;

class StatefulModel
{
    private static array $cache = [];

    private ?string $memo = null;

    public function __construct(private readonly string $dependency)
    {
    }

    public function remember(): string
    {
        $this->memo = $this->dependency;

        return $this->memo;
    }

    public function accumulate(string $key, string $value): void
    {
        self::$cache[$key] = $value;
    }

    public function append(string $suffix): void
    {
        $this->memo .= $suffix;
    }
}
