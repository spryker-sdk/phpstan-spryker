<?php

declare(strict_types=1);

// Minimal stubs for framework/ORM symbols the rules reflect on. Scanned (not analysed) by the
// rule tests via tests/rule-test.neon so fixtures can extend/implement realistic base types.

namespace Spryker\Shared\Kernel\Transfer {
    abstract class AbstractTransfer
    {
    }
}

namespace Spryker\Zed\Kernel\Communication\Controller {
    class AbstractGatewayController
    {
    }
}

namespace Generated\Shared\Transfer {
    class CommentTransfer extends \Spryker\Shared\Kernel\Transfer\AbstractTransfer
    {
    }
}

namespace Orm\Zed\Comment\Persistence {
    class SpyComment
    {
        public function save(): int
        {
            return 0;
        }

        public function delete(): void
        {
        }
    }
}

namespace Symfony\Component\Serializer {
    interface SerializerInterface
    {
    }
}

namespace ApiPlatform\Metadata {
    class Operation
    {
    }
}

namespace ApiPlatform\State {
    interface ProviderInterface
    {
    }

    interface ProcessorInterface
    {
    }
}
