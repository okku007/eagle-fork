<?php
/**
 * Refactored CryptoManager (formerly Eagle.class.php)
 * Handles string algorithms, hashing, password hashing, OpenSSL, and Libsodium.
 */

declare(strict_types=1);

namespace App\Services;

class CryptoManager
{
    /**
     * Get available algorithms natively supported by PHP
     */
    public static function getAlgorithms(): array
    {
        $algorithms = [];

        // 1. String algorithms (Encoders/Decoders)
        $functions = [
            "base64_encode" => "base64_encode",
            "base64_decode" => "base64_decode",
            "rot13" => "str_rot13",
            "url_encode" => "urlencode",
            "url_decode" => "urldecode",
            "hex_encode" => "bin2hex",
            "hex_decode" => "hex2bin",
            "html_entities" => "htmlentities",
            "html_entity_decode" => "html_entity_decode",
            "json_encode" => "json_encode",
            "json_decode" => "json_decode",
        ];

        foreach ($functions as $name => $function) {
            if (function_exists($function)) {
                $algorithms[] = [
                    'slug' => self::algorithmSlug($name),
                    'name' => self::algorithmName($name),
                    'algorithm' => $function,
                    'type' => 'string'
                ];
            }
        }

        // 2. Standard Hash algorithms
        $hashes = hash_algos();
        foreach ($hashes as $hash) {
            $algorithms[] = [
                'slug' => self::algorithmSlug($hash),
                'name' => self::algorithmName($hash),
                'algorithm' => $hash,
                'type' => 'hash'
            ];
        }

        // 3. Password Hashing
        $passwordAlgorithms = [
            'password_hash_bcrypt' => PASSWORD_BCRYPT,
            'password_hash_argon2i' => defined('PASSWORD_ARGON2I') ? PASSWORD_ARGON2I : null,
            'password_hash_argon2id' => defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : null,
            'password_verify' => 'password_verify'
        ];

        foreach ($passwordAlgorithms as $name => $const) {
            if ($const !== null) {
                $algorithms[] = [
                    'slug' => self::algorithmSlug($name),
                    'name' => self::algorithmName($name),
                    'algorithm' => ltrim((string)$const, '0..9'),
                    'raw_algo' => $const,
                    'type' => 'password'
                ];
            }
        }

        // 4. OpenSSL Symmetric Encyption
        if (function_exists('openssl_get_cipher_methods')) {
            $ciphers = openssl_get_cipher_methods(true);
            $popularCiphers = ['aes-256-cbc', 'aes-128-cbc', 'aes-256-gcm', 'chacha20-poly1305', 'camellia-256-cbc'];

            foreach ($popularCiphers as $cipher) {
                if (in_array($cipher, $ciphers)) {
                    $algorithms[] = [
                        'slug' => self::algorithmSlug("encrypt_{$cipher}"),
                        'name' => self::algorithmName("Encrypt " . strtoupper($cipher)),
                        'algorithm' => $cipher,
                        'type' => 'encrypt'
                    ];
                    $algorithms[] = [
                        'slug' => self::algorithmSlug("decrypt_{$cipher}"),
                        'name' => self::algorithmName("Decrypt " . strtoupper($cipher)),
                        'algorithm' => $cipher,
                        'type' => 'decrypt'
                    ];
                }
            }
        }

        // 5. Libsodium Cryptography
        if (extension_loaded('sodium')) {
            $sodiumModules = [
                'sodium_generichash' => ['type' => 'sodium_hash', 'name' => 'Libsodium Generic Hash (BLAKE2b)'],
                'sodium_secretbox_encrypt' => ['type' => 'sodium_encrypt', 'name' => 'Libsodium Secretbox Encrypt'],
                'sodium_secretbox_decrypt' => ['type' => 'sodium_decrypt', 'name' => 'Libsodium Secretbox Decrypt'],
            ];

            foreach ($sodiumModules as $slug => $data) {
                $algorithms[] = [
                    'slug' => self::algorithmSlug($slug),
                    'name' => $data['name'],
                    'algorithm' => $slug,
                    'type' => $data['type']
                ];
            }
        }

        return $algorithms;
    }

    public static function groupAlgorithmsByType(): array
    {
        $algorithms = self::getAlgorithms();
        $grouped = [];
        foreach ($algorithms as $algo) {
            $grouped[$algo['type']][] = $algo;
        }
        return $grouped;
    }

    public static function algorithmSlug(string $string): string
    {
        $slug = strtolower($string);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }

    public static function algorithmName(string $string): string
    {
        $name = strtolower($string);
        $name = preg_replace('/[^a-z0-9\.\,\s]+/', ' ', $name);
        return trim($name, ' ');
    }

