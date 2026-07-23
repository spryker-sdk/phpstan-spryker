<?php

declare(strict_types=1);

namespace Pyz\Zed\FixtureModule\Communication\Controller;

class RegularController
{
    public function indexAction(string $anything): array
    {
        return [$anything];
    }
}
