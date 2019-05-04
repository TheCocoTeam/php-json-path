<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Iterator\Event;

use Remorhaz\JSON\Path\Iterator\ValueInterface;

final class BeforeObjectEvent implements BeforeObjectEventInterface
{

    private $value;

    public function __construct(ValueInterface $value)
    {
        $this->value = $value;
    }

    public function getValue(): ValueInterface
    {
        return $this->value;
    }
}