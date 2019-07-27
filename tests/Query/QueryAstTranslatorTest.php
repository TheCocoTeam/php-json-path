<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Test\Query;

use Exception;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Remorhaz\JSON\Path\Query\Exception\QueryAstNotTranslatedException;
use Remorhaz\JSON\Path\Query\Query;
use Remorhaz\JSON\Path\Query\QueryAstTranslator;
use Remorhaz\JSON\Path\Query\QueryCallbackBuilderInterface;
use Remorhaz\JSON\Path\Query\QueryInterface;
use Remorhaz\JSON\Path\Query\QueryCapabilitiesInterface;
use Remorhaz\JSON\Path\Runtime\RuntimeInterface;
use Remorhaz\UniLex\AST\Tree;
use Remorhaz\UniLex\Exception as UniLexException;

/**
 * @covers \Remorhaz\JSON\Path\Query\QueryAstTranslator
 */
class QueryAstTranslatorTest extends TestCase
{

    /**
     * @throws UniLexException
     */
    public function testBuildQuery_Constructed_ReturnsQueryInstance(): void
    {
        $callbackBuilder = $this->createMock(QueryCallbackBuilderInterface::class);
        $translator = new QueryAstTranslator($callbackBuilder);
        $tree = new Tree;
        $tree->setRootNode($tree->createNode('a'));
        self::assertInstanceOf(Query::class, $translator->buildQuery('b', $tree));
    }

    /**
     * @throws UniLexException
     */
    public function testBuildQuery_ThrowsExceptionOnTranslation_ThrowsException(): void
    {
        $callbackBuilder = $this->createMock(QueryCallbackBuilderInterface::class);
        $translator = new QueryAstTranslator($callbackBuilder);
        $tree = new Tree;
        $tree->setRootNode($tree->createNode('a'));

        $callbackBuilder
            ->method('onStart')
            ->willThrowException(new Exception);
        $this->expectException(QueryAstNotTranslatedException::class);
        $translator->buildQuery('b', $tree);
    }

    /**
     * @throws UniLexException
     */
    public function testBuildQuery_Constructed_StartsTreeTranslation(): void
    {
        $callbackBuilder = $this->createMock(QueryCallbackBuilderInterface::class);
        $translator = new QueryAstTranslator($callbackBuilder);
        $tree = new Tree;
        $rootNode = $tree->createNode('a');
        $tree->setRootNode($rootNode);

        $callbackBuilder
            ->expects(self::once())
            ->method('onStart')
            ->with($rootNode);
        $translator->buildQuery('b', $tree);
    }

    /**
     * @throws UniLexException
     */
    public function testBuildQuery_Constructed_FinishesTreeTranslation(): void
    {
        $callbackBuilder = $this->createMock(QueryCallbackBuilderInterface::class);
        $translator = new QueryAstTranslator($callbackBuilder);
        $tree = new Tree;
        $tree->setRootNode($tree->createNode('a'));

        $callbackBuilder
            ->expects(self::once())
            ->method('onFinish')
            ->with();
        $translator->buildQuery('b', $tree);
    }

    /**
     * @throws UniLexException
     */
    public function testBuildQuery_CallbackBuilderProvidesCallback_OnInvocationResultInvokesSameCallback(): void
    {
        $callback = $this->createMock(QueryInterface::class);
        $callbackBuilder = $this->createMock(QueryCallbackBuilderInterface::class);
        $callbackBuilder
            ->method('getQueryCallback')
            ->willReturn($callback);
        $translator = new QueryAstTranslator($callbackBuilder);
        $tree = new Tree;
        $tree->setRootNode($tree->createNode('a'));

        $query = $translator->buildQuery('b', $tree);

        $runtime = $this->createMock(RuntimeInterface::class);
        $rootValue = $this->createMock(NodeValueInterface::class);
        $callback
            ->expects(self::once())
            ->method('__invoke')
            ->with($runtime, $rootValue);
        $query($runtime, $rootValue);
    }

    /**
     * @throws UniLexException
     */
    public function testBuildQuery_CallbackBuilderProvidesGivenQueryProperties_ResultHasSamePropertiesInstance(): void
    {
        $properties = $this->createMock(QueryCapabilitiesInterface::class);
        $callbackBuilder = $this->createMock(QueryCallbackBuilderInterface::class);
        $callbackBuilder
            ->method('getQueryProperties')
            ->willReturn($properties);
        $translator = new QueryAstTranslator($callbackBuilder);
        $tree = new Tree;
        $tree->setRootNode($tree->createNode('a'));

        $query = $translator->buildQuery('b', $tree);
        self::assertSame($properties, $query->getCapabilities());
    }
}
