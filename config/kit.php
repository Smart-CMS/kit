<?php

// config for SmartCms/Kit
return [
    'admins_table_name' => 'admins',
    'contact_forms_table_name' => 'contact_forms',
    'pages_table_name' => 'pages',
    'notifications' => [
        'update' => 'kit::admin.update',
        'new_contact_form' => 'kit::admin.new_contact_form',
    ],
    'updates' => [
        'enabled' => env('KIT_UPDATES_ENABLED', true),
        'github_repository' => 's-cms/kit',
        'check_frequency' => 'login', // 'login', 'daily', 'disabled'
        'cache_duration' => 3600, // 1 hour in seconds
        'timeout' => 30, // GitHub API timeout
    ],
];
