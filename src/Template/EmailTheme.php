<?php

namespace Webform\Template;

use InvalidArgumentException;
use Kirby\Cms\App;

readonly class EmailTheme extends ViewModel
{
    public string $textColor;
    public string $accentColor;
    public string $backgroundColor;
    public string $surfaceColor;
    public string $headingColor;
    public string $footerColor;

    public function __construct(
        string $textColor,
        string $accentColor,
        string $backgroundColor,
        ?string $surfaceColor = null,
        ?string $headingColor = null,
        ?string $footerColor = null,
    ) {
        $this->textColor = $textColor;
        $this->backgroundColor = $backgroundColor;
        $this->accentColor = $accentColor;
        $this->surfaceColor = $surfaceColor ?? $backgroundColor;
        $this->headingColor = $headingColor ?? $textColor;
        $this->footerColor = $footerColor ?? $textColor;
    }

    public static function default(): static
    {
        return static::from(
            App::instance()->option('hksagentur.webform.email.theme')
        );
    }

    public static function from(string $name): static
    {
        return new static(...static::config($name));
    }

    public static function create(array $colors, ?string $name = null): static
    {
        $name ??= App::instance()->option('hksagentur.webform.email.theme');

        return new static(...[
            ...static::config($name),
            ...$colors,
        ]);
    }

    public function toArray(): array
    {
        return [
            'surfaceColor' => $this->surfaceColor,
            'backgroundColor' => $this->backgroundColor,
            'textColor' => $this->textColor,
            'accentColor' => $this->accentColor,
            'headingColor' => $this->headingColor,
            'footerColor' => $this->footerColor,
        ];
    }

    protected static function config(string $name): array
    {
        $config = App::instance()->option("hksagentur.webform.email.themes.{$name}");

        if (! is_array($config)) {
            throw new InvalidArgumentException("Unknown email theme [{$name}].");
        }

        return $config;
    }
}
