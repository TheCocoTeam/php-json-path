<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Query;

use Remorhaz\UniLex\AST\Tree;
use Remorhaz\UniLex\Exception as UniLexException;

final class QueryAstBuilder implements QueryAstBuilderInterface
{

    private $inputId;

    private $tree;

    public function __construct(Tree $tree)
    {
        $this->tree = $tree;
    }

    /**
     * @return int
     */
    public function getInput(): int
    {
        if (!isset($this->inputId)) {
            $this->inputId = $this
                ->tree
                ->createNode(QueryAstNodeType::GET_INPUT)
                ->getId();
        }

        return $this->inputId;
    }

    /**
     * @param int $id
     * @throws UniLexException
     */
    public function setOutput(int $id): void
    {
        $this
            ->tree
            ->setRootNode(
                $this
                    ->tree
                    ->createNode(QueryAstNodeType::SET_OUTPUT)
                    ->addChild($this->tree->getNode($id))
            );
    }

    /**
     * @param int $id
     * @return int
     * @throws UniLexException
     */
    public function createFilterContext(int $id): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::CREATE_FILTER_CONTEXT)
            ->addChild($this->tree->getNode($id))
            ->getId();
    }

    /**
     * @param int $id
     * @return int
     * @throws UniLexException
     */
    public function split(int $id): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::SPLIT)
            ->addChild($this->tree->getNode($id))
            ->getId();
    }

    /**
     * @param int $sourceId
     * @param int $id
     * @return int
     * @throws UniLexException
     */
    public function evaluate(int $sourceId, int $id): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::EVALUATE)
            ->addChild($this->tree->getNode($sourceId))
            ->addChild($this->tree->getNode($id))
            ->getId();
    }

    /**
     * @param int $contextId
     * @param int $evaluatedId
     * @return int
     * @throws UniLexException
     */
    public function filter(int $contextId, int $evaluatedId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::FILTER)
            ->addChild($this->tree->getNode($contextId))
            ->addChild($this->tree->getNode($evaluatedId))
            ->getId();
    }

    /**
     * @param int $leftEvaluatedId
     * @param int $rightEvaluatedId
     * @return int
     * @throws UniLexException
     */
    public function evaluateLogicalOr(int $leftEvaluatedId, int $rightEvaluatedId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::EVALUATE_LOGICAL_OR)
            ->addChild($this->tree->getNode($leftEvaluatedId))
            ->addChild($this->tree->getNode($rightEvaluatedId))
            ->getId();
    }

    /**
     * @param int $leftEvaluatedId
     * @param int $rightEvaluatedId
     * @return int
     * @throws UniLexException
     */
    public function evaluateLogicalAnd(int $leftEvaluatedId, int $rightEvaluatedId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::EVALUATE_LOGICAL_AND)
            ->addChild($this->tree->getNode($leftEvaluatedId))
            ->addChild($this->tree->getNode($rightEvaluatedId))
            ->getId();
    }

    /**
     * @param int $evaluatedId
     * @return int
     * @throws UniLexException
     */
    public function evaluateLogicalNot(int $evaluatedId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::EVALUATE_LOGICAL_NOT)
            ->addChild($this->tree->getNode($evaluatedId))
            ->getId();
    }

    /**
     * @param int $leftId
     * @param int $rightId
     * @return int
     * @throws UniLexException
     */
    public function calculateIsEqual(int $leftId, int $rightId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::CALCULATE_IS_EQUAL)
            ->addChild($this->tree->getNode($leftId))
            ->addChild($this->tree->getNode($rightId))
            ->getId();
    }

    /**
     * @param int $leftId
     * @param int $rightId
     * @return int
     * @throws UniLexException
     */
    public function calculateIsGreater(int $leftId, int $rightId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::CALCULATE_IS_GREATER)
            ->addChild($this->tree->getNode($leftId))
            ->addChild($this->tree->getNode($rightId))
            ->getId();
    }

    /**
     * @param string $pattern
     * @param int $id
     * @return int
     * @throws UniLexException
     */
    public function calculateIsRegExp(string $pattern, int $id): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::CALCULATE_IS_REGEXP)
            ->addChild($this->tree->getNode($id))
            ->setAttribute('pattern', $pattern)
            ->getId();
    }

    /**
     * @param int $id
     * @param int $matcherId
     * @return int
     * @throws UniLexException
     */
    public function fetchChildren(int $id, int $matcherId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::FETCH_CHILDREN)
            ->addChild($this->tree->getNode($id))
            ->addChild($this->tree->getNode($matcherId))
            ->getId();
    }

    /**
     * @param int $id
     * @param int $matcherId
     * @return int
     * @throws UniLexException
     */
    public function fetchChildrenDeep(int $id, int $matcherId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::FETCH_CHILDREN_DEEP)
            ->addChild($this->tree->getNode($id))
            ->addChild($this->tree->getNode($matcherId))
            ->getId();
    }

    public function matchAnyChild(int $sourceId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::MATCH_ANY_CHILD)
            ->addChild($this->tree->getNode($sourceId))
            ->getId();
    }

    /**
     * @param int $nameListId
     * @return int
     * @throws UniLexException
     */
    public function matchPropertyStrictly(int $nameListId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::MATCH_PROPERTY_STRICTLY)
            ->addChild($this->tree->getNode($nameListId))
            ->getId();
    }

    /**
     * @param int $indexListId
     * @return int
     * @throws UniLexException
     */
    public function matchElementStrictly(int $indexListId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::MATCH_ELEMENT_STRICTLY)
            ->addChild($this->tree->getNode($indexListId))
            ->getId();
    }

    /**
     * @param string $name
     * @param int $id
     * @return int
     * @throws UniLexException
     */
    public function aggregate(string $name, int $id): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::AGGREGATE)
            ->addChild($this->tree->getNode($id))
            ->setAttribute('name', $name)
            ->getId();
    }

    /**
     * @param int $sourceId
     * @param int $valueId
     * @return int
     * @throws UniLexException
     */
    public function populateLiteral(int $sourceId, int $valueId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::POPULATE_LITERAL)
            ->addChild($this->tree->getNode($sourceId))
            ->addChild($this->tree->getNode($valueId))
            ->getId();
    }

    /**
     * @param int $sourceId
     * @param int $arrayId
     * @return int
     * @throws UniLexException
     */
    public function populateLiteralArray(int $sourceId, int $arrayId): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::POPULATE_LITERAL_ARRAY)
            ->addChild($this->tree->getNode($sourceId))
            ->addChild($this->tree->getNode($arrayId))
            ->getId();
    }

    /**
     * @param int $sourceId
     * @param int ...$indexList
     * @return int
     * @throws UniLexException
     */
    public function populateIndexList(int $sourceId, int ...$indexList): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::POPULATE_INDEX_LIST)
            ->addChild($this->tree->getNode($sourceId))
            ->setAttribute('indexList', $indexList)
            ->getId();
    }

    /**
     * @param int $sourceId
     * @param int|null $start
     * @param int|null $end
     * @param int|null $step
     * @return int
     * @throws UniLexException
     */
    public function populateIndexSlice(int $sourceId, ?int $start, ?int $end, ?int $step): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::POPULATE_INDEX_SLICE)
            ->addChild($this->tree->getNode($sourceId))
            ->setAttribute('start', $start)
            ->setAttribute('end', $end)
            ->setAttribute('step', $step)
            ->getId();
    }

    /**
     * @param int $sourceId
     * @param string ...$nameList
     * @return int
     * @throws UniLexException
     */
    public function populateNameList(int $sourceId, string ...$nameList): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::POPULATE_NAME_LIST)
            ->addChild($this->tree->getNode($sourceId))
            ->setAttribute('nameList', $nameList)
            ->getId();
    }

    /**
     * @param $value
     * @return int
     * @throws UniLexException
     */
    public function createScalar($value): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::CREATE_SCALAR)
            ->setAttribute('value', $value)
            ->getId();
    }

    public function createArray(): int
    {
        return $this
            ->tree
            ->createNode(QueryAstNodeType::CREATE_ARRAY)
            ->getId();
    }

    /**
     * @param int $arrayId
     * @param int $valueId
     * @return int
     * @throws UniLexException
     */
    public function appendToArray(int $arrayId, int $valueId): int
    {
        $appendNode = $this
            ->tree
            ->createNode(QueryAstNodeType::APPEND_TO_ARRAY)
            ->addChild($this->tree->getNode($valueId));

        return $this
            ->tree
            ->getNode($arrayId)
            ->addChild($appendNode)
            ->getId();
    }
}