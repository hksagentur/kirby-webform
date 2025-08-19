<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta content="width=device-width" name="viewport" />
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta content="telephone=no,address=no,email=no,date=no,url=no" name="format-detection" />
    <meta content="light" name="color-scheme" />
    <meta content="light" name="supported-color-schemes" />
    <style>
        * {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        h1,
        h2,
        h3,
        img,
        li,
        ol,
        p,
        ul {
            margin-top: 0;
            margin-bottom: 0
        }
    </style>
</head>
<body>
    <table align="center" width="100%" border="0" cellPadding="0" cellSpacing="0" role="presentation" style="max-width:600px;min-width:300px;width:100%;margin-left:auto;margin-right:auto;padding:0.5rem">
        <tbody>
            <tr style="width:100%">
                <td>
                    <?php if ($logo = $site->logo()->toFile()) : ?>
                        <table align="center" width="100%" border="0" cellPadding="0" cellSpacing="0" role="presentation" style="margin:0px">
                            <tbody style="width:100%">
                                <tr style="width:100%">
                                    <td align="center">
                                        <img src="<?= $logo->url() ?>" title="<?= $site->title()->esc('attr') ?>" alt="<?= $logo->alt()->or($site->title())->esc('attr') ?>" style="display:block;width:auto;height:64px;margin-left:auto;margin-right:auto;outline:none;border:none;text-decoration:none;" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table align="center" width="100%" border="0" cellPadding="0" cellSpacing="0" role="presentation" style="max-width:37.5em;height:64px">
                            <tbody>
                                <tr style="width:100%"><td></td></tr>
                            </tbody>
                        </table>
                    <?php endif ?>

                    <?php if (! empty($greeting)) : ?>
                        <h2 style="text-align:left;color:rgb(17, 24, 39);margin-bottom:16px;margin-top:0px;font-size:30px;line-height:36px;font-weight:700">
                            <strong><?= esc($greeting) ?></strong>
                        </h2>
                    <?php endif ?>

                    <?php if (! empty($introLines)) : ?>
                        <?php foreach ($introLines as $line) : ?>
                            <p style="font-size:16px;line-height:24px;margin:16px 0;text-align:left;color:#374151;">
                                <?= esc($line) ?>
                            </p>
                        <?php endforeach ?>
                    <?php endif ?>

                    <?php if (! empty($data)) : ?>
                        <table align="center" width="100%" border="0" cellPadding="0" cellSpacing="0" style="max-width:100%;margin:16px 0px">
                            <tbody style="width:100%">
                                <?php foreach ($data as $key => $value) : ?>
                                    <tr style="width:100%">
                                        <th scope="row" align="left" valign="top" style="width:160px;border-bottom:1px solid lightgray;padding:0.25em 0.5em;margin:0;font-size:16px;line-height:24px;font-weight:700;text-align:left;vertical-align:top;color:#374151;">
                                            <?= esc($form->getField($key)?->getLabel() ?? Str::ucfirst($key)) ?>
                                        </th>
                                        <td align="left" valign="top" style="padding:0.25em 0.5em;border-bottom:1px solid lightgray;margin:0;font-size:16px;line-height:24px;text-align:left;vertical-align:top;color:#374151;">
                                            <?= esc($value ?: t('hksagentur.webform.template.submission.notAvailable'))  ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php endif ?>

                    <?php if (! empty($actionText)) : ?>
                        <table align="center" width="100%" border="0" cellPadding="0" cellSpacing="0" role="presentation" style="max-width:100%;text-align:left;">
                            <tbody>
                                <tr style="width:100%">
                                    <td>
                                        <a href="<?= esc($actionUrl ?? $site->url(), 'attr') ?>" style="color:#ffffff;background-color:#141313;border-color:#141313;padding:12px 34px 12px 34px;border-width:2px;border-style:solid;text-decoration:none;font-size:14px;font-weight:500;border-radius:9999px;line-height:100%;display:inline-block;max-width:100%" target="_blank">
                                            <span><!--[if mso]><i style="letter-spacing: 34px;mso-font-width:-100%;mso-text-raise:18" hidden>&nbsp;</i><![endif]--></span>
                                            <span style="max-width:100%;display:inline-block;line-height:120%;mso-padding-alt:0px;mso-text-raise:9px"><?= esc($actionText) ?></span>
                                            <span><!--[if mso]><i style="letter-spacing: 34px;mso-font-width:-100%" hidden>&nbsp;</i><![endif]--></span>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif ?>

                    <?php if (! empty($outroLines)) : ?>
                        <?php foreach ($outroLines as $line) : ?>
                            <p style="font-size:16px;line-height:24px;margin:16px 0;text-align:left;color:#374151;">
                                <?= esc($line) ?>
                            </p>
                        <?php endforeach ?>
                    <?php endif ?>

                    <?php if (! empty($salutation)) : ?>
                        <p style="font-size:16px;line-height:24px;margin:16px 0;text-align:left;color:#374151;">
                            <?= nl2br(esc($salutation)) ?>
                        </p>
                    <?php endif ?>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
