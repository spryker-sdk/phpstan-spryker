<?php

declare(strict_types=1);

namespace Pyz\Zed\PersistenceFixtureModule\Persistence;

use Generated\Shared\Transfer\CommentTransfer;
use Orm\Zed\Comment\Persistence\SpyComment;

interface FixtureModuleRepositoryInterface
{
    public function findComment(int $idComment): ?CommentTransfer;

    public function getCommentEntity(int $idComment): SpyComment;
}
