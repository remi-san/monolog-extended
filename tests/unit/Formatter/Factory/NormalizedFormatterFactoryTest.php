<?php

declare(strict_types=1);

namespace Monolog\Extended\Tests\Formatter\Factory;

use Monolog\Extended\Formatter\Factory\FormatterFactory;
use Monolog\Extended\Formatter\Factory\NormalizedFormatterFactory;
use Monolog\Extended\Formatter\NormalizedFormatter;
use Monolog\Extended\Normalizer\LogRecordNormalizer;
use Monolog\Formatter\FormatterInterface;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NormalizedFormatterFactoryTest extends TestCase
{
    #[Test]
    public function it_should_create_a_normalized_formatter(): void
    {
        $record = new LogRecord(new \DateTimeImmutable(), 'channel', Level::Info, 'message');

        $factory = new NormalizedFormatterFactory(
            new TestFormatterFactory(),
            new TestNormalizer()
        );

        $formatter = $factory->create([]);

        self::assertInstanceOf(NormalizedFormatter::class, $formatter);
        self::assertEquals('normalized message', $formatter->format($record));
    }
}

final class TestFormatterFactory implements FormatterFactory
{
    public function create(mixed $options): FormatterInterface
    {
        return new TestFormatter();
    }
}

final class TestFormatter implements FormatterInterface
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
        return $record->with(...['message' => 'normalized '.$record->message]);
    }
}
