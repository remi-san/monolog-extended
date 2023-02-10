<?php

declare(strict_types=1);

namespace Monolog\Extended\Tests\Normalizer;

use Monolog\Extended\Normalizer\LogRecordNormalizer;
use Monolog\Extended\Normalizer\LogRecordNormalizerStack;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class LogRecordNormalizerStackTest extends TestCase
{
    #[Test]
    public function it_returns_the_record_as_is_if_stack_is_empty(): void
    {
        $normalizer = new LogRecordNormalizerStack();

        $record = new LogRecord(new \DateTimeImmutable(), 'channel', Level::Info, 'message', ['context' => 'value']);

        self::assertSame($record, $normalizer->normalize($record));
    }

    #[Test]
    public function it_returns_the_record_modified_by_all_given_normalizers(): void
    {
        $normalizer = new LogRecordNormalizerStack([
            new TestNormalizer(),
            new OtherTestNormalizer(),
        ]);

        $record = new LogRecord(new \DateTimeImmutable(), 'channel', Level::Info, 'message', ['context' => 'value']);

        self::assertEquals('message : test #2', $normalizer->normalize($record)->message);
    }

    #[Test]
    public function it_returns_the_record_modified_by_all_pushed_normalizers(): void
    {
        $normalizer = new LogRecordNormalizerStack();
        $normalizer->push(new TestNormalizer());
        $normalizer->push(new OtherTestNormalizer());

        $record = new LogRecord(new \DateTimeImmutable(), 'channel', Level::Info, 'message', ['context' => 'value']);

        self::assertEquals('message : test #2', $normalizer->normalize($record)->message);
    }
}

final class TestNormalizer implements LogRecordNormalizer
{
    public function normalize(LogRecord $record): LogRecord
    {
        return $record->with(...['message' => $record->message.' : test']);
    }
}

final class OtherTestNormalizer implements LogRecordNormalizer
{
    public function normalize(LogRecord $record): LogRecord
    {
        return $record->with(...['message' => $record->message.' #2']);
    }
}
