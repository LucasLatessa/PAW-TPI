<?php

function isValidTotpCode(string $secretBase32, string $code): bool
{
    $secret = base32DecodeStrict($secretBase32);
    if ($secret === null || $secret === '') {
        return false;
    }

    $period = 30;
    $window = 1;      // tolerancia ±30s
    $digits = 6;

    $counter = intdiv(time(), $period);

    for ($i = -$window; $i <= $window; $i++) {
        $otp = totpSha1($secret, $counter + $i, $digits);
        if (hash_equals($otp, $code)) {
            return true;
        }
    }

    return false;
}

function totpSha1(string $secret, int $counter, int $digits): string
{
    $binCounter = pack('N*', 0) . pack('N*', $counter); // 8 bytes BE
    $hash = hash_hmac('sha1', $binCounter, $secret, true);

    $offset = ord($hash[19]) & 0x0F;
    $binary =
        ((ord($hash[$offset]) & 0x7F) << 24) |
        ((ord($hash[$offset + 1]) & 0xFF) << 16) |
        ((ord($hash[$offset + 2]) & 0xFF) << 8) |
        (ord($hash[$offset + 3]) & 0xFF);

    $mod = 10 ** $digits;
    $otp = (string)($binary % $mod);

    return str_pad($otp, $digits, '0', STR_PAD_LEFT);
}

function base32DecodeStrict(string $b32): ?string
{
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    if ($b32 === '' || preg_match('/[^A-Z2-7]/', $b32)) {
        return null;
    }

    $bits = 0;
    $value = 0;
    $out = '';

    $len = strlen($b32);
    for ($i = 0; $i < $len; $i++) {
        $idx = strpos($alphabet, $b32[$i]);
        if ($idx === false) return null;

        $value = ($value << 5) | $idx;
        $bits += 5;

        if ($bits >= 8) {
            $bits -= 8;
            $out .= chr(($value >> $bits) & 0xFF);
        }
    }

    return $out;
}