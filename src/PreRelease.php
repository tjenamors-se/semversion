<?php

namespace Tjenamors\Semversion;

class PreRelease
{
    private string $value;

    public function __construct(PreReleaseType $type, string $custom)
    {
        $this->value = ($type == PreReleaseType::NONE) ? $custom : $type->value;
    }
    public function getValue(): string
    {
        return $this->value;
    }
}
