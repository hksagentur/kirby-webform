<?php if (! empty($messages)) : ?>
    <div <?= attr([
        'id' => $id ?? null,
        'class' => [
            'inline-error',
            ...A::wrap($class ?? []),
        ],
        ...$attrs ?? [],
    ]) ?>>
        <svg <?= attr([
            'xmlns' => 'http://www.w3.org/2000/svg',
            'viewBox' => '0 0 24 24',
            'class' => [
                'inline-error__icon',
                'icon',
                'icon--alert',
            ],
            'aria-hidden' => 'true',
            'focusable' => 'false',
        ]) ?>>
            <path d="M11.983,0a12.206,12.206,0,0,0-8.51,3.653A11.8,11.8,0,0,0,0,12.207,11.779,11.779,0,0,0,11.8,24h.214A12.111,12.111,0,0,0,24,11.791h0A11.766,11.766,0,0,0,11.983,0ZM10.5,16.542a1.476,1.476,0,0,1,1.449-1.53h.027a1.527,1.527,0,0,1,1.523,1.47,1.475,1.475,0,0,1-1.449,1.53h-.027A1.529,1.529,0,0,1,10.5,16.542ZM11,12.5v-6a1,1,0,0,1,2,0v6a1,1,0,1,1-2,0Z"/>
        </svg>

        <ul class="inline-error__list" role="list">
            <?php if (isset($messages)) : ?>
                <?php foreach ($messages as $message) : ?>
                    <li class="inline-error__list-item">
                        <?= $message ?>
                    </li>
                <?php endforeach ?>
            <?php else : ?>
                <li class="inline-error__list-item">
                    <?= $slot ?>
                </li>
            <?php endif ?>
        </ul>
    </div>
<?php endif ?>
