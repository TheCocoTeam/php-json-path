<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

use Remorhaz\JSON\Data\ObjectValueInterface;
use Remorhaz\JSON\Data\ValueInterface;

final class AfterObjectEvent implements AfterObjectEventInterface
{

    private $value;

    public function __construct(ObjectValueInterface $value)
    {
        $this->value = $value;
    }

    public function getValue(): ValueInterface
    {
        return $this->value;
    }
}
