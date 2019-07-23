<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Query;

interface QueryAstBuilderInterface
{

    public function getInput(): int;

    public function setOutput(int $id, bool $isDefinite): void;

    public function createFilterContext(int $id): int;

    public function split(int $id): int;

    public function evaluate(int $sourceId, int $id): int;

    public function filter(int $contextId, int $evaluatedId): int;

    public function evaluateLogicalOr(int $leftId, int $rightId): int;

    public function evaluateLogicalAnd(int $leftId, int $rightId): int;

    public function evaluateLogicalNot(int $id): int;

    public function calculateIsEqual(int $leftId, int $rightId): int;

    public function calculateIsGreater(int $leftId, int $rightId): int;

    public function calculateIsRegExp(string $pattern, int $id): int;

    public function fetchChildren(int $id, int $matcherId): int;

    public function fetchChildrenDeep(int $id, int $matcherId): int;

    public function matchAnyChild(int $sourceId): int;

    public function matchPropertyStrictly(int $id): int;

    public function matchElementStrictly(int $id): int;

    public function aggregate(string $name, int $id): int;

    public function populateLiteral(int $sourceId, int $valueId): int;

    public function populateLiteralArray(int $sourceId, int $arrayId): int;

    public function populateIndexList(int $sourceId, int ...$indexList): int;

    public function populateIndexSlice(int $sourceId, ?int $start, ?int $end, ?int $step): int;

    public function populateNameList(int $sourceId, string ...$nameList): int;

    public function createScalar($value): int;

    public function createArray(): int;

    public function appendToArray(int $arrayId, int $valueId): int;
}
