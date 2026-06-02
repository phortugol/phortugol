<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\BreakSignal;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\ForNode;

/**
 * @implements NodeExecutor<ForNode>
 */
final class ForExecutor implements NodeExecutor
{
    /**
     * @param ForNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        $from = (float) $runner->execute($node->from);
        $to = (float) $runner->execute($node->to);
        $step = $node->step !== null ? (float) $runner->execute($node->step) : 1.0;

        $iterations = 0;
        $i = $from;

        try {
            while (($step > 0 && $i <= $to) || ($step < 0 && $i >= $to)) {
                if (++$iterations > 100_000) {
                    throw new RuntimeException('Loop exceeded 100,000 iterations');
                }

                $runner->env->set($node->variable, $i);

                foreach ($node->body as $statement) {
                    $runner->execute($statement);
                }

                $i += $step;
            }
        } catch (BreakSignal) {
            //
        }

        return null;
    }
}
