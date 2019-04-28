<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Iterator\DecodedJson\Event;

use Iterator;
use Remorhaz\JSON\Path\Iterator\Event\BeforeObjectEventInterface;
use Remorhaz\JSON\Path\Iterator\PathInterface;
use Remorhaz\JSON\Path\Iterator\ValueInterface;

final class BeforeObjectEvent implements BeforeObjectEventInterface
{

    private $iteratorFactory;

    public function __construct(ValueInterface $iteratorFactory)
    {
        $this->iteratorFactory = $iteratorFactory;
    }

    /**
     * @return PathInterface
     */
    public function getPath(): PathInterface
    {
        return $this->iteratorFactory->getPath();
    }

    /**
     * @return Iterator
     */
    public function createIterator(): Iterator
    {
        return $this->iteratorFactory->createIterator();
    }
}