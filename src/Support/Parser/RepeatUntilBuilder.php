<?php

declare(strict_types = 1);

namespace Phortugol\Support\Parser;

use Phortugol\Concerns\Support\Parser\CanBeFalse;
use Phortugol\Concerns\Support\Parser\CanBeLiteral;
use Phortugol\Concerns\Support\Parser\CanBeTrue;
use Phortugol\Concerns\Support\Parser\CanBeVariable;
use Phortugol\Concerns\Support\Parser\CanWhen;
use Phortugol\Concerns\Support\Parser\HasBody;
use Phortugol\Contracts\Node;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Parser\Nodes\RepeatUntilNode;

final class RepeatUntilBuilder
{
    use CanBeTrue;
    use CanBeFalse;
    use CanBeLiteral;
    use CanBeVariable;
    use CanWhen;
    use HasBody;

    private Node | null $condition = null;

    /**
     * @var list<Node>
     */
    private array $body = [];

    public function build(): RepeatUntilNode
    {
        return new RepeatUntilNode(
            $this->body,
            $this->condition ?? throw new RuntimeException('RepeatUntilBuilder: call when(), true(), false(), literal(), or variable() before build()'),
        );
    }
}
