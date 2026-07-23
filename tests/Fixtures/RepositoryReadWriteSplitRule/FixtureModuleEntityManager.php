<?php

declare(strict_types=1);

namespace Pyz\Zed\FixtureModule\Persistence;

class FixtureModuleEntityManager
{
    public function createComment(string $message): void
    {
    }

    public function updateComment(string $message): void
    {
    }

    public function deleteComment(int $idComment): void
    {
    }

    public function findComment(int $idComment): ?string
    {
        return null;
    }

    public function getCommentCount(): int
    {
        return 0;
    }
}
