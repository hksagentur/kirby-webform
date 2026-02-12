<?php

namespace Webform\Form\Actions;

use Closure;
use Kirby\Http\Remote;
use Kirby\Toolkit\Str;
use Webform\Form\FormSubmission;

class Webhook extends Action
{
    protected array|Closure $headers = [];

    protected string|Closure $contentType = 'application/json';
    protected string|Closure $url;

    public function __construct(string|Closure $url)
    {
        $this->url($url);
    }

    public static function create(string|Closure $url): static
    {
        return new static($url);
    }

    public function getUrl(): ?string
    {
        return $this->evaluate($this->url);
    }

    public function url(string|Closure $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->evaluate($this->headers);
    }

    public function headers(array|Closure $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    public function isUrlEncoded(): bool
    {
        return $this->getContentType() === 'application/x-www-form-urlencoded';
    }

    public function isJson(): bool
    {
        return $this->getContentType() === 'application/json';
    }

    public function getContentType(): string
    {
        return $this->evaluate($this->contentType);
    }

    public function contentType(string|Closure $mime): static
    {
        $this->contentType = $mime;

        return $this;
    }

    public function useUrlEncoding(): static
    {
        return $this->contentType('application/x-www-form-urlencoded');
    }

    public function useJsonEncoding(): static
    {
        return $this->contentType('application/json');
    }

    public function execute(FormSubmission $submission): void
    {
        $data = $submission->all();

        $body = $this->isJson()
            ? json_encode($data, JSON_UNESCAPED_SLASHES)
            : http_build_query($data);

        $url = Str::template($this->getUrl(), $data);

        $options = $this->applyFilters('webhook:before', [
            'options' => [
                'headers' => [
                    ...$this->getHeaders(),
                ],
                'method' => 'POST',
                'url' => $url,
                'data' => $body,
            ],
        ], 'options');

        $response = Remote::request($url, $options);

        $this->fireEvent('webhook:after', [
            'response' => $response,
        ]);
    }
}
