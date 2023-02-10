<?php

declare(strict_types=1);

namespace Monolog\Extended\Tests\Normalizer;

use Monolog\Extended\Normalizer\JsonSerializableNormalizer;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class JsonSerializableNormalizerTest extends TestCase
{
    private JsonSerializableNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new JsonSerializableNormalizer();
    }

    #[Test]
    public function it_converts_json_serializable_elements_of_the_context_to_primitive_values(): void
    {
        $logRecord = new LogRecord(
            new \DateTimeImmutable(),
            'TEST',
            Level::Info,
            'message',
            [
                'dummy' => [
                    'foo' => new Foo(),
                    'bar',
                    new Bar(),
                ],
                'bar'   => new Foo(),
            ],
            [
                'baz'   => new Foo(),
            ]
        );

        $processedRecord = $this->normalizer->normalize($logRecord);

        self::assertEquals($logRecord->datetime, $processedRecord->datetime);
        self::assertEquals($logRecord->channel, $processedRecord->channel);
        self::assertEquals($logRecord->level, $processedRecord->level);
        self::assertEquals($logRecord->message, $processedRecord->message);
        self::assertEquals(
            [
                'dummy' => [
                    'foo' => ['bar' => 'baz'],
                    'bar',
                    'baz',
                ],
                'bar'   => ['bar' => 'baz'],
            ],
            $processedRecord->context
        );
        self::assertEquals(
            [
                'baz'   => ['bar' => 'baz'],
            ],
            $processedRecord->extra
        );
    }
}

final class Foo implements \JsonSerializable
{
    /**
     * @return array<string, Bar>
     */
    public function jsonSerialize(): array
    {
        return ['bar' => new Bar()];
    }
}

final class Bar implements \JsonSerializable
{
    public function jsonSerialize(): string
    {
        return 'baz';
    }
}
