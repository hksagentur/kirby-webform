<button <?= Html::attr([
    'type' => $type ?? 'button',
    'class' => A::merge(A::wrap($class ?? []), ['button']),
]) ?>>
    <?= $slot ?>
</button>
