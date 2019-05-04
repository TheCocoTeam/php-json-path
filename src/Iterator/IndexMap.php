<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Iterator;

use function count;

final class IndexMap implements IndexMapInterface
{

    private $map;

    public function __construct(int ...$map)
    {
        $this->map = $map;
    }

    public function count()
    {
        return count($this->map);
    }

    public function toArray(): array
    {
        return $this->map;
    }

    public function getOuterIndex(int $innerIndex): int
    {
        if (!isset($this->map[$innerIndex])) {
            throw new Exception\ValueOuterIndexNotFoundException($innerIndex);
        }

        return $this->map[$innerIndex];
    }

    public function outerIndexExists(int $outerIndex): bool
    {
        return in_array($outerIndex, $this->map, true);
    }

    public function split(): IndexMapInterface
    {
        return new self(...array_keys($this->map));
    }

    public function join(IndexMapInterface $indexMap): IndexMapInterface
    {
        $map = [];
        foreach (array_keys($this->map) as $index) {
            $map[] = $indexMap->getOuterIndex($index);
        }

        return new self(...$map);
    }

    public function equals(IndexMapInterface $indexMap): bool
    {
        return $this->toArray() === $indexMap->toArray();
    }
}