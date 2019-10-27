<?php
namespace Verovio;

return [
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'file_renderers' => [
        'invokables' => [
            'verovio' => Media\FileRenderer\Verovio::class,
        ],
        'aliases' => [
            'application/vnd.mei+xml' => 'verovio',
            'application/vnd.recordare.musicxml' => 'verovio',
            'mei' => 'verovio',
            'musicxml' => 'verovio',
            'mxl' => 'verovio',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'verovio' => View\Helper\Verovio::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\SettingsFieldset::class => Form\SettingsFieldset::class,
            Form\SiteSettingsFieldset::class => Form\SiteSettingsFieldset::class,
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'verovio' => [
        'settings' => [
            'verovio_source_property' => null,
        ],
        'site_settings' => [
            'verovio_template' => 'common/verovio',
        ],
    ],
];
