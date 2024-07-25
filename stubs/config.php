<?php

use Uniform\Actions\EmailAction;
use Uniform\Guards\HoneypotGuard;
use Webform\Form\Actions\DatabaseAction;

return [
    'name' => 'Contact Form',
    'guards' => [
        HoneypotGuard::class,
    ],
    'actions' => [
        EmailAction::class,
        DatabaseAction::class,
    ],
    'email' => [
        'subject' => 'Contact Form Submission: {{ name }}',
        'to' => 'info@example.org',
        'from' => 'info@example.org',
    ],
    'database' => [
        'table' => 'submissions',
    ],
    'fields' => [
        'name' => [
            'rules' => ['required'],
            'message' => ['Your name is required'],
        ],
        'email' => [
            'rules' => ['required', 'email'],
            'message' => ['Your email address is required', 'Please enter a valid email address'],
        ],
        'message' => [
            'rules' => ['required'],
            'message' => ['A message is required'],
        ]
    ],
];
