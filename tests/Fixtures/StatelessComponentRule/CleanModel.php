<?php

declare(strict_types=1);

namespace Pyz\Zed\FixtureModule\Business\Model;

class CleanModel
{
    private readonly string $dependency;

    public function __construct(string $dependency)
    {
        $this->dependency = $dependency;
    }

    public function calculate(string $input): string
    {
        $result = $this->dependency . $input;

        return $result;
    }
}
