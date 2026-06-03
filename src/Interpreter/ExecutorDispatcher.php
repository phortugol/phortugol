<?php

declare(strict_types = 1);

namespace Phortugol\Interpreter;

use Phortugol\Contracts\Node;
use Phortugol\Contracts\NodeExecutor;
use Phortugol\Exceptions\RuntimeException;
use Phortugol\Interpreter\Executors\ArrayAccessExecutor;
use Phortugol\Interpreter\Executors\ArrayAssignExecutor;
use Phortugol\Interpreter\Executors\ArrayDeclarationExecutor;
use Phortugol\Interpreter\Executors\AssignExecutor;
use Phortugol\Interpreter\Executors\BinaryExecutor;
use Phortugol\Interpreter\Executors\BreakExecutor;
use Phortugol\Interpreter\Executors\CallExecutor;
use Phortugol\Interpreter\Executors\ForExecutor;
use Phortugol\Interpreter\Executors\FunctionExecutor;
use Phortugol\Interpreter\Executors\IfExecutor;
use Phortugol\Interpreter\Executors\LiteralExecutor;
use Phortugol\Interpreter\Executors\ProcedureExecutor;
use Phortugol\Interpreter\Executors\ProgramExecutor;
use Phortugol\Interpreter\Executors\ReadExecutor;
use Phortugol\Interpreter\Executors\RepeatUntilExecutor;
use Phortugol\Interpreter\Executors\ReturnExecutor;
use Phortugol\Interpreter\Executors\SwitchExecutor;
use Phortugol\Interpreter\Executors\UnaryExecutor;
use Phortugol\Interpreter\Executors\VariableExecutor;
use Phortugol\Interpreter\Executors\WhileExecutor;
use Phortugol\Interpreter\Executors\WriteExecutor;
use Phortugol\Parser\Nodes\ArrayAccessNode;
use Phortugol\Parser\Nodes\ArrayAssignNode;
use Phortugol\Parser\Nodes\ArrayDeclarationNode;
use Phortugol\Parser\Nodes\AssignNode;
use Phortugol\Parser\Nodes\BinaryNode;
use Phortugol\Parser\Nodes\BreakNode;
use Phortugol\Parser\Nodes\CallNode;
use Phortugol\Parser\Nodes\ForNode;
use Phortugol\Parser\Nodes\FunctionNode;
use Phortugol\Parser\Nodes\IfNode;
use Phortugol\Parser\Nodes\LiteralNode;
use Phortugol\Parser\Nodes\ProcedureNode;
use Phortugol\Parser\Nodes\ProgramNode;
use Phortugol\Parser\Nodes\ReadNode;
use Phortugol\Parser\Nodes\RepeatUntilNode;
use Phortugol\Parser\Nodes\ReturnNode;
use Phortugol\Parser\Nodes\SwitchNode;
use Phortugol\Parser\Nodes\UnaryNode;
use Phortugol\Parser\Nodes\VariableNode;
use Phortugol\Parser\Nodes\WhileNode;
use Phortugol\Parser\Nodes\WriteNode;

final readonly class ExecutorDispatcher
{
    /**
     * @param array<class-string<Node>, NodeExecutor<Node>> $executors
     */
    public function __construct(
        private array $executors = [],
    ) {
    }

    public static function default(): self
    {
        return new self()
            ->register(class: ProgramNode::class, executor: new ProgramExecutor())
            ->register(class: LiteralNode::class, executor: new LiteralExecutor())
            ->register(class: VariableNode::class, executor: new VariableExecutor())
            ->register(class: BinaryNode::class, executor: new BinaryExecutor())
            ->register(class: UnaryNode::class, executor: new UnaryExecutor())
            ->register(class: WriteNode::class, executor: new WriteExecutor())
            ->register(class: ReadNode::class, executor: new ReadExecutor())
            ->register(class: AssignNode::class, executor: new AssignExecutor())
            ->register(class: IfNode::class, executor: new IfExecutor())
            ->register(class: WhileNode::class, executor: new WhileExecutor())
            ->register(class: ForNode::class, executor: new ForExecutor())
            ->register(class: RepeatUntilNode::class, executor: new RepeatUntilExecutor())
            ->register(class: SwitchNode::class, executor: new SwitchExecutor())
            ->register(class: BreakNode::class, executor: new BreakExecutor())
            ->register(class: ArrayDeclarationNode::class, executor: new ArrayDeclarationExecutor())
            ->register(class: ArrayAccessNode::class, executor: new ArrayAccessExecutor())
            ->register(class: ArrayAssignNode::class, executor: new ArrayAssignExecutor())
            ->register(class: ProcedureNode::class, executor: new ProcedureExecutor())
            ->register(class: FunctionNode::class, executor: new FunctionExecutor())
            ->register(class: CallNode::class, executor: new CallExecutor())
            ->register(class: ReturnNode::class, executor: new ReturnExecutor())
        ;
    }

    /**
     * @param class-string<Node> $class
     * @param NodeExecutor<Node> $executor
     */
    public function register(string $class, NodeExecutor $executor): self
    {
        $executors = $this->executors;
        $executors[$class] = $executor;

        return new self($executors);
    }

    public function dispatch(Node $node, Runner $runner): mixed
    {
        $class = $node::class;

        if (! isset($this->executors[$class])) {
            throw new RuntimeException("No executor registered for node type: {$class}");
        }

        return $this->executors[$class]->execute($node, $runner);
    }
}
