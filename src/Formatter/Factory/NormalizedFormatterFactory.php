<?php

declare(strict_types=1);

namespace Monolog\Extended\Formatter\Factory;

use Monolog\Extended\Formatter\NormalizedFormatter;
use Monolog\Extended\Normalizer\LogRecordNormalizer;
use Monolog\Formatter\FormatterInterface;

final readonly class NormalizedFormatterFactory implements FormatterFactory
{
    public function __construct(
        private FormatterFactory $formatterFactory,
        private LogRecordNormalizer $normalizer
    ) {
    }

    public function create(mixed $options): FormatterInterface
    {
        return new NormalizedFormatter(
            $this->formatterFactory->create($options),
            $this->normalizer
        );
    }
}
