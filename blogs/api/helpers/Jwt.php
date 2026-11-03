<?php
/**
 * Minimal dependency-free JWT (HS256) encode/decode.
 * Sufficient for this project; swap for firebase/php-jwt if you add Composer.
 */

declare(strict_types=1);

class Jwt
{
    /** Create a signed JWT for the given claims. */
    public static function encode(array $claims): string
    {
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];

        $now = time();
        $payload = array_merge([
            'iss' => JWT_ISSUER,
            'iat' => $now,
            'exp' => $now + JWT_TTL,
        ], $claims);

        $segments = [
            self::base64UrlEncode(json_encode($header)),
            self::base64UrlEncode(json_encode($payload)),
        ];

        $signing = implode('.', $segments);
        $signature = hash_hmac('sha256', $signing, JWT_SECRET, true);
        $segments[] = self::base64UrlEncode($signature);

        return implode('.', $segments);
    }

    /**
     * Verify + decode a JWT. Returns claims array, or null when invalid/expired.
     */
    public static function decode(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$encHeader, $encPayload, $encSignature] = $parts;

        $expected = self::base64UrlEncode(
            hash_hmac('sha256', "$encHeader.$encPayload", JWT_SECRET, true)
        );

        if (!hash_equals($expected, $encSignature)) {
            return null;
        }

        $payload = json_decode(self::base64UrlDecode($encPayload), true);
        if (!is_array($payload)) {
            return null;
        }

        if (isset($payload['exp']) && time() >= (int) $payload['exp']) {
            return null;
        }

        return $payload;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/')) ?: '';
    }
}
