<?php

/**
 * E-Mail Template for Webform Submissions
 *
 * @var \Kirby\Cms\App $kirby
 * @var \Kirby\Cms\Site $site
 * @var \Kirby\Cms\User $user
 */

use Webform\Toolkit\Html;

$surfaceColor ??= '#ffffff';
$backgroundColor ??= '#f2f4f6';
$textColor ??= '#374151';
$accentColor ??= '#004d9d';
$headingColor ??= '#111827';
$footerColor ??= '#6b7280';

$theme = compact(
    'headingColor',
    'textColor',
    'accentColor',
    'footerColor',
    'surfaceColor',
    'backgroundColor',
);

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
            color: <?= Html::css($textColor) ?>;
            background-color: <?= Html::css($backgroundColor) ?>;
        }

        h2 {
            margin: 0 0 24px;
            color: <?= Html::css($headingColor) ?>;
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
            background-color: <?= Html::css($backgroundColor) ?>;
        }

        .main {
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            margin: 40px auto;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            color: <?= Html::css($textColor) ?>;
            background-color: <?= Html::css($surfaceColor) ?>;
        }

        .content {
            padding: 32px;
        }

        .button {
            display: inline-block;
            padding: 12px 34px;
            border: 2px solid <?= Html::css($accentColor) ?>;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
            line-height: 100%;
            text-decoration: none;
            color: #fff;
            background-color: <?= Html::css($accentColor) ?>;
        }

        .nav {
            margin-bottom: 12px;
        }

        .link {
            margin: 0 8px;
            color: <?= Html::css($footerColor) ?>;
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
            color: <?= Html::css($headingColor) ?>;
            font-weight: 600;
        }

        .footer {
            padding: 20px 0;
            color: <?= Html::css($footerColor) ?>;
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
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:32px;margin-bottom:32px;">
                        <tr>
                            <td align="center">
                                <?php snippet('webform/emails/header', [
                                    ...$theme,
                                    'kirby' => $kirby,
                                    'site' => $site,
                                ]) ?>
                            </td>
                        </tr>
                    </table>

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

                        <?php if (! empty($rows)) : ?>
                            <table class="data-table" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;width:100%;margin:24px 0;">
                                <?php foreach ($rows as ['label' => $label, 'value' => $value]) : ?>
                                    <tr>
                                        <th style="font-weight:600;color:<?= esc($headingColor, 'attr') ?>;width:30%;padding:12px 16px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:top;">
                                            <?= esc($label) ?>
                                        </th>
                                        <td style="padding:12px 16px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:top;">
                                            <?= esc($value) ?>
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
                    <?php snippet('webform/emails/footer', [
                        ...$theme,
                        'kirby' => $kirby,
                        'site' => $site,
                        'footerLinks' => $footerLinks ?? [],
                        'footerLines' => $footerLines ?? [],
                    ]) ?>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
