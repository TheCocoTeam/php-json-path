<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Processor;

use Remorhaz\JSON\Data\Path\PathInterface;

final class ExistingSelectOnePathResult implements SelectOnePathResultInterface
{

    private $path;

    private $encoder;

    public function __construct(PathEncoder $encoder, PathInterface $path)
    {
        $this->encoder = $encoder;
        $this->path = $path;
    }

    public function get(): PathInterface
    {
        return $this->path;
    }

    public function encode(): string
    {
        return $this
            ->encoder
            ->encodePath($this->path);
    }
}
