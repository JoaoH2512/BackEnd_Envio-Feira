<?php

declare(strict_types=1);

class AdminConfig
{
    private const SECRET_CODE = 'ADM-2026-SEGURANCA';

    public static function getSecretCode(): string
    {
        return self::SECRET_CODE;
    }
}