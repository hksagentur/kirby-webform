<?php /** @var \Kirby\Cms\Site $site */ ?>

<?php /** @var ?array<int, array{title: string, url: string}> $footerLinks */ ?>
<?php /** @var ?array<int, string> $footerLines */ ?>

<?php $footerColor ??= '#6b7280' ?>

<?php if (! empty($footerLinks)) : ?>
    <nav class="nav" style="margin-bottom:12px;">
        <?php foreach ($footerLinks as $index => ['title' => $title, 'url' => $url]) : ?>
            <?= $index > 0 ? '|' : '' ?>
            <a href="<?= esc($url, 'attr') ?>" class="link" target="_blank" style="margin:0 8px;font-size:13px;text-decoration:underline;color:<?= esc($footerColor, 'attr') ?>;">
                <?= esc($title) ?>
            </a>
        <?php endforeach ?>
    </nav>
<?php endif ?>

<?php if (! empty($footerLines)) : ?>
    <?php foreach ($footerLines as $footerLine) : ?>
        <p style="font-size:12px;line-height:18px;margin:8px 0;color:<?= esc($footerColor, 'attr') ?>;">
            <?= esc($footerLine) ?>
        </p>
    <?php endforeach ?>
<?php else : ?>
    <p style="font-size:12px;line-height:18px;margin:8px 0;color:<?= esc($footerColor, 'attr') ?>;">
        &copy; <?= date('Y') ?> <?= $site->title()->esc() ?>
    </p>
<?php endif ?>
