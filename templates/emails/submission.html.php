<?php

/**
 * E-Mail Template for Webform Submissions
 *
 * @var \Kirby\Cms\App $kirby
 * @var \Kirby\Cms\Site $site
 * @var \Kirby\Cms\User $user
 * @var \Webform\Form\Form $form
 */

$surfaceColor = $surfaceColor ?? '#ffffff';
$backgroundColor = $backgroundColor ?? '#f2f4f6';
$textColor = $textColor ?? '#374151';
$accentColor = $accentColor ?? '#004d9d';
$headingColor = $headingColor ?? '#111827';
$footerColor = $footerColor ?? '#6b7280';

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?= $language ?? kirby()->language()?->code() ?>">
<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta content="telephone=no,address=no,email=no,date=no,url=no" name="format-detection" />
    <meta content="light" name="color-scheme" />
    <style>
        :root {
            color-scheme: light;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            color: <?= esc($textColor, 'attr') ?>;
            background-color: <?= esc($backgroundColor, 'attr') ?>;
        }

        h2 {
            margin: 0 0 24px;
            color: <?= esc($headingColor, 'attr') ?>;
            font-size: 24px;
            font-weight: 700;
            line-height: 32px;
        }

        p {
            margin: 16px 0;
            font-size: 16px;
            line-height: 24px;
        }

        .wrapper {
            table-layout: fixed;
            width: 100%;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: <?= esc($backgroundColor, 'attr') ?>;
        }

        .main {
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            margin: 40px auto;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            color: <?= esc($textColor, 'attr') ?>;
            background-color: <?= esc($surfaceColor, 'attr') ?>;
        }

        .logo {
            display: block;
            width: auto;
            height: 48px;
            outline: none;
            border: none;
            text-decoration: none;
        }

        .content {
            padding: 32px;
        }

        .button {
            display: inline-block;
            padding: 12px 34px;
            border: 2px solid <?= esc($accentColor, 'attr') ?>;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
            line-height: 100%;
            text-decoration: none;
            color: #fff;
            background-color: <?= esc($accentColor, 'attr') ?>;
        }

        .nav {
            margin-bottom: 12px;
        }

        .link {
            margin: 0 8px;
            color: <?= esc($footerColor, 'attr') ?>;
            font-size: 13px;
            text-decoration: underline;
        }

        .data-table {
            border-collapse: collapse;
            width: 100%;
            margin: 24px 0;
        }

        .data-table th,
        .data-table td {
            vertical-align: top;
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .data-table th {
            width: 30%;
            color: <?= esc($headingColor, 'attr') ?>;
            font-weight: 600;
        }

        .footer {
            padding: 20px 0;
            color: <?= esc($footerColor, 'attr') ?>;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper" style="table-layout:fixed;width:100%;background-color:<?= esc($backgroundColor, 'attr') ?>;padding-top: 40px;padding-bottom:40px;">
        <table class="main" align="center" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;border-spacing:0;margin:0 auto;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color:<?= esc($textColor, 'attr') ?>;background-color:<?= esc($surfaceColor, 'attr') ?>;">
            <tr>
                <td style="padding:0;">
                    <?php if ($logo = $site->logo()->toFile()) : ?>
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:32px;margin-bottom:32px;">
                            <tr>
                                <td align="center">
                                    <a href="<?= $site->url() ?>" target="_blank" style="display:inline-block;">
                                        <img
                                            src="<?= $logo->url() ?>"
                                            title="<?= $site->title()->esc('attr') ?>"
                                            alt="<?= $logo->alt()->or($site->title())->esc('attr') ?>"
                                            style="display:block;width:auto;height:64px;outline:none;border:none;text-decoration:none;"
                                        />
                                    </a>
                                </td>
                            </tr>
                        </table>
                    <?php endif ?>

                    <div class="content" style="padding:32px;">
                        <?php if (! empty($greeting)) : ?>
                            <h2 style="font-size:24px;font-weight:700;line-height:32px;margin:0 0 24px;color:<?= esc($headingColor, 'attr') ?>;">
                                <?= esc($greeting) ?>
                            </h2>
                        <?php endif ?>

                        <?php if (! empty($introLines)) : ?>
                            <?php foreach ($introLines as $introLine) : ?>
                                <p style="font-size:16px;line-height:24px;margin:16px 0;">
                                    <?= esc($introLine) ?>
                                </p>
                            <?php endforeach ?>
                        <?php endif ?>

                        <?php if (! empty($data)) : ?>
                            <table class="data-table" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;width:100%;margin:24px 0;">
                                <?php foreach ($data as $key => $value) : ?>
                                    <tr>
                                        <th style="font-weight:600;color:<?= esc($headingColor, 'attr') ?>;width:30%;padding:12px 16px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:top;">
                                            <?= esc($form->getChildren()->getIndex()->findByName($key)?->getLabel() ?? Str::ucfirst($key)) ?>
                                        </th>
                                        <td style="padding:12px 16px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:top;">
                                            <?= esc($value ?: t('hksagentur.webform.template.submission.notAvailable'))  ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </table>
                        <?php endif ?>

                        <?php if (! empty($actionText)) : ?>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>
                                        <a href="<?= esc($actionUrl ?? $site->url(), 'attr') ?>" class="button" target="_blank" style="display:inline-block;padding:12px 34px;border:2px solid <?= esc($accentColor, 'attr') ?>;border-radius:9999px;color:#fff;font-size:14px;font-weight:500;line-height:100%;text-decoration:none;background-color:<?= esc($accentColor, 'attr') ?>;">
                                            <?= esc($actionText) ?>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        <?php endif ?>

                        <?php if (! empty($outroLines)) : ?>
                            <?php foreach ($outroLines as $outroLine) : ?>
                                <p style="font-size:16px;line-height:24px;margin:16px 0;">
                                    <?= esc($outroLine) ?>
                                </p>
                            <?php endforeach ?>
                        <?php endif ?>

                        <?php if (! empty($salutation)) : ?>
                            <p style="font-size:16px;line-height:24px;margin:16px 0;">
                                <?= nl2br(esc($salutation)) ?>
                            </p>
                        <?php endif ?>
                    </div>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="footer" style="text-align:center;padding:20px 0;font-size:12px;color:<?= esc($footerColor, 'attr') ?>;">
                    <?php if (!empty($footerLinks)) : ?>
                        <nav class="nav" style="margin-bottom:12px;">
                            <?php foreach ($footerLinks as $index => $link) : ?>
                                <?= $index > 0 ? '|' : '' ?>
                                <a href="<?= esc($link['url'], 'attr') ?>" class="link" target="_blank" style="margin:0 8px;font-size:13px;text-decoration:underline;color:<?= esc($footerColor, 'attr') ?>;">
                                    <?= esc($link['title']) ?>
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
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
