<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter\Executors;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Environment;
use Phortugol\Interpreter\ReturnSignal;
use Phortugol\Interpreter\Runner;
use Phortugol\Interpreter\SubroutineValue;
use Phortugol\Parser\Nodes\CallNode;

/**
 * @implements NodeExecutor<CallNode>
 */
final class CallExecutor implements NodeExecutor
{
    /**
     * @param CallNode $node
     */
    public function execute(Node $node, Runner $runner): mixed
    {
        $subroutine = $runner->env->get($node->name);

        if (! $subroutine instanceof SubroutineValue) {
            throw new RuntimeException("'{$node->name}' is not a procedure or function");
        }

        if (count($node->arguments) !== count($subroutine->parameters)) {
            $expected = count($subroutine->parameters);
            $received = count($node->arguments);

            throw new RuntimeException("'{$node->name}' expects {$expected} argument(s), {$received} given");
        }

        $argumentValues = array_map(fn (Node $argument) => $runner->execute($argument), $node->arguments);

        $localEnvironment = new Environment($runner->env);

        foreach ($subroutine->parameters as $index => $parameter) {
            $localEnvironment->define($parameter, $argumentValues[$index]);
        }

        try {
            return $runner->withEnvironment($localEnvironment, function () use ($subroutine, $runner): null {
                foreach ($subroutine->body as $statement) {
                    $runner->execute($statement);
                }

                return null;
            });
        } catch (ReturnSignal $signal) {
            return $signal->value;
        }
    }
}
