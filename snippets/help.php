<div <?= Html::attr([
    'id' => $id ?? null,
    'class' => A::merge(A::wrap($class ?? []), ['help']),
]) ?>>
    <?= $slot ?>
</div>
