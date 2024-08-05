<?php

return [
    'customer' => [
        'singular_label' => 'Customer',
        'plural_label' => 'Customers',
        'labels' => [
            'names' => 'Names',
            'last_names' => 'Last names',
            'identification_type' => 'Identification type',
            'full_name' => 'Full name',
            'is_featured' => 'Featured',
        ],
        'sections' => [
            'personal_information' => 'Personal information',
            'contact_information' => 'Contact information',
        ],
        'values' => [
            'is_featured_true' => 'Yes',
            'is_featured_false' => 'No',
        ],
    ],
    'payment_method' => [
        'singular_label' => 'Payment method',
        'plural_label' => 'Payment methods',
        'labels' => [
            'is_digital' => 'Digital',
        ],
    ],
    'setting' => [
        'singular_label' => 'General',
        'tabs' => [
            'application' => 'Application',
            'social_networks' => 'Social networks',
        ],
        'sections' => [
            'company_information' => 'Company information',
            'settings' => 'General settings',
            'location' => 'Location',
        ],
        'labels' => [
            'identification_number' => 'NIT',
            'name' => 'Name',
            'email' => 'Email',
            'phone_number' => 'Phone',
            'address' => 'address',
            'use_default_location' => 'Use default location',
            'theme_color' => 'Color theme',
        ],
        'helper_text' => [
            'image_field' => 'Max size: <strong>1MB</strong>. Accepted formats: <i>JPG, JPEG, PNG, WEBP.</i>',
        ],
    ],
    'user' => [
        'singular_label' => 'User',
        'labels' => [
            'roles_count' => 'Roles',
        ],
        'sections' => [
            'personal_information' => 'Personal information',
            'contact_information' => 'Contact information',
            'account_information' => 'Account information',
        ],
    ],
];
