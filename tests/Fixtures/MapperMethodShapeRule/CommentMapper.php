<?php

declare(strict_types=1);

namespace Pyz\Zed\FixtureModule\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CommentTransfer;
use Orm\Zed\Comment\Persistence\SpyComment;

class CommentMapper
{
    public function mapCommentEntityToCommentTransfer(
        SpyComment $commentEntity,
        CommentTransfer $commentTransfer
    ): CommentTransfer {
        return $commentTransfer;
    }

    public function convertComment(SpyComment $commentEntity): CommentTransfer
    {
        return new CommentTransfer();
    }

    public function mapComment(SpyComment $commentEntity): CommentTransfer
    {
        return new CommentTransfer();
    }

    protected function normalize(string $value): string
    {
        return $value;
    }
}
