<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Webform\Toolkit\Flash;
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
        $id = Flash::get('webform.page.previous');

        if ($id === null) {
            return null;
        }

        $page = App::instance()->site()->find($id);

        if (! $page || ! $page->isAccessible()) {
            return null;
        }

        return $page;
    }
}
