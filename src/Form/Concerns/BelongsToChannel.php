<?php

namespace Webform\Form\Concerns;

use Webform\Toolkit\Flash;

trait BelongsToChannel
{
    protected string $channel;

    public static function latestChannel(): ?string
    {
        return Flash::get(static::latestChannelKey());
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    protected static function latestChannelKey(): string
    {
        return 'webform.channel';
    }

    protected static function channelKeyFor(string $channel, string $name): string
    {
        return "webform.form.{$channel}.{$name}";
    }

    protected function flashChannel(): void
    {
        Flash::put(static::latestChannelKey(), $this->channel);
    }
}
