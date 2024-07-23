<div <?= Html::attr([
    'id' => $id ?? null,
    'class' => A::merge(A::wrap($class ?? []), ['field']),
]) ?>>
    <?php if ($help = $slots->help()) : ?>
        <div class="field__help">
            <?= $help ?>
        </div>
    <?php endif ?>

    <?= $slot ?>
</div>
