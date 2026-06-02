<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\BreakSignal;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\RepeatUntilNode;

/**
 * @implements NodeExecutor<RepeatUntilNode>
 */
final class RepeatUntilExecutor implements NodeExecutor
{
    /**
     * @param RepeatUntilNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        $iterations = 0;

        try {
            do {
                if (++$iterations > 100_000) {
                    throw new RuntimeException('Loop exceeded 100,000 iterations');
                }

                foreach ($node->body as $statement) {
                    $runner->execute($statement);
                }
            } while (! (bool) $runner->execute($node->condition));
        } catch (BreakSignal) {
            //
        }

        return null;
    }
}
