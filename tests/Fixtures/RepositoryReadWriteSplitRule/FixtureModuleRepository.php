<?php

declare(strict_types=1);

namespace Pyz\Zed\FixtureModule\Persistence;

use Orm\Zed\Comment\Persistence\SpyComment;

class FixtureModuleRepository
{
    public function getComment(int $idComment): ?string
    {
        return null;
    }

    public function findComments(): array
    {
        return [];
    }

    public function existsComment(int $idComment): bool
    {
        return false;
    }

    public function checkAvailability(): bool
    {
        return true;
    }

    public function saveComment(SpyComment $commentEntity): void
    {
        $commentEntity->save();
    }

    public function removeComment(SpyComment $commentEntity): void
    {
        $commentEntity->delete();
    }

    protected function buildQuery(): void
    {
    }
}
