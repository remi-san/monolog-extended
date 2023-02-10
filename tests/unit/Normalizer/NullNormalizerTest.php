<?php

declare(strict_types=1);

namespace Monolog\Extended\Tests\Normalizer;

use Monolog\Extended\Normalizer\NullNormalizer;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NullNormalizerTest extends TestCase
{
    #[Test]
    public function it_returns_the_record_as_is(): void
    {
        $normalizer = new NullNormalizer();

        $record = new LogRecord(new \DateTimeImmutable(), 'channel', Level::Info, 'message', ['context' => 'value']);

        self::assertSame($record, $normalizer->normalize($record));
    }
}
