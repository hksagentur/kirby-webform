<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Kirby\Http\Environment;
use Kirby\Toolkit\Str;
use Webform\Support\Concerns\EncryptsData;

trait CanBeEncrypted
{
    use EncryptsData;

    protected bool|Closure $encrypt = false;
    protected string|Closure|null $secretKey = null;

    public function getValue(): mixed
    {
        $value = parent::getValue();

        if (! $value || ! $this->shouldEncrypt()) {
            return $value;
        }

        return base64_encode($this->encrypter()->encrypt((string) $value));
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
}
