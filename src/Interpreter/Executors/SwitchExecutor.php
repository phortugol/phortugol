<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Interpreter\Runner;
use Phortugol\Parser\Nodes\SwitchNode;

/**
 * @implements NodeExecutor<SwitchNode>
 */
final class SwitchExecutor implements NodeExecutor
{
    /**
     * @param SwitchNode $node
     */
    public function execute(Node $node, Runner $runner): null
    {
        $target = $runner->execute($node->target);

        foreach ($node->cases as $case) {
            if ($runner->execute($case->value) == $target) {
                foreach ($case->body as $statement) {
                    $runner->execute($statement);
                }

                return null;
            }
        }

        if ($node->otherwise !== null) {
            foreach ($node->otherwise as $statement) {
                $runner->execute($statement);
            }
        }

        return null;
    }
}
