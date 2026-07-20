<?php

declare(strict_types=1);

namespace Pyz\Zed\FixtureModule\Business\Mapper;

use Generated\Shared\Transfer\CommentTransfer;

class BusinessMapper
{
    public function convertComment(CommentTransfer $commentTransfer): CommentTransfer
    {
        return $commentTransfer;
    }
}