    public static function getAlgorithmBySlug(string $slug): ?array
    {
        $algorithms = self::getAlgorithms();
        foreach ($algorithms as $algorithm) {
            if ($algorithm['slug'] === $slug) {
                return $algorithm;
            }
        }
        return null;
    }

    /**
     * Resolves the correct PHP function based on module Type.
     */
    public static function executeAlgorithm(array $algorithm, array $opts): string
    {
        if (empty($opts['string'])) {
            return 'ERROR: TARGET_STRING is empty. Please provide a payload.';
        }

        $input = $opts['string'];
        $type = $algorithm['type'];
        $algoName = $algorithm['algorithm'];

        try {
            switch ($type) {
                case 'string':
                    if ($algoName === 'hex2bin' && !ctype_xdigit($input)) {
                        return 'ERROR: Invalid hex string provided for hex_decode.';
                    }
                    $result = @$algoName($input);
                    return $result !== false ? ((is_array($result) || is_object($result)) ? json_encode($result) : (string)$result) : 'ERROR: Operation failed.';

                case 'hash':
                    $salt = $opts['salt'] ?? '';
                    return hash($algoName, $input . $salt);

                case 'password':
                    if ($algorithm['name'] === 'password verify') {
                        $hashToVerify = $opts['compare_hash'] ?? '';
                        if (empty($hashToVerify))
                            return 'ERROR: To verify a password, provide the hash in the COMPARE_HASH field.';
                        return password_verify($input, $hashToVerify) ? 'SUCCESS: Password Match' : 'FAILURE: Password Mismatch';
                    }
                    else {
                        return password_hash($input, $algorithm['raw_algo']) ?: 'ERROR: Failed to generate password hash.';
                    }

                case 'encrypt':
                case 'decrypt':
                    $cipher = $algoName;
                    $key = $opts['key'] ?? '';
                    $iv = $opts['iv'] ?? '';

                    if (empty($key))
                        return 'ERROR: A cryptographic KEY is required for OpenSSL operations.';

                    $ivLength = openssl_cipher_iv_length($cipher);
                    if ($ivLength > 0 && strlen($iv) !== $ivLength) {
                        return "ERROR: Initialization Vector (IV) must be exactly {$ivLength} bytes for {$cipher}. Provided: " . strlen($iv) . " bytes.";
                    }

                    if ($type === 'encrypt') {
                        $encrypted = openssl_encrypt($input, $cipher, $key, 0, $iv);
                        return $encrypted !== false ? $encrypted : 'ERROR: Encryption failed. ' . openssl_error_string();
                    }
                    else {
                        $decrypted = openssl_decrypt($input, $cipher, $key, 0, $iv);
                        return $decrypted !== false ? $decrypted : 'ERROR: Decryption failed. Check Key, IV, and payload integrity. ' . openssl_error_string();
                    }

                case 'sodium_hash':
                    $key = $opts['key'] ?? '';
                    // Generichash can optionally take a key
                    return sodium_bin2hex(sodium_crypto_generichash($input, $key));

                case 'sodium_encrypt':
                case 'sodium_decrypt':
                    $key = $opts['key'] ?? '';
                    $nonce = $opts['iv'] ?? ''; // Using IV field as Nonce for UI simplicity

                    if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
                        return "ERROR: Libsodium secretbox requires a key of exactly " . SODIUM_CRYPTO_SECRETBOX_KEYBYTES . " bytes.";
                    }
                    if (strlen($nonce) !== SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
                        return "ERROR: Libsodium secretbox requires a nonce (IV) of exactly " . SODIUM_CRYPTO_SECRETBOX_NONCEBYTES . " bytes.";
                    }

                    if ($type === 'sodium_encrypt') {
                        $ciphertext = sodium_crypto_secretbox($input, $nonce, $key);
                        return sodium_bin2hex($ciphertext);
                    }
                    else {
                        // We expect hex encoded input for decryption from our UI
                        if (!ctype_xdigit($input))
                            return 'ERROR: Decryption string must be valid hex for Libsodium symmetric.';
                        $rawCipher = sodium_hex2bin($input);
                        $decrypted = sodium_crypto_secretbox_open($rawCipher, $nonce, $key);
                        return $decrypted !== false ? $decrypted : 'ERROR: Libsodium Decryption failed. Authentication tag mismatch or invalid key/nonce.';
                    }

                default:
                    return 'ERROR: Unknown module type detected.';
            }
        }
        catch (\Throwable $e) {
            return 'CRITICAL ERROR: ' . $e->getMessage();
        }
    }
}