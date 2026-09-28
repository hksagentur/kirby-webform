<?php if (! empty($marker)) : ?>
    <span <?= attr([
        'class' => [
            'label-marker',
            "label-marker--{$marker}",
        ],
    ]) ?>>
        <?= t("hksagentur.webform.labelMarker.{$marker}") ?>
    </span>
<?php endif ?>
