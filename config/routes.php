<?php

return [
    [
        'pattern' => 'webform/(:all)',
        'method' => 'POST',
        'action' => fn (string $path) => form($path)->done()
    ],
];
