<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\WhileNode;

/**
 * @implements NodeExecutor<WhileNode>
 */
final class WhileExecutor implements NodeExecutor
{
    /**
     * @param WhileNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        $iterations = 0;

        while ((bool) $runner->execute($node->condition)) {
            if (++$iterations > 100_000) {
                throw new RuntimeException('Loop exceeded 100,000 iterations');
            }

            foreach ($node->body as $statement) {
                $runner->execute($statement);
            }
        }

        return null;
    }
}
