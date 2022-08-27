<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;


use PhpParser\Node;
use PhpParser\Node\Expr\ErrorSuppress;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\NodeManipulator\AssignManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Collector\NodesToAddCollector;
use RectorPrefix202208\Webmozart\Assert\Assert;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Rector\PostRector\Collector\NodesToRemoveCollector;

use function RectorPrefix202208\dump;

final class RectorReplaceErrorSuppression extends AbstractRector implements ConfigurableRectorInterface
{

    private array $replacementClass;

    public function __construct(
        private readonly NodesToAddCollector $nodesToAddCollector,
        private readonly NodesToRemoveCollector $nodesToRemoveCollector,
        private readonly AssignManipulator $assignManipulator
    ) {}

    /**
     * What nodes are we looking for
     */
    public function getNodeTypes() : array
    {
        return [ErrorSuppress::class];
    }

    public function configure(array $configuration) : void
    {
        Assert::string($configuration['className']);
        Assert::string($configuration['methodName']);

        $this->replacementClass = $configuration;
    }

    public function refactor(Node $node)
    {
        if (!$node instanceof ErrorSuppress) {
            return null;
        }
        if (!$node->expr instanceof Node\Expr\FuncCall) {
            return null;
        }

        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);

        $newArgs = [];
        foreach ($node->expr->getArgs() as $arg) {
            $newArgs[] = $arg->value;
        }

        $newNode = $this->nodeFactory->createStaticCall(
            $this->replacementClass['className'],
            $this->replacementClass['methodName'],
            [
                $node->expr->name->toString(),
                $newArgs,
            ]
        );

//        $this->nodesToAddCollector->addNodeAfterNode($newNode, $parent);
//        $this->removeNode($node);
        return $newNode;
    }

    public function getRuleDefinition() : RuleDefinition
    {
        return new RuleDefinition(
            'Replace constant by new ones',
            [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
@foo_function_call($arg1, $arg2)
CODE_SAMPLE
                ,<<<'CODE_SAMPLE'
className::classMethod('foo_function_call', $arg1, $arg2);
CODE_SAMPLE
                , ['className' => '\Tests\App', 'methodName' => 'getDefine'])]);
    }
}