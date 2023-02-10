<?php

declare(strict_types=1);

namespace Monolog\Extended\Normalizer;

use Monolog\LogRecord;

final class JsonSerializableNormalizer implements LogRecordNormalizer
{
    public function normalize(LogRecord $record): LogRecord
    {
        return $record->with(
            ...[
                'context' => self::serialize($record->context),
                'extra'   => self::serialize($record->extra),
            ]
        );
    }

    private static function serialize(mixed $object): mixed
    {
        if (\is_array($object)) {
            return self::serializeArray($object);
        }

        if ($object instanceof \JsonSerializable) {
            return self::serializeJsonSerializable($object);
        }

        return $object;
    }

    private static function serializeJsonSerializable(\JsonSerializable $object): mixed
    {
        return self::serialize($object->jsonSerialize());
    }

    /**
     * @param array<mixed> $array
     *
     * @return array<mixed>
     */
    private static function serializeArray(array $array): array
    {
        return array_map(
            static fn ($extraValue): mixed => self::serialize($extraValue),
            $array
        );
    }
}
