<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Iterator;

use Generator;
use Iterator;
use Remorhaz\JSON\Path\Iterator\Event\ScalarEvent;

final class ResultValue implements ResultValueInterface
{

    private $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function getData(): bool
    {
        return $this->value;
    }

    public function createIterator(): Iterator
    {
        return $this->createGenerator();
    }

    private function createGenerator(): Generator
    {
        yield new ScalarEvent($this);
    }
}
