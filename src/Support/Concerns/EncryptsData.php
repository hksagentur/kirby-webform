<?php

namespace Webform\Support\Concerns;

use InvalidArgumentException;
use Kirby\Toolkit\SymmetricCrypto;
use RuntimeException;

trait EncryptsData
{
    protected ?SymmetricCrypto $encrypter = null;

    abstract public function getSecretKey(): string;

    protected function encrypter(): SymmetricCrypto
    {
        if ($this->encrypter !== null) {
            return $this->encrypter;
        }

        if (! SymmetricCrypto::isAvailable()) {
            throw new RuntimeException(
                'The sodium extension is required to use encryption features.'
            );
        }

        $secretKey = $this->getSecretKey();

        if (! $secretKey || strlen($secretKey) !== 32) {
            throw new InvalidArgumentException(sprintf(
                'Invalid secret key length, expected %d-bytes.',
                SODIUM_CRYPTO_SECRETBOX_KEYBYTES
            ));
        }

        return $this->encrypter = new SymmetricCrypto($secretKey);
    }
}
