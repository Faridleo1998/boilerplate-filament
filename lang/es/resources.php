<?php

return [
    'customer' => [
        'singular_label' => 'Cliente',
        'plural_label' => 'Clientes',
        'labels' => [
            'names' => 'Nombre(s)',
            'last_names' => 'Apellido(s)',
            'identification_type' => 'Tipo de identificación',
            'full_name' => 'Nombre completo',
            'is_featured' => 'Destacado',
        ],
        'sections' => [
            'personal_information' => 'Información personal',
            'contact_information' => 'Información de contacto',
        ],
        'values' => [
            'is_featured_true' => 'Sí',
            'is_featured_false' => 'No',
        ],
    ],
    'setting' => [
        'singular_label' => 'General',
        'tabs' => [
            'application' => 'Aplicación',
            'social_networks' => 'Redes sociales',
        ],
        'sections' => [
            'company_information' => 'Información de la empresa',
            'settings' => 'Configuración general',
            'location' => 'Ubicación',
        ],
        'labels' => [
            'identification_number' => 'NIT',
            'name' => 'Nombre',
            'email' => 'Correo electrónico',
            'phone_number' => 'Teléfono',
            'address' => 'Dirección',
            'use_default_location' => 'Usar ubicación como predeterminada',
            'theme_color' => 'Color del tema',
        ],
        'helper_text' => [
            'image_field' => 'Peso máximo: <strong>1MB</strong>. Tipos de archivo permitidos: <i>JPG, JPEG, PNG, WEBP.</i>',
        ],
    ],
    'user' => [
        'singular_label' => 'Usuario',
        'labels' => [
            'roles_count' => 'Roles',
        ],
        'sections' => [
            'personal_information' => 'Información personal',
            'contact_information' => 'Información de contacto',
            'account_information' => 'Información de la cuenta',
        ],
    ],
];
