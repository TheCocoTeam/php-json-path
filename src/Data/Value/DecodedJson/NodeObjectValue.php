<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\DecodedJson;

use Generator;
use Iterator;
use Remorhaz\JSON\Data\Event\AfterObjectEvent;
use Remorhaz\JSON\Data\Event\BeforeObjectEvent;
use Remorhaz\JSON\Data\Event\PropertyEvent;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use stdClass;

final class NodeObjectValue implements NodeValueInterface, ObjectValueInterface
{

    private $data;

    private $path;

    private $valueFactory;

    public function __construct(
        stdClass $data,
        PathInterface $path,
        NodeValueFactoryInterface $valueFactory
    ) {
        $this->data = $data;
        $this->path = $path;
        $this->valueFactory = $valueFactory;
    }

    public function createChildIterator(): Iterator
    {
        return $this->createChildGenerator();
    }

    private function createChildGenerator(): Generator
    {
        foreach (get_object_vars($this->data) as $name => $property) {
            yield $name => $this
                ->valueFactory
                ->createValue($property, $this->path->copyWithProperty($name));
        }
    }

    public function createEventIterator(): Iterator
    {
        return $this->createEventGenerator($this->data, $this->path);
    }

    private function createEventGenerator(stdClass $data, PathInterface $path): Generator
    {
        yield new BeforeObjectEvent($this);

        foreach (get_object_vars($data) as $name => $property) {
            yield new PropertyEvent($name, $path);
            yield from $this
                ->valueFactory
                ->createValue($property, $path->copyWithProperty($name))
                ->createEventIterator();
        }

        yield new AfterObjectEvent($this);
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }
}
