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
            'mei' => 'verovio',
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
    ],
];
