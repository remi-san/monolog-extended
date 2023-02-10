<?php

declare(strict_types=1);

namespace Monolog\Extended\Formatter\Factory;

use Monolog\Formatter\FormatterInterface;

interface FormatterFactory
{
    public function create(mixed $options): FormatterInterface;
}
