<?php

declare(strict_types=1);

namespace Pyz\Zed\PersistenceFixtureModule\Persistence;

use Generated\Shared\Transfer\CommentTransfer;
use Orm\Zed\Comment\Persistence\SpyComment;

class FixtureModuleEntityManager
{
    public function saveComment(SpyComment $commentEntity): void
    {
    }

    public function saveNullableComment(?SpyComment $commentEntity): void
    {
    }

    public function createComment(CommentTransfer $commentTransfer): CommentTransfer
    {
        return $commentTransfer;
    }

    protected function persistEntity(SpyComment $commentEntity): SpyComment
    {
        return $commentEntity;
    }
}
