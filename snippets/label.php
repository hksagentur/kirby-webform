<label <?= Html::attr([
    'for' => $for,
    'class' => A::merge(A::wrap($class ?? []), ['label']),
]) ?>>
    <?= $slot ?>
</label>
