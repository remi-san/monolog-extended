<?php

declare(strict_types=1);

namespace Monolog\Extended\Normalizer;

use Assert\Assert;
use Monolog\LogRecord;

final class LogRecordNormalizerStack implements LogRecordNormalizer
{
    /**
     * @param array<LogRecordNormalizer> $normalizers
     */
    public function __construct(
        private array $normalizers = []
    ) {
        Assert::that($normalizers)->all()->isInstanceOf(LogRecordNormalizer::class);
    }

    public function push(LogRecordNormalizer $normalizer): void
    {
        $this->normalizers[] = $normalizer;
    }

    public function normalize(LogRecord $record): LogRecord
    {
        return array_reduce(
            $this->normalizers,
            static fn (LogRecord $record, LogRecordNormalizer $normalizer): LogRecord => $normalizer->normalize($record),
            $record
        );
    }
}
