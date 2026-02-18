<?php $type ??= 'success' ?>

<<?= $as ?? 'div' ?> <?= attr([
    'id' => $id ?? null,
    'class' => [
        'message',
        "message--{$type}",
        ...A::wrap($class ?? []),
    ],
    'role' => $role ?? null,
    'aria-live' => $politeness ?? null,
    ...$attrs ?? [],
]) ?>>
    <svg class="message__icon icon" viewBox="0 0 24 24">
        <?php if ($type === 'success') : ?>
            <path d="m18 7-7 9.5-5-4"/>
            <circle cx="12" cy="12" r="11.5"/>
        <?php elseif ($type === 'warning') : ?>
            <path d="M22.6 22.6a.6.6 0 0 1-.6.9H2a.6.6 0 0 1-.6-1l10.2-20c.2-.5.6-.5.8 0ZM12 17v-7"/>
            <path d="M12 19a.2.2 0 0 0-.3.2.3.3 0 0 0 .3.3.2.2 0 0 0 .3-.3.3.3 0 0 0-.3-.2"/>
        <?php elseif ($type === 'error') : ?>
            <path d="M23 11.8A11.2 11.2 0 0 1 12 23 10.8 10.8 0 0 1 1 12.2 11.2 11.2 0 0 1 12 1a10.8 10.8 0 0 1 11 10.8ZM12 14V7"/>
            <path d="M12 16a.2.2 0 0 0-.3.3.3.3 0 0 0 .3.2.2.2 0 0 0 .2-.3.3.3 0 0 0-.2-.2"/>
        <?php else : ?>
            <circle cx="12" cy="12" r="11"/>
            <path d="M14.5 17H13a1 1 0 0 1-1-1V9.5a.5.5 0 0 0-.5-.5H10m1.7-2.5a.3.3 0 1 0 .3.3.3.3 0 0 0-.3-.3"/>
        <?php endif ?>
    </svg>

    <?= $slot ?>
</<?= $as ?? 'div' ?>>
