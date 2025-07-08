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
];
