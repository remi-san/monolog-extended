<?php

declare(strict_types=1);

namespace Monolog\Extended\Tests\Formatter;

use Monolog\Extended\Formatter\NormalizedFormatter;
use Monolog\Extended\Normalizer\LogRecordNormalizer;
use Monolog\Formatter\FormatterInterface;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NormalizedFormatterTest extends TestCase
{
    #[Test]
    public function it_returns_the_record_normalized_and_formatted(): void
    {
        $formatter = new NormalizedFormatter(
            new SimpleFormatter(),
            new TestNormalizer()
        );

        $record = new LogRecord(new \DateTimeImmutable(), 'channel', Level::Info, 'message');

        $formatted = $formatter->format($record);
        self::assertEquals('message : test', $formatted);
    }

    #[Test]
    public function it_returns_the_records_normalized_and_formatted(): void
    {
        $formatter = new NormalizedFormatter(
            new SimpleFormatter(),
            new TestNormalizer()
        );

        $record = new LogRecord(new \DateTimeImmutable(), 'channel', Level::Info, 'message');

        $formatted = $formatter->formatBatch([$record]);
        self::assertEquals(['message : test'], $formatted);
    }
}

final class SimpleFormatter implements FormatterInterface
{
    public function format(LogRecord $record): string
    {
        return $record->message;
    }

    /**
     * @param array<LogRecord> $records
     *
     * @return array<string>
     */
    public function formatBatch(array $records): array
    {
        return array_map(
            fn (LogRecord $record): string => $record->message,
            $records
        );
    }
}

final class TestNormalizer implements LogRecordNormalizer
{
    public function normalize(LogRecord $record): LogRecord
    {
        return $record->with(...['message' => $record->message.' : test']);
    }
}
