<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Processor;

use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Remorhaz\JSON\Path\Query\QueryInterface;

interface ProcessorInterface
{

    public function select(QueryInterface $query, NodeValueInterface $rootNode): SelectResultInterface;

    public function selectPaths(QueryInterface $query, NodeValueInterface $rootNode): array;
}
