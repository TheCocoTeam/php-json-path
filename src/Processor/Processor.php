<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Path\Processor;

use Collator;
use Remorhaz\JSON\Path\Iterator\Aggregator\ValueAggregatorCollection;
use Remorhaz\JSON\Path\Iterator\Comparator\ValueComparatorCollection;
use Remorhaz\JSON\Path\Iterator\DecodedJson\NodeValueFactory;
use Remorhaz\JSON\Path\Iterator\Evaluator;
use Remorhaz\JSON\Path\Iterator\Fetcher;
use Remorhaz\JSON\Path\Iterator\NodeValueInterface;
use Remorhaz\JSON\Path\Iterator\Path;
use Remorhaz\JSON\Path\Iterator\ValueIteratorFactory;
use Remorhaz\JSON\Path\Iterator\ValueIteratorFactoryInterface;
use Remorhaz\JSON\Path\Parser\QueryCallbackBuilder;
use Remorhaz\JSON\Path\Parser\TranslatorFactory;
use Remorhaz\JSON\Path\Parser\TranslatorFactoryInterface;
use Remorhaz\UniLex\AST\Translator;
use Remorhaz\UniLex\AST\Tree;
use Throwable;

final class Processor implements ProcessorInterface
{

    private $valueIteratorFactory;

    private $translatorFactory;

    public static function create(): self
    {
        $valueIteratorFactory = new ValueIteratorFactory;
        $translatorFactory = new TranslatorFactory(
            new Fetcher($valueIteratorFactory),
            new Evaluator(
                new ValueComparatorCollection($valueIteratorFactory, new Collator('UTF-8')),
                new ValueAggregatorCollection($valueIteratorFactory)
            )
        );

        return new self($valueIteratorFactory, $translatorFactory);
    }

    public function __construct(
        ValueIteratorFactoryInterface $valueIteratorFactory,
        TranslatorFactoryInterface $translatorFactory
    ) {
        $this->valueIteratorFactory = $valueIteratorFactory;
        $this->translatorFactory = $translatorFactory;
    }

    public function readDecoded(string $path, $decodedJson): ResultInterface
    {
        return new Result(
            $this->valueIteratorFactory,
            ...$this->readOutput($path, $this->createDecodedRootNode($decodedJson))
        );
    }

    private function readOutput(string $path, NodeValueInterface $rootNode): array
    {
        try {
            $ast = new Tree;
            $scheme = $this
                ->translatorFactory
                ->createTranslationScheme($rootNode, $ast);
            $this
                ->translatorFactory
                ->createParser($path, $scheme)
                ->run();

            $astListener = new QueryCallbackBuilder;
            $translator = new Translator($ast, $astListener);
            $translator->run();

            $queryCallback = $astListener->getQueryCallback();

            $valueIteratorFactory = new ValueIteratorFactory;
            $runtime = new Runtime(
                new Fetcher($valueIteratorFactory),
                new Evaluator(
                    new ValueComparatorCollection($valueIteratorFactory, new Collator('UTF-8')),
                    new ValueAggregatorCollection($valueIteratorFactory)
                ),
                $rootNode
            );
            $query = new Query($runtime, $queryCallback);
            $query->execute();
        } catch (Throwable $e) {
            throw new Exception\TranslationFailedException($e);
        }

        return $scheme->getOutput();
    }

    private function createDecodedRootNode($decodedJson): NodeValueInterface
    {
        return (new NodeValueFactory)->createValue($decodedJson, Path::createEmpty());
    }
}
