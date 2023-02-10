<?php

declare(strict_types=1);

namespace Monolog\Extended\Normalizer;

use Monolog\LogRecord;

final class NullNormalizer implements LogRecordNormalizer
{
    public function normalize(LogRecord $record): LogRecord
    {
        return $record;
    }
}
