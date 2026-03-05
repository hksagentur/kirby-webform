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

    public static function handle(FormSubmission $submission, mixed ...$arguments): mixed
    {
        return static::create(...$arguments)->execute($submission);
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

    public function getContentType(): string
    {
        return $this->evaluate($this->contentType);
    }

    public function usesUrlEncoded(): bool
    {
        return $this->getContentType() === 'application/x-www-form-urlencoded';
    }

    public function usesJsonEncoding(): bool
    {
        return $this->getContentType() === 'application/json';
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

    public function execute(FormSubmission $submission): mixed
    {
        $data = $submission->all();

        $url = Str::template($this->getUrl(), $data);

        $body = $this->usesJsonEncoding()
            ? json_encode($data, JSON_UNESCAPED_SLASHES)
            : http_build_query($data, '', '&', PHP_QUERY_RFC3986);

        $options = $this->apply('webhook:before', [
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

        $this->dispatch('webhook:after', [
            'response' => $response,
        ]);

        return $response->code() >= 200 && $response->code() < 300;
    }
}
