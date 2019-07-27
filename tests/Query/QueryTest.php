<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Test\Query;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Remorhaz\JSON\Path\Query\Query;
use Remorhaz\JSON\Path\Query\QueryInterface;
use Remorhaz\JSON\Path\Query\CapabilitiesInterface;
use Remorhaz\JSON\Path\Runtime\RuntimeInterface;
use Remorhaz\JSON\Path\Value\ValueListInterface;

/**
 * @covers \Remorhaz\JSON\Path\Query\Query
 */
class QueryTest extends TestCase
{

    public function testInvoke_ConstructedWithCallback_CallsSameCallback(): void
    {
        $callback = $this->createMock(QueryInterface::class);
        $query = new Query(
            'a',
            $callback,
            $this->createMock(CapabilitiesInterface::class)
        );
        $runtime = $this->createMock(RuntimeInterface::class);
        $rootValue = $this->createMock(NodeValueInterface::class);

        $callback
            ->expects(self::once())
            ->method('__invoke')
            ->with($runtime, $rootValue);
        $query($runtime, $rootValue);
    }

    public function testInvoke_CallbackReturnsValueList_ReturnsSameInstance(): void
    {
        $values = $this->createMock(ValueListInterface::class);
        $callback = $this->createMock(QueryInterface::class);
        $callback
            ->method('__invoke')
            ->willReturn($values);
        $query = new Query(
            'a',
            $callback,
            $this->createMock(CapabilitiesInterface::class)
        );

        $actualValue = $query(
            $this->createMock(RuntimeInterface::class),
            $this->createMock(NodeValueInterface::class)
        );
        self::assertSame($values, $actualValue);
    }

    public function testGetProperties_ConstructedWithGivenProperties_ReturnsSameInstance(): void
    {
        $properties = $this->createMock(CapabilitiesInterface::class);
        $callback = $this->createMock(QueryInterface::class);
        $query = new Query('a', $callback, $properties);

        self::assertSame($properties, $query->getCapabilities());
    }

    public function providerIsDefinite(): array
    {
        return [
            'TRUE' => [true, true],
            'FALSE' => [false, false],
        ];
    }
}
