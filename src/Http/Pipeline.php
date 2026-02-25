<?php

namespace Webform\Http;

use Closure;
use Kirby\Cms\App;
use Kirby\Exception\Exception;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Throwable;

class Pipeline
{
    protected ?Request $request = null;
    protected ?Closure $finally = null;

    public function __construct(
        protected array $pipes = []
    ) {
    }

    public function through(array $pipes): static
    {
        $this->pipes = $pipes;

        return $this;
    }

    public function then(Closure $destination): Response|array|false
    {
        $request = $this->getRequest();

        $pipeline = array_reduce(
            array_reverse($this->pipes),
            $this->carry(),
            $this->prepareDestination($destination)
        );

        try {
            return $pipeline($request);
        } finally {
            $result = $this->finally ? ($this->finally)($request) : null;

            if ($result !== null) {
                return $result;
            }
        }
    }

    public function finally(Closure $callback): static
    {
        $this->finally = $callback;

        return $this;
    }

    public function send(Request $request): static
    {
        $this->request = $request;

        return $this;
    }

    protected function getRequest(): Request
    {
        return $this->request ?? App::instance()->request();
    }

    protected function prepareDestination(Closure $destination): Closure
    {
        return function (Request $request, mixed ...$args) use ($destination): Response|array|false {
            try {
                return $destination($request, ...$args);
            } catch (Throwable $exception) {
                return $this->handleException($exception);
            }
        };
    }

    protected function carry(): Closure
    {
        return function ($stack, $pipe) {
            return function (Request $request, mixed ...$args) use ($stack, $pipe): Response|array|false {
                try {
                    if (is_callable($pipe)) {
                        return $pipe($request, $stack, ...$args);
                    }

                    if (is_string($pipe)) {
                        $pipe = new $pipe();
                    }

                    return $pipe->handle($request, $stack, ...$args);
                } catch (Throwable $exception) {
                    return $this->handleException($exception);
                }
            };
        };
    }

    protected function handleException(Throwable $exception): Response|array|false
    {
        if ($exception instanceof Exception) {
            die(App::instance()->io($exception));
        }

        throw $exception;
    }
}
