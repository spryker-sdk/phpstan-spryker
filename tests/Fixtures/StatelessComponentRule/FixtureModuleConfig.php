<?php

declare(strict_types=1);

namespace Pyz\Zed\FixtureModule\Business;

class FixtureModuleConfig
{
    private ?string $resolved = null;

    public function getResolved(): string
    {
        $this->resolved = 'config classes are exempt';

        return $this->resolved;
    }
}
