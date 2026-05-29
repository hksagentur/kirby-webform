<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Kirby\Http\Url;
use Webform\Form\Form;
use Webform\Toolkit\Route;

class AddContext extends Middleware
{
    public function handle(Request $request, Closure $next): Response|array|false
    {
        /** @var Form $form */
        $form = Route::get('form');

        $kirby = App::instance();
        $site = App::instance()->site();

        $pageId = $request->get('_webform_page');
        $blockId = $request->get('_webform_block');

        $page = $pageId ? $this->getReferrerPage($pageId) : $this->getPreviousPage();
        $block = $blockId ? $page?->block($blockId) : null;

        $form->getContext()->add([
            'kirby' => $kirby,
            'site' => $site,
            'page' => $page,
            'block' => $block,
        ]);

        return $next($request);
    }

    protected function getReferrerPage(string $id): ?Page
    {
        $page = App::instance()->site()->find($id);

        if (! $page || ! $page->isAccessible()) {
            return null;
        }

        return $page;
    }

    protected function getPreviousPage(): ?Page
    {
        $url = Url::last();

        if (! $url) {
            return null;
        }

        $home = App::instance()->url(object: true);
        $uri = Url::toObject($url);

        if ($uri->domain() !== $home->domain()) {
            return null;
        }

        $page = App::instance()->site()->find($uri->path());

        if (! $page || ! $page->isAccessible()) {
            return null;
        }

        return $page;
    }
}
