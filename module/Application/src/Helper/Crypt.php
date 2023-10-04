<?php

namespace Application\Helper;

class Crypt
{
    // Store the cipher method
    private const CIPHERING = "AES-128-CTR";

    // Use OpenSSl Encryption method
    // private const IV_LENGTH = openssl_cipher_iv_length(CIPHERING);
    private const OPTIONS = 0;

    // Non-NULL Initialization Vector for encryption
    private const CRYPTION_IV = '1234567891011121';

    // Store the encryption key
    private const SECRET_KEY = 'W2-0SS-02';

    public static function encrypt(array $data)
    {
        $string_concat = implode(".", $data);

        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt(
            $string_concat,
            self::CIPHERING,
            self::SECRET_KEY,
            self::OPTIONS,
            self::CRYPTION_IV
        );

        return base64_encode($encryption);
    }

    public static function decrypt(string $value)
    {
        // Use openssl_decrypt() function to decrypt the data
        $decryption = openssl_decrypt(
            base64_decode($value),
            self::CIPHERING,
            self::SECRET_KEY,
            self::OPTIONS,
            self::CRYPTION_IV
        );

        $data = explode('.', $decryption);
        return $data;
    }
}

?>