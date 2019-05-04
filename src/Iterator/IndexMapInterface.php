<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Iterator;

use Countable;

interface IndexMapInterface extends Countable
{

    public function toArray(): array;

    public function getOuterIndex(int $innerIndex): int;

    public function outerIndexExists(int $outerIndex): bool;

    public function split(): IndexMapInterface;

    public function join(IndexMapInterface $indexMap): IndexMapInterface;

    public function equals(IndexMapInterface $indexMap): bool;
}
