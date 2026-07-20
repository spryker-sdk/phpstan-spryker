<?php

declare(strict_types=1);

namespace Pyz\Glue\FixtureModule\Api\Storefront\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

class BadProcessor implements ProcessorInterface
{
    public function __construct(private readonly string $notASerializer)
    {
    }

    /**
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return null;
    }
}
