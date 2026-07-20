<?php

declare(strict_types=1);

namespace Pyz\Zed\FixtureModule\Communication\Controller;

use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

class GatewayController extends AbstractGatewayController
{
    public function goodAction(CommentTransfer $commentTransfer): CommentTransfer
    {
        return $commentTransfer;
    }

    public function goodNullableAction(CommentTransfer $commentTransfer): ?CommentTransfer
    {
        return null;
    }

    public function goodUnionAction(CommentTransfer $commentTransfer): CommentTransfer|null
    {
        return null;
    }

    public function badParamAction(string $idComment): CommentTransfer
    {
        return new CommentTransfer();
    }

    public function badParamCountAction(CommentTransfer $commentTransfer, string $extra): CommentTransfer
    {
        return $commentTransfer;
    }

    public function badReturnAction(CommentTransfer $commentTransfer): array
    {
        return [];
    }

    public function noReturnTypeAction(CommentTransfer $commentTransfer)
    {
        return $commentTransfer;
    }

    protected function helperMethodNotAnAction(string $input): string
    {
        return $input;
    }
}
