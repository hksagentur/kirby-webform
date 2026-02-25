<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use InvalidArgumentException;
use Kirby\Http\Environment;
use Kirby\Toolkit\Str;
use Kirby\Toolkit\SymmetricCrypto;
use RuntimeException;

trait CanBeEncrypted
{
    protected ?SymmetricCrypto $encrypter = null;

    protected bool|Closure $encrypt = false;
    protected string|Closure|null $secretKey = null;

    public function encrypter(): SymmetricCrypto
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

    public function getSecretKey(): string
    {
        $secretKey = $this->evaluate($this->secretKey);

        if (! $secretKey) {
            $secretKey = Environment::getGlobally('APP_KEY', '');
        }

        if (Str::startsWith($secretKey, 'base64:')) {
            $secretKey = base64_decode(Str::after($secretKey, 'base64:'), strict: true);
        }

        return $secretKey ?: '';
    }

    public function secretKey(string|Closure|null $key = null): static
    {
        $this->secretKey = $key;

        return $this;
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

    public function getValue(): mixed
    {
        $value = parent::getValue();

        if (! $value || ! $this->shouldEncrypt()) {
            return $value;
        }

        return base64_encode($this->encrypter()->encrypt((string) $value));
    }
}
