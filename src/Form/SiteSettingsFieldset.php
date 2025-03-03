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
                        'common/verovio-mei-viewer' => 'Official (Bootstrap 3)', // @translate
                        'common/verovio-viewer' => 'Web (Bootstrap 4)', // @translate
                        'common/verovio-toolkit' => 'Custom (via theme)', // @translate
                    ],
                ],
                'attributes' => [
                    'id' => 'verovio_template',
                ],
            ])
        ;
    }
}
