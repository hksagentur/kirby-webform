<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use InvalidArgumentException;
use Kirby\Http\Environment;
use Kirby\Toolkit\SymmetricCrypto;
use RuntimeException;

trait CanBeEncrypted
{
    protected bool|Closure $encrypt = false;

    abstract public function getValue(): mixed;
    abstract public function getDefaultValue(): mixed;

    public function getEncryptedValue(): ?string
    {
        $value = $this->getDefaultValue();

        if ($value === null) {
            return null;
        }

        return base64_encode($this->crypto()->encrypt((string) $value));
    }

    public function getDecryptedValue(): ?string
    {
        $value = $this->getValue();

        if ($value === null) {
            return null;
        }

        $value = base64_decode((string) $value, strict: true);

        if ($value === false) {
            return null;
        }

        return $this->crypto()->decrypt($value);
    }

    public function shouldEncrypt(): bool
    {
        return $this->evaluate($this->encrypt);
    }

    public function encrypt(bool|Closure $encrypt = true): static
    {
        $this->encrypt = $encrypt;

        return $this;
    }

    protected ?SymmetricCrypto $crypto = null;

    protected function crypto(): SymmetricCrypto
    {
        if ($this->crypto !== null) {
            return $this->crypto;
        }

        if (! SymmetricCrypto::isAvailable()) {
            throw new RuntimeException(
                'The sodium extension is required to use encryption features.'
            );
        }

        $secretKey = Environment::getGlobally('APP_KEY');

        if (! $secretKey) {
            throw new InvalidArgumentException(sprintf(
                'Missing secret key, expected a base64-encoded %d-byte key.',
                SODIUM_CRYPTO_SECRETBOX_KEYBYTES
            ));
        }

        $secretKey = base64_decode($secretKey, strict: true);

        if (! $secretKey || strlen($secretKey) !== 32) {
            throw new InvalidArgumentException(sprintf(
                'Invalid secret key length, expected %d-bytes.',
                SODIUM_CRYPTO_SECRETBOX_KEYBYTES
            ));
        }

        return $this->crypto = new SymmetricCrypto($secretKey);
    }
}
