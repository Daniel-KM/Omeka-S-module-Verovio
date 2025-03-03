<?php declare(strict_types=1);

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

            // Aliases are not used to speed loading and to decrease memory use.
            // TODO Check if the issue is still there.

            'application/vnd.mei+xml' => Media\FileRenderer\Verovio::class,
            'application/vnd.recordare.musicxml' => Media\FileRenderer\Verovio::class,
            'mei' => Media\FileRenderer\Verovio::class,
            'musicxml' => Media\FileRenderer\Verovio::class,
            'mxl' => Media\FileRenderer\Verovio::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'verovio' => View\Helper\Verovio::class,
        ],
    ],
    'block_layouts' => [
        'invokables' => [
            'verovio' => Site\BlockLayout\Verovio::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\SettingsFieldset::class => Form\SettingsFieldset::class,
            Form\SiteSettingsFieldset::class => Form\SiteSettingsFieldset::class,
            Form\VerovioFieldset::class => Form\VerovioFieldset::class,
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
        'block_settings' => [
            'verovio' => [
                'heading' => '',
                'source' => '',
                'template' => '',
            ],
        ],
    ],
];
