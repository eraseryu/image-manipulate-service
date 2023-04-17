<?php

namespace App\Common;

class RandomDataGenerator
{
    private const DEFAULT_ALGO = 'sha256';
    private const DEFAULT_LENGTH = 8;

    public function generateIdByString(string $input): string
    {
        $hashBase64 = base64_encode(hash(self::DEFAULT_ALGO, $input, true));
        $hashUrlSafe = strtr($hashBase64, '+/', '-_');
        $hashUrlSafe = rtrim($hashUrlSafe, '=');

        return strtolower(substr($hashUrlSafe, 0, self::DEFAULT_LENGTH));
    }
}
