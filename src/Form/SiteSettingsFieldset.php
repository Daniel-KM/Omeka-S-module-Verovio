<?php declare(strict_types=1);

namespace Verovio\Form;

use Common\Form\Element as CommonElement;
use Laminas\Form\Fieldset;

class SiteSettingsFieldset extends Fieldset
{
    protected $label = 'Verovio MEI viewer'; // @translate

    protected $elementGroups = [
        // "Player" is used instead of viewer, because "viewer" is used for a site
        // user role and cannot be translated differently (no context).
        // Player is polysemic too anyway, but less used and more adapted for
        // non-image viewers.
        'player' => 'Players', // @translate
    ];

    public function init(): void
    {
        $versions = include dirname(__DIR__, 2) . '/data/player/versions.php';

        $this
            ->setAttribute('id', 'verovio')
            ->setOption('element_groups', $this->elementGroups)
            ->add([
                'name' => 'verovio_template',
                'type' => CommonElement\OptionalRadio::class,
                'options' => [
                    'element_group' => 'player',
                    'label' => 'Verovio: Default template', // @translate
                    'value_options' => [
                        // Same options than block templates.
                        'common/verovio' => 'App (simple viewer)', // @translate
                        'common/verovio-toolkit' => 'Toolkit (via theme)', // @translate
                        'common/verovio-mei-viewer' => 'Official (Bootstrap 3)', // @translate
                        'common/verovio-viewer' => 'Web (Bootstrap 4)', // @translate
                    ],
                ],
                'attributes' => [
                    'id' => 'verovio_template',
                ],
            ])
            ->add([
                'name' => 'verovio_variant',
                'type' => CommonElement\OptionalSelect::class,
                'options' => [
                    'element_group' => 'player',
                    'label' => 'Verovio: Toolkit variant', // @translate
                    'info' => ' ',
                    'documentation' => 'https://book.verovio.org/installing-or-building-from-sources/javascript-and-webassembly.html',
                    'value_options' => [
                        'wasm' => 'Wasm (recommended)', // @translate
                        'hum' => 'Humdrum', // @translate
                        'asm' => 'Asm (to support old browsers)', // @translate
                    ],
                    'empty_option' => '',
                ],
                'attributes' => [
                    'id' => 'verovio_variant',
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Select a variant…', // @translate
                ],
            ])
            ->add([
                'name' => 'verovio_version',
                'type' => CommonElement\OptionalSelect::class,
                'options' => [
                    'element_group' => 'player',
                    'label' => 'Verovio: Version', // @translate
                    'value_options' => array_combine($versions, $versions),
                    'empty_option' => '',
                ],
                'attributes' => [
                    'id' => 'verovio_version',
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Select a version…', // @translate
                ],
            ])
        ;
    }
}
