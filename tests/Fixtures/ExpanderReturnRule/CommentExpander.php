<?php

declare(strict_types=1);

namespace Pyz\Zed\FixtureModule\Business\Expander;

use Generated\Shared\Transfer\CommentTransfer;

class CommentExpander
{
    public function expandWithAuthor(CommentTransfer $commentTransfer): CommentTransfer
    {
        return $commentTransfer;
    }

    public function expandInPlace(CommentTransfer $commentTransfer): void
    {
    }

    protected function resolveAuthor(CommentTransfer $commentTransfer): void
    {
    }
}
