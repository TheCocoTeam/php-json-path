<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Query;

use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Remorhaz\JSON\Path\Value\ValueListInterface;
use Remorhaz\JSON\Path\Runtime\RuntimeInterface;

interface QueryInterface
{

    public function __invoke(RuntimeInterface $runtime, NodeValueInterface $rootNode): ValueListInterface;

    /**
     * @return CapabilitiesInterface
     * @deprecated
     */
    public function getProperties(): CapabilitiesInterface;

    public function getCapabilities(): CapabilitiesInterface;

    public function getSource(): string;
}
