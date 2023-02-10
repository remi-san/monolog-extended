<?php

declare(strict_types=1);

namespace Monolog\Extended\Formatter;

use Monolog\Extended\Normalizer\LogRecordNormalizer;
use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

final readonly class NormalizedFormatter implements FormatterInterface
{
    public function __construct(
        private FormatterInterface $formatter,
        private LogRecordNormalizer $normalizer
    ) {
    }

    public function format(LogRecord $record): mixed
    {
        return $this->formatter->format(
            $this->normalizer->normalize($record)
        );
    }

    /**
     * @param array<LogRecord> $records
     */
    public function formatBatch(array $records): mixed
    {
        return $this->formatter->formatBatch(
            array_map(
                fn (LogRecord $record): LogRecord => $this->normalizer->normalize($record),
                $records
            )
        );
    }
}
