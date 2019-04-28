<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Iterator\Event;

use Remorhaz\JSON\Path\Iterator\ValueInterface;

interface ValueEventInterface extends DataEventInterface, ValueInterface
{
}