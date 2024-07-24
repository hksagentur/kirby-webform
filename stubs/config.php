<?php

return [
    'id' => 'contact',
    'name' => 'Contact Form',
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
