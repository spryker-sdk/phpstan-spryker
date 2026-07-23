<?php

declare(strict_types=1);

namespace Pyz\Glue\FixtureModule\Api\Storefront\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GoodProcessor implements ProcessorInterface
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    /**
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        return null;
    }
}
