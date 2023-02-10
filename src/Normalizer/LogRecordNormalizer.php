<?php

declare(strict_types=1);

namespace Monolog\Extended\Normalizer;

use Monolog\LogRecord;

interface LogRecordNormalizer
{
    public function normalize(LogRecord $record): LogRecord;
}
