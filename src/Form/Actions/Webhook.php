<?php

namespace Webform\Form\Actions;

use Closure;
use Kirby\Http\Remote;
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
        $headers = $this->getHeaders();

        $contentType = $this->getContentType();
        $url = $this->getUrl();

        $data = in_array($contentType, ['application/json'])
            ? json_encode($submission->all(), JSON_UNESCAPED_SLASHES)
            : http_build_query($submission->all());

        $options = $this->applyFilters('webhook:before', [
            'options' => [
                'headers' => [
                    ...$headers
                ],
                'method' => 'POST',
                'url' => $url,
                'data' => $data,
            ],
        ], 'options');

        $response = Remote::request($url, $options);

        $this->fireEvent('webhook:after', [
            'response' => $response,
        ]);
    }
}
